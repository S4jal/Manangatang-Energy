<?php
/**
 * SMTP email delivery.
 *
 * Routes all wp_mail() through an authenticated SMTP server (Microsoft 365 by
 * default) so contact-form and system emails actually get delivered instead of
 * relying on the host's php mail(). Configured from a dedicated "Email (SMTP)"
 * page under the Manangatang menu and stored in its own `me_smtp_settings`
 * option, kept separate from page content.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SMTP settings, merged with sensible Microsoft 365 defaults.
 *
 * @return array
 */
function me_smtp_settings() {
	$defaults = array(
		'enabled'    => '0',
		'host'       => 'smtp.office365.com',
		'port'       => '587',
		'encryption' => 'tls', // none | ssl | tls (STARTTLS).
		'auth'       => '1',
		'username'   => '',
		'password'   => '',
		'from_email' => '',
		'from_name'  => get_bloginfo( 'name' ),
		'force_from' => '1',
	);
	$saved = get_option( 'me_smtp_settings', array() );
	return wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );
}

/**
 * Read one SMTP setting.
 *
 * @param string $key Setting key.
 * @return string
 */
function me_smtp( $key ) {
	$all = me_smtp_settings();
	return isset( $all[ $key ] ) ? $all[ $key ] : '';
}

/**
 * Configure PHPMailer to send through the SMTP server.
 *
 * @param PHPMailer\PHPMailer\PHPMailer $phpmailer PHPMailer instance (by ref).
 */
function me_smtp_phpmailer_init( $phpmailer ) {
	if ( '1' !== me_smtp( 'enabled' ) || '' === me_smtp( 'host' ) ) {
		return;
	}

	$phpmailer->isSMTP();
	$phpmailer->Host = me_smtp( 'host' );
	$phpmailer->Port = (int) me_smtp( 'port' );

	$enc = me_smtp( 'encryption' );
	if ( 'ssl' === $enc || 'tls' === $enc ) {
		$phpmailer->SMTPSecure = $enc; // 'ssl' or 'tls' (STARTTLS).
	} else {
		$phpmailer->SMTPSecure  = '';
		$phpmailer->SMTPAutoTLS = false;
	}

	if ( '1' === me_smtp( 'auth' ) ) {
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = me_smtp( 'username' );
		$phpmailer->Password = me_smtp( 'password' );
	} else {
		$phpmailer->SMTPAuth = false;
	}

	// Force a consistent From — Microsoft 365 rejects mismatched senders.
	$from_email = me_smtp( 'from_email' );
	if ( '' === $from_email && '1' === me_smtp( 'auth' ) ) {
		$from_email = me_smtp( 'username' );
	}
	if ( '' !== $from_email && ( '1' === me_smtp( 'force_from' ) || ! $phpmailer->From ) ) {
		$phpmailer->From = $from_email;
		if ( '' !== me_smtp( 'from_name' ) ) {
			$phpmailer->FromName = me_smtp( 'from_name' );
		}
	}
}
add_action( 'phpmailer_init', 'me_smtp_phpmailer_init' );

/**
 * Add the "Email (SMTP)" submenu under the Manangatang menu.
 */
function me_smtp_admin_menu() {
	add_submenu_page(
		'mec-settings',
		__( 'Email (SMTP)', 'manangatang-energy' ),
		__( 'Email (SMTP)', 'manangatang-energy' ),
		'manage_options',
		'mec-smtp',
		'me_render_smtp_page',
		2
	);
}
add_action( 'admin_menu', 'me_smtp_admin_menu', 20 );

/**
 * Save handler for the SMTP settings form.
 */
