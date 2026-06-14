<?php
/**
 * Password-protected maintenance mode.
 *
 * When enabled, front-end visitors see a maintenance screen and cannot view the
 * site until they enter the correct password (remembered via a signed cookie).
 * Logged-in administrators always bypass the gate so they can keep working.
 * Turning the mode off publishes the site normally again.
 *
 * Settings live in their own `me_maintenance` option and are managed from a
 * "Maintenance" page under the Manangatang menu.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ME_MAINT_COOKIE = 'me_maint_unlock';

/**
 * Maintenance settings merged with defaults.
 *
 * @return array
 */
function me_maintenance_settings() {
	$defaults = array(
		'enabled'  => '0',
		'password' => '',
		'heading'  => "We'll be back soon",
		'message'  => 'Our website is undergoing scheduled maintenance. Please check back shortly.',
	);
	$saved = get_option( 'me_maintenance', array() );
	return wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );
}

/**
 * Read one maintenance setting.
 *
 * @param string $key Setting key.
 * @return string
 */
function me_maint( $key ) {
	$all = me_maintenance_settings();
	return isset( $all[ $key ] ) ? $all[ $key ] : '';
}

/**
 * Signed token for the unlock cookie — tied to the current password and the
 * site's secret keys, so it changes (and invalidates) when the password does.
 *
 * @param string $password Current password.
 * @return string
 */
function me_maint_token( $password ) {
	return wp_hash( 'me_maint|' . $password );
}

/**
 * Does the current request already hold a valid unlock cookie?
 *
 * @param string $password Current password.
 * @return bool
 */
function me_maint_is_unlocked( $password ) {
	if ( '' === $password || ! isset( $_COOKIE[ ME_MAINT_COOKIE ] ) ) {
		return false;
	}
	return hash_equals( me_maint_token( $password ), sanitize_text_field( wp_unslash( $_COOKIE[ ME_MAINT_COOKIE ] ) ) );
}

/**
 * Front-end gate. Runs before any template is loaded.
 */
function me_maintenance_gate() {
	if ( '1' !== me_maint( 'enabled' ) ) {
		return;
	}
	// Admins (and anyone who can manage the site) always get through.
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
		return;
	}
	// Never gate the login screen or system endpoints.
	if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}
	$GLOBALS['pagenow'] = isset( $GLOBALS['pagenow'] ) ? $GLOBALS['pagenow'] : '';
	if ( 'wp-login.php' === $GLOBALS['pagenow'] ) {
		return;
	}

	$password = (string) me_maint( 'password' );

	// Already unlocked this browser — let them browse.
	if ( me_maint_is_unlocked( $password ) ) {
		return;
	}

	// Handle a password submission.
	$error = '';
	if ( isset( $_POST['me_maint_pw'] ) && '' !== $password ) {
		$try = sanitize_text_field( wp_unslash( $_POST['me_maint_pw'] ) );
		if ( hash_equals( $password, $try ) ) {
			setcookie( ME_MAINT_COOKIE, me_maint_token( $password ), time() + 7 * DAY_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
			// Reload the same URL (relative REQUEST_URI keeps the subdirectory intact).
			$here = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : home_url( '/' );
			wp_safe_redirect( $here );
			exit;
		}
		$error = __( 'Incorrect password. Please try again.', 'manangatang-energy' );
	}

	me_maintenance_screen( $password, $error );
}
add_action( 'template_redirect', 'me_maintenance_gate' );

/**
 * Output the standalone maintenance screen and stop.
 *
 * @param string $password Current password (controls whether a form is shown).
 * @param string $error    Optional error message.
 */
