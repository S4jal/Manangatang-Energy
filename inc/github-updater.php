<?php
/**
 * GitHub theme updater.
 *
 * Lets the theme update itself from GitHub Releases through WordPress's
 * normal theme-update system (Dashboard → Updates, Appearance → Themes),
 * plus a "Theme Update" admin page with a manual "Check now" button.
 *
 * How releases work:
 *  - Bump "Version:" in style.css.
 *  - Create a GitHub Release whose tag is that version (e.g. 1.0.1 or v1.0.1).
 *  - Either attach a built .zip asset (folder named manangatang-energy/ inside)
 *    or rely on GitHub's auto "Source code (zip)" — the updater renames it.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Updater.
 */
class ME_GitHub_Updater {

	const CACHE = 'me_github_release';

	/** @var string */
	private $slug;
	/** @var string */
	private $version;
	/** @var string owner/repo */
	private $repo;
	/** @var string optional access token (private repos) */
	private $token;

	/**
	 * Wire up the update hooks.
	 */
	public function __construct() {
		$this->slug    = get_template();
		$theme         = wp_get_theme( $this->slug );
		$this->version = $theme->get( 'Version' );
		$this->repo    = trim( (string) get_option( 'me_github_repo', '' ) );
		$this->token   = trim( (string) get_option( 'me_github_token', '' ) );

		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check' ) );
		add_filter( 'upgrader_source_selection', array( $this, 'rename_source' ), 10, 4 );
		add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );
	}

	/**
	 * Fetch the latest GitHub release (cached).
	 *
	 * @return array|null { version, package, url } or null.
	 */
	public function latest() {
		if ( ! $this->repo ) {
			return null;
		}
		$cached = get_transient( self::CACHE );
		if ( false !== $cached ) {
			return $cached ? $cached : null;
		}

		$url  = 'https://api.github.com/repos/' . $this->repo . '/releases/latest';
		$args = array(
			'timeout' => 15,
			'headers' => array(
				'Accept'     => 'application/vnd.github+json',
				'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ),
			),
		);
		if ( $this->token ) {
			$args['headers']['Authorization'] = 'Bearer ' . $this->token;
		}

		$res = wp_remote_get( $url, $args );
		if ( is_wp_error( $res ) || 200 !== (int) wp_remote_retrieve_response_code( $res ) ) {
			set_transient( self::CACHE, array(), HOUR_IN_SECONDS );
			return null;
		}

		$d = json_decode( wp_remote_retrieve_body( $res ), true );
		if ( empty( $d['tag_name'] ) ) {
			set_transient( self::CACHE, array(), HOUR_IN_SECONDS );
			return null;
		}

		// Prefer an attached .zip asset; fall back to the source zipball.
		$package = isset( $d['zipball_url'] ) ? $d['zipball_url'] : '';
		if ( ! empty( $d['assets'] ) ) {
			foreach ( $d['assets'] as $a ) {
				if ( isset( $a['name'], $a['browser_download_url'] ) && '.zip' === strtolower( substr( $a['name'], -4 ) ) ) {
					$package = $a['browser_download_url'];
					break;
				}
			}
		}

		$remote = array(
			'version' => ltrim( $d['tag_name'], 'vV' ),
			'package' => $package,
			'url'     => isset( $d['html_url'] ) ? $d['html_url'] : '',
		);
		set_transient( self::CACHE, $remote, 6 * HOUR_IN_SECONDS );
		return $remote;
	}

	/**
	 * Current installed version.
	 *
	 * @return string
	 */
	public function current() {
		return $this->version;
	}

	/**
	 * Configured repo (owner/repo).
	 *
	 * @return string
	 */
	public function repo() {
		return $this->repo;
	}

	/**
	 * Inject the update into the themes transient when a newer release exists.
	 *
	 * @param object $transient Update transient.
	 * @return object
	 */
	public function check( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		$remote = $this->latest();
		if ( $remote && ! empty( $remote['version'] ) && ! empty( $remote['package'] )
			&& version_compare( $this->version, $remote['version'], '<' ) ) {
			$transient->response[ $this->slug ] = array(
				'theme'       => $this->slug,
				'new_version' => $remote['version'],
				'url'         => $remote['url'],
				'package'     => $remote['package'],
			);
		}
		return $transient;
	}

	/**
	 * Rename the extracted GitHub folder (e.g. owner-repo-sha) to the theme slug.
	 *
	 * @param string $source        Extracted folder.
	 * @param string $remote_source Parent dir.
	 * @param object $upgrader      Upgrader.
	 * @param array  $hook_extra    Context.
	 * @return string|WP_Error
	 */
	public function rename_source( $source, $remote_source, $upgrader, $hook_extra = null ) {
		global $wp_filesystem;
		if ( empty( $hook_extra['theme'] ) || $hook_extra['theme'] !== $this->slug || ! $wp_filesystem ) {
			return $source;
		}
		$desired = trailingslashit( trailingslashit( $remote_source ) . $this->slug );
		if ( untrailingslashit( $source ) === untrailingslashit( $desired ) ) {
			return $source;
		}
		if ( $wp_filesystem->move( $source, $desired, true ) ) {
			return $desired;
		}
		return $source;
	}

	/**
	 * Clear the release cache after a theme update.
	 *
	 * @param object $upgrader Upgrader.
	 * @param array  $options  Process info.
	 */
	public function purge( $upgrader, $options ) {
		if ( isset( $options['type'] ) && 'theme' === $options['type'] ) {
			delete_transient( self::CACHE );
		}
	}
}