function me_handle_save_smtp() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Permission denied.', 'manangatang-energy' ) );
	}
	check_admin_referer( 'me_save_smtp', 'me_smtp_nonce' );

	$in      = isset( $_POST['smtp'] ) && is_array( $_POST['smtp'] ) ? wp_unslash( $_POST['smtp'] ) : array();
	$current = me_smtp_settings();

	$enc = isset( $in['encryption'] ) ? sanitize_key( $in['encryption'] ) : 'tls';
	if ( ! in_array( $enc, array( 'none', 'ssl', 'tls' ), true ) ) {
		$enc = 'tls';
	}

	// Keep the stored password if the field is left blank (so it isn't shown/retyped).
	$password = isset( $in['password'] ) ? trim( $in['password'] ) : '';
	if ( '' === $password ) {
		$password = $current['password'];
	}

	$clean = array(
		'enabled'    => isset( $in['enabled'] ) ? '1' : '0',
		'host'       => isset( $in['host'] ) ? sanitize_text_field( $in['host'] ) : '',
		'port'       => isset( $in['port'] ) ? (string) absint( $in['port'] ) : '587',
		'encryption' => $enc,
		'auth'       => isset( $in['auth'] ) ? '1' : '0',
		'username'   => isset( $in['username'] ) ? sanitize_text_field( $in['username'] ) : '',
		'password'   => $password,
		'from_email' => isset( $in['from_email'] ) ? sanitize_email( $in['from_email'] ) : '',
		'from_name'  => isset( $in['from_name'] ) ? sanitize_text_field( $in['from_name'] ) : '',
		'force_from' => isset( $in['force_from'] ) ? '1' : '0',
	);

	update_option( 'me_smtp_settings', $clean );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'mec-smtp', 'updated' => '1' ),
		admin_url( 'admin.php' )
	) );
	exit;
}
add_action( 'admin_post_me_save_smtp', 'me_handle_save_smtp' );

/**
 * Send a test email and redirect back with the result.
 */
function me_handle_smtp_test() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Permission denied.', 'manangatang-energy' ) );
	}
	check_admin_referer( 'me_smtp_test', 'me_smtp_test_nonce' );

	$to = isset( $_POST['test_to'] ) ? sanitize_email( wp_unslash( $_POST['test_to'] ) ) : '';
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	// Capture any PHPMailer failure so we can show it.
	$error = '';
	$grab  = function ( $wp_error ) use ( &$error ) {
		$error = $wp_error->get_error_message();
	};
	add_action( 'wp_mail_failed', $grab );

	$subject = sprintf(
		/* translators: %s: site name. */
		__( '[%s] SMTP test email', 'manangatang-energy' ),
		get_bloginfo( 'name' )
	);
	$body = __( 'This is a test email sent from your website to confirm SMTP delivery is working.', 'manangatang-energy' );
	$sent = wp_mail( $to, $subject, $body );

	remove_action( 'wp_mail_failed', $grab );

	$args = array( 'page' => 'mec-smtp' );
	if ( $sent ) {
		$args['tested'] = 'ok';
		$args['to']     = rawurlencode( $to );
	} else {
		$args['tested'] = 'fail';
		set_transient( 'me_smtp_test_error', $error, 60 );
	}

	wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_me_smtp_test', 'me_handle_smtp_test' );

/**
 * Render the SMTP settings page.
 */