function me_maintenance_screen( $password, $error = '' ) {
	if ( ! headers_sent() ) {
		status_header( 503 );
		header( 'Retry-After: 3600' );
		nocache_headers();
	}

	$heading = me_maint( 'heading' );
	$message = me_maint( 'message' );
	$logo    = '';
	if ( function_exists( 'get_custom_logo' ) && has_custom_logo() ) {
		$logo = get_custom_logo();
	}

	?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?php echo esc_html( wp_strip_all_tags( $heading ) . ' — ' . get_bloginfo( 'name' ) ); ?></title>
	<style>
		*{box-sizing:border-box}
		body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;
			font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
			color:#1d2327;background:radial-gradient(1200px 600px at 50% -10%,#eafaf0,transparent),linear-gradient(160deg,#0f1a14,#16241b)}
		.card{width:100%;max-width:460px;background:#fff;border-radius:20px;padding:40px 34px;
			box-shadow:0 24px 60px rgba(0,0,0,.35);text-align:center}
		.logo{margin:0 auto 18px;max-width:140px}
		.logo img{max-width:100%;height:auto}
		.badge{display:inline-flex;align-items:center;gap:7px;font-size:11px;font-weight:700;text-transform:uppercase;
			letter-spacing:.12em;color:#13682f;background:#eafaf0;padding:6px 12px;border-radius:999px;margin-bottom:18px}
		.dot{width:8px;height:8px;border-radius:50%;background:#1f8f47;box-shadow:0 0 0 0 rgba(31,143,71,.5);animation:p 1.8s infinite}
		@keyframes p{0%{box-shadow:0 0 0 0 rgba(31,143,71,.5)}70%{box-shadow:0 0 0 10px rgba(31,143,71,0)}100%{box-shadow:0 0 0 0 rgba(31,143,71,0)}}
		h1{margin:0 0 12px;font-size:26px;line-height:1.2;color:#13321f}
		p.msg{margin:0 0 24px;color:#5c6b62;font-size:15px;line-height:1.6}
		form{margin-top:8px}
		.field{display:flex;gap:8px}
		input[type=password]{flex:1;border:1px solid #c3c4c7;border-radius:10px;padding:12px 14px;font-size:15px;outline:none}
		input[type=password]:focus{border-color:#1f8f47;box-shadow:0 0 0 3px #eafaf0}
		button{border:0;border-radius:10px;padding:12px 20px;font-size:15px;font-weight:700;color:#fff;cursor:pointer;
			background:linear-gradient(135deg,#1f8f47,#13682f)}
		button:hover{filter:brightness(1.05)}
		.err{margin:0 0 16px;color:#b32d2e;font-size:13px;font-weight:600}
		.lbl{display:block;text-align:left;font-size:12px;font-weight:600;color:#646970;margin:0 0 6px}
	</style>
</head>
<body>
	<div class="card">
		<?php if ( $logo ) : ?>
			<div class="logo"><?php echo wp_kses_post( $logo ); ?></div>
		<?php endif; ?>
		<span class="badge"><span class="dot"></span> <?php esc_html_e( 'Maintenance', 'manangatang-energy' ); ?></span>
		<h1><?php echo esc_html( $heading ); ?></h1>
		<?php if ( '' !== trim( (string) $message ) ) : ?>
			<p class="msg"><?php echo esc_html( $message ); ?></p>
		<?php endif; ?>

		<?php if ( '' !== $password ) : ?>
			<?php if ( $error ) : ?>
				<p class="err"><?php echo esc_html( $error ); ?></p>
			<?php endif; ?>
			<form method="post" action="">
				<label class="lbl" for="me_maint_pw"><?php esc_html_e( 'Enter password to preview the site', 'manangatang-energy' ); ?></label>
				<div class="field">
					<input type="password" id="me_maint_pw" name="me_maint_pw" autocomplete="current-password" autofocus />
					<button type="submit"><?php esc_html_e( 'Enter', 'manangatang-energy' ); ?></button>
				</div>
			</form>
		<?php endif; ?>
	</div>
</body>
</html>
	<?php
	exit;
}

/**
 * Add the "Maintenance" submenu under the Manangatang menu.
 */
function me_maintenance_admin_menu() {
	add_submenu_page(
		'mec-settings',
		__( 'Maintenance', 'manangatang-energy' ),
		__( 'Maintenance', 'manangatang-energy' ),
		'manage_options',
		'mec-maintenance',
		'me_render_maintenance_page',
		3
	);
}
add_action( 'admin_menu', 'me_maintenance_admin_menu', 20 );

/**
 * Show an admin-bar warning while maintenance mode is live.
 *
 * @param WP_Admin_Bar $bar Admin bar.
 */
function me_maintenance_admin_bar( $bar ) {
	if ( '1' !== me_maint( 'enabled' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$bar->add_node(
		array(
			'id'    => 'me-maintenance',
			'title' => '⚠ ' . __( 'Maintenance mode ON', 'manangatang-energy' ),
			'href'  => admin_url( 'admin.php?page=mec-maintenance' ),
			'meta'  => array( 'title' => __( 'Only you (logged-in admins) and visitors with the password can see the site.', 'manangatang-energy' ) ),
		)
	);
}
add_action( 'admin_bar_menu', 'me_maintenance_admin_bar', 90 );

/**
 * Save handler for the maintenance settings form.
 */
function me_handle_save_maintenance() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Permission denied.', 'manangatang-energy' ) );
	}
	check_admin_referer( 'me_save_maintenance', 'me_maint_nonce' );

	$in      = isset( $_POST['maint'] ) && is_array( $_POST['maint'] ) ? wp_unslash( $_POST['maint'] ) : array();
	$current = me_maintenance_settings();

	// Keep the stored password when the field is left blank.
	$password = isset( $in['password'] ) ? trim( $in['password'] ) : '';
	if ( '' === $password ) {
		$password = $current['password'];
	}

	$clean = array(
		'enabled'  => isset( $in['enabled'] ) ? '1' : '0',
		'password' => $password,
		'heading'  => isset( $in['heading'] ) ? sanitize_text_field( $in['heading'] ) : '',
		'message'  => isset( $in['message'] ) ? sanitize_textarea_field( $in['message'] ) : '',
	);

	update_option( 'me_maintenance', $clean );

	wp_safe_redirect( add_query_arg( array( 'page' => 'mec-maintenance', 'updated' => '1' ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_me_save_maintenance', 'me_handle_save_maintenance' );

/**
 * Render the maintenance settings page.
 */
function me_render_maintenance_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = me_maintenance_settings();
	?>
	<style>
		.me-mt-wrap{max-width:560px}
		.me-mt-card{background:#fff;border:1px solid #e2e4e7;border-radius:10px;box-shadow:0 1px 2px rgba(0,0,0,.04);padding:18px 20px;margin:16px 0}
		.me-mt-field{margin-bottom:16px}
		.me-mt-field label.lbl{display:block;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:#646970;margin-bottom:5px}
		.me-mt-field input[type=text],.me-mt-field input[type=password],.me-mt-field textarea{width:100%;border-radius:6px;border:1px solid #c3c4c7;padding:8px 10px;font-size:13px;box-sizing:border-box}
		.me-mt-field input:focus,.me-mt-field textarea:focus{border-color:#1f8f47;box-shadow:0 0 0 1px #1f8f47;outline:none}
		.me-mt-toggle{display:flex;align-items:center;gap:10px;margin-bottom:6px}
		.me-mt-switch{position:relative;display:inline-block;width:38px;height:22px;flex:none}
		.me-mt-switch input{opacity:0;width:0;height:0}
		.me-mt-track{position:absolute;inset:0;background:#c3c4c7;border-radius:22px;transition:.2s}
		.me-mt-track:before{content:"";position:absolute;height:16px;width:16px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.2s}
		.me-mt-switch input:checked+.me-mt-track{background:#1f8f47}
		.me-mt-switch input:checked+.me-mt-track:before{transform:translateX(16px)}
		.me-mt-hint{font-size:12px;color:#787c82;margin-top:5px}
	</style>
	<div class="wrap me-mt-wrap">
		<h1><?php esc_html_e( 'Maintenance mode', 'manangatang-energy' ); ?></h1>

		<?php if ( isset( $_GET['updated'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Maintenance settings saved.', 'manangatang-energy' ); ?></p></div>
		<?php endif; ?>

		<?php if ( '1' === $s['enabled'] ) : ?>
			<div class="notice notice-warning"><p><strong><?php esc_html_e( 'Maintenance mode is ON.', 'manangatang-energy' ); ?></strong> <?php esc_html_e( 'Visitors without the password see the maintenance screen. You (logged-in admins) can still browse normally.', 'manangatang-energy' ); ?></p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="me_save_maintenance" />
			<?php wp_nonce_field( 'me_save_maintenance', 'me_maint_nonce' ); ?>

			<div class="me-mt-card">
				<div class="me-mt-toggle">
					<label class="me-mt-switch">
						<input type="checkbox" name="maint[enabled]" value="1" <?php checked( '1', $s['enabled'] ); ?> />
						<span class="me-mt-track"></span>
					</label>
					<span><strong><?php esc_html_e( 'Enable maintenance mode', 'manangatang-energy' ); ?></strong></span>
				</div>
				<p class="me-mt-hint"><?php esc_html_e( 'When on, the public site is hidden behind the password below. Turn it off to publish the site again.', 'manangatang-energy' ); ?></p>
			</div>

			<div class="me-mt-card">
				<div class="me-mt-field">
					<label class="lbl" for="me_mt_pw"><?php esc_html_e( 'Preview password', 'manangatang-energy' ); ?></label>
					<input type="password" id="me_mt_pw" name="maint[password]" value="" autocomplete="new-password" placeholder="<?php echo '' !== $s['password'] ? esc_attr__( '••••••••  (leave blank to keep current)', 'manangatang-energy' ) : esc_attr__( 'Set a password', 'manangatang-energy' ); ?>" />
					<p class="me-mt-hint"><?php esc_html_e( 'Anyone with this password can view the site while it is in maintenance. Leave blank when enabling to fully hide the site (admins only).', 'manangatang-energy' ); ?></p>
				</div>
				<div class="me-mt-field">
					<label class="lbl" for="me_mt_heading"><?php esc_html_e( 'Heading', 'manangatang-energy' ); ?></label>
					<input type="text" id="me_mt_heading" name="maint[heading]" value="<?php echo esc_attr( $s['heading'] ); ?>" />
				</div>
				<div class="me-mt-field">
					<label class="lbl" for="me_mt_message"><?php esc_html_e( 'Message', 'manangatang-energy' ); ?></label>
					<textarea id="me_mt_message" name="maint[message]" rows="3"><?php echo esc_textarea( $s['message'] ); ?></textarea>
				</div>
			</div>

			<?php submit_button( __( 'Save maintenance settings', 'manangatang-energy' ) ); ?>
		</form>
	</div>
	<?php
}