/**
 * Singleton instance.
 *
 * @return ME_GitHub_Updater
 */
function me_github_updater() {
	static $instance = null;
	if ( null === $instance ) {
		$instance = new ME_GitHub_Updater();
	}
	return $instance;
}
add_action( 'admin_init', 'me_github_updater' );
add_action( 'after_setup_theme', 'me_github_updater' );

/* ===================== Admin page ===================== */

/**
 * Register the "Theme Update" submenu under the Manangatang menu
 * (falls back to Appearance if the plugin menu is absent).
 */
function me_theme_update_menu() {
	$parent = menu_page_url( 'mec-settings', false ) ? 'mec-settings' : 'themes.php';
	add_submenu_page(
		$parent,
		__( 'Theme Update', 'manangatang-energy' ),
		__( 'Theme Update', 'manangatang-energy' ),
		'update_themes',
		'me-theme-update',
		'me_render_theme_update_page'
	);
}
add_action( 'admin_menu', 'me_theme_update_menu', 30 );

/**
 * Save GitHub settings / handle "Check now".
 */
function me_handle_theme_update_actions() {
	if ( ! current_user_can( 'update_themes' ) ) {
		wp_die( esc_html__( 'Permission denied.', 'manangatang-energy' ) );
	}
	check_admin_referer( 'me_theme_update', 'me_tu_nonce' );

	if ( isset( $_POST['me_save_repo'] ) ) {
		update_option( 'me_github_repo', sanitize_text_field( wp_unslash( $_POST['me_github_repo'] ?? '' ) ) );
		update_option( 'me_github_token', sanitize_text_field( wp_unslash( $_POST['me_github_token'] ?? '' ) ) );
	}

	// Force a fresh check.
	delete_transient( ME_GitHub_Updater::CACHE );
	delete_site_transient( 'update_themes' );

	wp_safe_redirect( add_query_arg( array( 'page' => 'me-theme-update', 'checked' => '1' ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_me_theme_update', 'me_handle_theme_update_actions' );

/**
 * Render the Theme Update page.
 */
function me_render_theme_update_page() {
	if ( ! current_user_can( 'update_themes' ) ) {
		return;
	}
	$updater = me_github_updater();
	$repo    = get_option( 'me_github_repo', '' );
	$token   = get_option( 'me_github_token', '' );
	$current = $updater->current();
	$remote  = $updater->latest();
	$latest  = $remote && ! empty( $remote['version'] ) ? $remote['version'] : '';
	$has_new = $latest && version_compare( $current, $latest, '<' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Theme Update', 'manangatang-energy' ); ?></h1>

		<?php if ( isset( $_GET['checked'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Checked for updates.', 'manangatang-energy' ); ?></p></div>
		<?php endif; ?>

		<div style="max-width:680px">
			<table class="form-table" role="presentation">
				<tr>
					<th><?php esc_html_e( 'Installed version', 'manangatang-energy' ); ?></th>
					<td><strong><?php echo esc_html( $current ); ?></strong></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Latest on GitHub', 'manangatang-energy' ); ?></th>
					<td>
						<?php if ( ! $repo ) : ?>
							<em><?php esc_html_e( 'Set the repository below first.', 'manangatang-energy' ); ?></em>
						<?php elseif ( $latest ) : ?>
							<strong><?php echo esc_html( $latest ); ?></strong>
							<?php if ( $has_new ) : ?>
								<span style="color:#1f8f47;font-weight:600"> — <?php esc_html_e( 'update available', 'manangatang-energy' ); ?></span>
								&nbsp;<a class="button button-primary" href="<?php echo esc_url( admin_url( 'update-core.php' ) ); ?>"><?php esc_html_e( 'Go to Updates', 'manangatang-energy' ); ?></a>
							<?php else : ?>
								<span style="color:#646970"> — <?php esc_html_e( 'up to date', 'manangatang-energy' ); ?></span>
							<?php endif; ?>
						<?php else : ?>
							<em><?php esc_html_e( 'Could not read a release (check the repo name / that a release exists).', 'manangatang-energy' ); ?></em>
						<?php endif; ?>
					</td>
				</tr>
			</table>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="me_theme_update" />
				<?php wp_nonce_field( 'me_theme_update', 'me_tu_nonce' ); ?>
				<h2 class="title"><?php esc_html_e( 'GitHub repository', 'manangatang-energy' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th><label for="me_github_repo"><?php esc_html_e( 'Repository (owner/repo)', 'manangatang-energy' ); ?></label></th>
						<td><input type="text" id="me_github_repo" name="me_github_repo" class="regular-text" value="<?php echo esc_attr( $repo ); ?>" placeholder="your-username/manangatang-energy" /></td>
					</tr>
					<tr>
						<th><label for="me_github_token"><?php esc_html_e( 'Access token', 'manangatang-energy' ); ?></label></th>
						<td>
							<input type="password" id="me_github_token" name="me_github_token" class="regular-text" value="<?php echo esc_attr( $token ); ?>" autocomplete="off" />
							<p class="description"><?php esc_html_e( 'Only needed for a private repository.', 'manangatang-energy' ); ?></p>
						</td>
					</tr>
				</table>
				<p>
					<button type="submit" name="me_save_repo" value="1" class="button button-primary"><?php esc_html_e( 'Save & check now', 'manangatang-energy' ); ?></button>
					<button type="submit" class="button"><?php esc_html_e( 'Check for updates', 'manangatang-energy' ); ?></button>
				</p>
			</form>

			<hr />
			<h2 class="title"><?php esc_html_e( 'How to release a new version', 'manangatang-energy' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Bump "Version:" in style.css (e.g. 1.0.1).', 'manangatang-energy' ); ?></li>
				<li><?php esc_html_e( 'Push to GitHub and create a Release with the tag = that version (1.0.1 or v1.0.1).', 'manangatang-energy' ); ?></li>
				<li><?php esc_html_e( 'Optionally attach a built .zip (folder named manangatang-energy inside). Otherwise the source zip is used.', 'manangatang-energy' ); ?></li>
				<li><?php esc_html_e( 'Click "Check for updates" here, then update from Dashboard → Updates.', 'manangatang-energy' ); ?></li>
			</ol>
		</div>
	</div>
	<?php
}