function me_render_smtp_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = me_smtp_settings();
	?>
	<style>
		.me-smtp-wrap{max-width:640px}
		.me-smtp-card{background:#fff;border:1px solid #e2e4e7;border-radius:10px;box-shadow:0 1px 2px rgba(0,0,0,.04);padding:18px 20px;margin:16px 0}
		.me-smtp-card h2{margin:0 0 4px;font-size:15px}
		.me-smtp-card p.desc{margin:0 0 14px;color:#646970;font-size:13px}
		.me-smtp-row{display:flex;gap:16px;flex-wrap:wrap;margin-bottom:14px}
		.me-smtp-field{flex:1;min-width:180px}
		.me-smtp-field.full{flex-basis:100%}
		.me-smtp-field label{display:block;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:#646970;margin-bottom:5px}
		.me-smtp-field input[type=text],.me-smtp-field input[type=email],.me-smtp-field input[type=password],.me-smtp-field select{width:100%;border-radius:6px;border:1px solid #c3c4c7;padding:7px 10px;font-size:13px;box-sizing:border-box}
		.me-smtp-field input:focus,.me-smtp-field select:focus{border-color:#1f8f47;box-shadow:0 0 0 1px #1f8f47;outline:none}
		.me-smtp-toggle{display:flex;align-items:center;gap:10px;margin-bottom:14px}
		.me-smtp-switch{position:relative;display:inline-block;width:38px;height:22px;flex:none}
		.me-smtp-switch input{opacity:0;width:0;height:0}
		.me-smtp-track{position:absolute;inset:0;background:#c3c4c7;border-radius:22px;transition:.2s}
		.me-smtp-track:before{content:"";position:absolute;height:16px;width:16px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.2s}
		.me-smtp-switch input:checked+.me-smtp-track{background:#1f8f47}
		.me-smtp-switch input:checked+.me-smtp-track:before{transform:translateX(16px)}
		.me-smtp-hint{font-size:12px;color:#787c82;margin-top:4px}
	</style>
	<div class="wrap me-smtp-wrap">
		<h1><?php esc_html_e( 'Email (SMTP)', 'manangatang-energy' ); ?></h1>

		<?php if ( isset( $_GET['updated'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'SMTP settings saved.', 'manangatang-energy' ); ?></p></div>
		<?php endif; ?>

		<?php if ( isset( $_GET['tested'] ) && 'ok' === $_GET['tested'] ) : ?>
			<div class="notice notice-success is-dismissible"><p>
				<?php
				printf(
					/* translators: %s: recipient email. */
					esc_html__( 'Test email sent successfully to %s.', 'manangatang-energy' ),
					'<strong>' . esc_html( isset( $_GET['to'] ) ? rawurldecode( wp_unslash( $_GET['to'] ) ) : '' ) . '</strong>'
				);
				?>
			</p></div>
		<?php elseif ( isset( $_GET['tested'] ) && 'fail' === $_GET['tested'] ) : ?>
			<div class="notice notice-error is-dismissible"><p>
				<?php esc_html_e( 'Test email failed.', 'manangatang-energy' ); ?>
				<?php
				$err = get_transient( 'me_smtp_test_error' );
				delete_transient( 'me_smtp_test_error' );
				if ( $err ) {
					echo ' <code>' . esc_html( $err ) . '</code>';
				}
				?>
			</p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="me_save_smtp" />
			<?php wp_nonce_field( 'me_save_smtp', 'me_smtp_nonce' ); ?>

			<div class="me-smtp-card">
				<h2><?php esc_html_e( 'Delivery', 'manangatang-energy' ); ?></h2>
				<p class="desc"><?php esc_html_e( 'When enabled, all site email is sent through the SMTP server below instead of the server default.', 'manangatang-energy' ); ?></p>
				<div class="me-smtp-toggle">
					<label class="me-smtp-switch">
						<input type="checkbox" name="smtp[enabled]" value="1" <?php checked( '1', $s['enabled'] ); ?> />
						<span class="me-smtp-track"></span>
					</label>
					<span><?php esc_html_e( 'Send email via SMTP', 'manangatang-energy' ); ?></span>
				</div>

				<div class="me-smtp-row">
					<div class="me-smtp-field" style="flex:2">
						<label for="smtp_host"><?php esc_html_e( 'SMTP host', 'manangatang-energy' ); ?></label>
						<input type="text" id="smtp_host" name="smtp[host]" value="<?php echo esc_attr( $s['host'] ); ?>" placeholder="smtp.office365.com" />
					</div>
					<div class="me-smtp-field" style="flex:0 0 110px">
						<label for="smtp_port"><?php esc_html_e( 'Port', 'manangatang-energy' ); ?></label>
						<input type="text" id="smtp_port" name="smtp[port]" value="<?php echo esc_attr( $s['port'] ); ?>" placeholder="587" />
					</div>
					<div class="me-smtp-field" style="flex:0 0 150px">
						<label for="smtp_enc"><?php esc_html_e( 'Encryption', 'manangatang-energy' ); ?></label>
						<select id="smtp_enc" name="smtp[encryption]">
							<option value="tls" <?php selected( 'tls', $s['encryption'] ); ?>><?php esc_html_e( 'TLS (STARTTLS)', 'manangatang-energy' ); ?></option>
							<option value="ssl" <?php selected( 'ssl', $s['encryption'] ); ?>><?php esc_html_e( 'SSL', 'manangatang-energy' ); ?></option>
							<option value="none" <?php selected( 'none', $s['encryption'] ); ?>><?php esc_html_e( 'None', 'manangatang-energy' ); ?></option>
						</select>
					</div>
				</div>
				<p class="me-smtp-hint"><?php esc_html_e( 'Microsoft 365 / Outlook: host smtp.office365.com, port 587, TLS (STARTTLS).', 'manangatang-energy' ); ?></p>
			</div>

			<div class="me-smtp-card">
				<h2><?php esc_html_e( 'Authentication', 'manangatang-energy' ); ?></h2>
				<div class="me-smtp-toggle">
					<label class="me-smtp-switch">
						<input type="checkbox" name="smtp[auth]" value="1" <?php checked( '1', $s['auth'] ); ?> />
						<span class="me-smtp-track"></span>
					</label>
					<span><?php esc_html_e( 'Use SMTP authentication (username & password)', 'manangatang-energy' ); ?></span>
				</div>
				<div class="me-smtp-row">
					<div class="me-smtp-field">
						<label for="smtp_user"><?php esc_html_e( 'Username', 'manangatang-energy' ); ?></label>
						<input type="text" id="smtp_user" name="smtp[username]" value="<?php echo esc_attr( $s['username'] ); ?>" autocomplete="off" placeholder="you@yourdomain.com.au" />
					</div>
					<div class="me-smtp-field">
						<label for="smtp_pass"><?php esc_html_e( 'Password', 'manangatang-energy' ); ?></label>
						<input type="password" id="smtp_pass" name="smtp[password]" value="" autocomplete="new-password" placeholder="<?php echo '' !== $s['password'] ? esc_attr__( '••••••••  (leave blank to keep)', 'manangatang-energy' ) : ''; ?>" />
						<p class="me-smtp-hint"><?php esc_html_e( 'Microsoft 365 with MFA needs an App Password, not your normal password.', 'manangatang-energy' ); ?></p>
					</div>
				</div>
			</div>

			<div class="me-smtp-card">
				<h2><?php esc_html_e( 'Sender', 'manangatang-energy' ); ?></h2>
				<div class="me-smtp-row">
					<div class="me-smtp-field">
						<label for="smtp_from_email"><?php esc_html_e( 'From email', 'manangatang-energy' ); ?></label>
						<input type="email" id="smtp_from_email" name="smtp[from_email]" value="<?php echo esc_attr( $s['from_email'] ); ?>" placeholder="you@yourdomain.com.au" />
						<p class="me-smtp-hint"><?php esc_html_e( 'Should match the authenticated mailbox. Blank = use the username.', 'manangatang-energy' ); ?></p>
					</div>
					<div class="me-smtp-field">
						<label for="smtp_from_name"><?php esc_html_e( 'From name', 'manangatang-energy' ); ?></label>
						<input type="text" id="smtp_from_name" name="smtp[from_name]" value="<?php echo esc_attr( $s['from_name'] ); ?>" />
					</div>
				</div>
				<div class="me-smtp-toggle">
					<label class="me-smtp-switch">
						<input type="checkbox" name="smtp[force_from]" value="1" <?php checked( '1', $s['force_from'] ); ?> />
						<span class="me-smtp-track"></span>
					</label>
					<span><?php esc_html_e( 'Force this From on all outgoing email (recommended for Microsoft 365)', 'manangatang-energy' ); ?></span>
				</div>
			</div>

			<?php submit_button( __( 'Save SMTP settings', 'manangatang-energy' ) ); ?>
		</form>

		<div class="me-smtp-card">
			<h2><?php esc_html_e( 'Send a test email', 'manangatang-energy' ); ?></h2>
			<p class="desc"><?php esc_html_e( 'Save your settings first, then send a test to confirm delivery.', 'manangatang-energy' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="me_smtp_test" />
				<?php wp_nonce_field( 'me_smtp_test', 'me_smtp_test_nonce' ); ?>
				<div class="me-smtp-row" style="align-items:flex-end;margin-bottom:0">
					<div class="me-smtp-field">
						<label for="smtp_test_to"><?php esc_html_e( 'Send to', 'manangatang-energy' ); ?></label>
						<input type="email" id="smtp_test_to" name="test_to" value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" />
					</div>
					<div class="me-smtp-field" style="flex:0 0 auto">
						<?php submit_button( __( 'Send test', 'manangatang-energy' ), 'secondary', 'submit', false ); ?>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php
}
