<?php
/**
 * Customizer integration (Appearance → Customize).
 *
 * All controls bind to the shared `mec_settings` option, so they stay
 * in sync with the plugin's Manangatang → Settings page. Adds:
 *   - Contact details, Hero stats, Footer, Forms (mirrors of the settings page)
 *   - Per-page hero text editing and section show/hide toggles
 *     (the "add/remove content, page-wise" controls).
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Boolean sanitizer: store toggles as '1' (show) or '0' (hide).
 *
 * @param mixed $value Raw value.
 * @return string
 */
function me_cz_bool( $value ) {
	return ( true === $value || '1' === $value || 1 === $value || 'on' === $value ) ? '1' : '0';
}

/**
 * Register a text/textarea control bound to mec_settings[$id].
 *
 * @param WP_Customize_Manager $wp      Manager.
 * @param string               $section Section id.
 * @param string               $id      Setting key.
 * @param string               $label   Control label.
 * @param string               $default Default value.
 * @param string               $type    'text' or 'textarea'.
 */
function me_cz_text( $wp, $section, $id, $label, $default, $type = 'text' ) {
	$wp->add_setting(
		"mec_settings[{$id}]",
		array(
			'type'              => 'option',
			'default'           => $default,
			'sanitize_callback' => ( 'textarea' === $type ) ? 'sanitize_textarea_field' : 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		"mec_ctrl_{$id}",
		array(
			'label'    => $label,
			'section'  => $section,
			'settings' => "mec_settings[{$id}]",
			'type'     => $type,
		)
	);
}

/**
 * Register a show/hide checkbox bound to mec_settings[$id].
 *
 * @param WP_Customize_Manager $wp      Manager.
 * @param string               $section Section id.
 * @param string               $id      Setting key.
 * @param string               $label   Control label.
 */
function me_cz_toggle( $wp, $section, $id, $label ) {
	$wp->add_setting(
		"mec_settings[{$id}]",
		array(
			'type'              => 'option',
			'default'           => '1',
			'sanitize_callback' => 'me_cz_bool',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		"mec_ctrl_{$id}",
		array(
			'label'    => $label,
			'section'  => $section,
			'settings' => "mec_settings[{$id}]",
			'type'     => 'checkbox',
		)
	);
}

/**
 * Build the Customizer panel.
 *
 * @param WP_Customize_Manager $wp Manager.
 */
function me_customize_register( $wp ) {

	require_once get_template_directory() . '/inc/class-me-repeater-control.php';

	$wp->add_panel(
		'me_pages',
		array(
			'title'       => __( 'Pages', 'manangatang-energy' ),
			'description' => __( 'Edit each page — hero text and which sections show. Pick a page below.', 'manangatang-energy' ),
			'priority'    => 20,
		)
	);

	/* ---------- Header (top-level section) ---------- */
	$wp->add_section(
		'me_sec_header',
		array(
			'title'       => __( 'Header', 'manangatang-energy' ),
			'description' => __( 'The top navigation bar. Menu items are managed under Menus.', 'manangatang-energy' ),
			'priority'    => 21,
		)
	);
	$wp->add_setting(
		'mec_settings[header_logo]',
		array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		new WP_Customize_Image_Control(
			$wp,
			'mec_ctrl_header_logo',
			array(
				'label'       => __( 'Header logo', 'manangatang-energy' ),
				'description' => __( 'Defaults to the site logo mark if left empty.', 'manangatang-energy' ),
				'section'     => 'me_sec_header',
				'settings'    => 'mec_settings[header_logo]',
			)
		)
	);
	me_cz_toggle( $wp, 'me_sec_header', 'header_show_phone', __( 'Show header button', 'manangatang-energy' ) );

	$wp->add_setting(
		'mec_settings[header_btn_icon]',
		array(
			'type'              => 'option',
			'default'           => 'phone',
			'sanitize_callback' => 'me_sanitize_icon',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		new ME_Icon_Control(
			$wp,
			'mec_ctrl_header_btn_icon',
			array(
				'label'    => __( 'Button icon', 'manangatang-energy' ),
				'section'  => 'me_sec_header',
				'settings' => 'mec_settings[header_btn_icon]',
				'choices'  => me_icon_choices(),
			)
		)
	);
	me_cz_text( $wp, 'me_sec_header', 'header_btn_text', __( 'Button text', 'manangatang-energy' ), me_opt( 'phone' ) );
	me_cz_text( $wp, 'me_sec_header', 'header_btn_link', __( 'Button link', 'manangatang-energy' ), 'tel:' . me_tel() );

	/* ---------- Preloader (top-level section) ---------- */
	$wp->add_section(
		'me_sec_preloader',
		array(
			'title'       => __( 'Preloader', 'manangatang-energy' ),
			'description' => __( 'The loading screen shown before the page appears.', 'manangatang-energy' ),
			'priority'    => 22,
		)
	);

	$wp->add_setting(
		'mec_settings[preloader_enable]',
		array(
			'type'              => 'option',
			'default'           => '1',
			'sanitize_callback' => 'me_cz_bool',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		'mec_ctrl_preloader_enable',
		array(
			'label'    => __( 'Enable preloader', 'manangatang-energy' ),
			'section'  => 'me_sec_preloader',
			'settings' => 'mec_settings[preloader_enable]',
			'type'     => 'checkbox',
		)
	);

	$wp->add_setting(
		'mec_settings[preloader_duration]',
		array(
			'type'              => 'option',
			'default'           => 4000,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		'mec_ctrl_preloader_duration',
		array(
			'label'       => __( 'Max display time (milliseconds)', 'manangatang-energy' ),
			'description' => __( 'The preloader hides on page load, or after this time at the latest. 1000 = 1 second.', 'manangatang-energy' ),
			'section'     => 'me_sec_preloader',
			'settings'    => 'mec_settings[preloader_duration]',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 500, 'max' => 10000, 'step' => 100 ),
		)
	);

	$wp->add_setting(
		'mec_settings[preloader_logo]',
		array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		new WP_Customize_Image_Control(
			$wp,
			'mec_ctrl_preloader_logo',
			array(
				'label'       => __( 'Preloader logo', 'manangatang-energy' ),
				'description' => __( 'Defaults to the site logo mark if left empty.', 'manangatang-energy' ),
				'section'     => 'me_sec_preloader',
				'settings'    => 'mec_settings[preloader_logo]',
			)
		)
	);

	/* ---------- Cookie consent (top-level section) ---------- */
	$wp->add_section(
		'me_sec_cookie',
		array(
			'title'       => __( 'Cookie Consent', 'manangatang-energy' ),
			'description' => __( 'The cookie notice shown at the bottom of the page.', 'manangatang-energy' ),
			'priority'    => 23,
		)
	);
	$wp->add_setting(
		'mec_settings[cookie_enable]',
		array(
			'type'              => 'option',
			'default'           => '1',
			'sanitize_callback' => 'me_cz_bool',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		'mec_ctrl_cookie_enable',
		array(
			'label'    => __( 'Enable cookie notice', 'manangatang-energy' ),
			'section'  => 'me_sec_cookie',
			'settings' => 'mec_settings[cookie_enable]',
			'type'     => 'checkbox',
		)
	);
	me_cz_text( $wp, 'me_sec_cookie', 'cookie_title', __( 'Heading', 'manangatang-energy' ), 'We use cookies' );
	me_cz_text( $wp, 'me_sec_cookie', 'cookie_text', __( 'Message', 'manangatang-energy' ), 'We use cookies to improve your experience, analyse traffic, and personalise content.', 'textarea' );
	me_cz_text( $wp, 'me_sec_cookie', 'cookie_accept', __( 'Accept button label', 'manangatang-energy' ), 'Accept all' );
	me_cz_text( $wp, 'me_sec_cookie', 'cookie_decline', __( 'Decline button label', 'manangatang-energy' ), 'Decline' );

	/* ---------- Contact details (top-level, site-wide) ---------- */
	$wp->add_section(
		'me_sec_contact',
		array(
			'title'       => __( 'Contact details', 'manangatang-energy' ),
			'description' => __( 'Phone, email and address used across the site (header, footer, contact page).', 'manangatang-energy' ),
			'priority'    => 25,
		)
	);
	me_cz_text( $wp, 'me_sec_contact', 'phone', __( 'Phone (display)', 'manangatang-energy' ), '0427 739 716' );
	me_cz_text( $wp, 'me_sec_contact', 'email', __( 'Email', 'manangatang-energy' ), 'info@manangatangenergy.com.au' );
	me_cz_text( $wp, 'me_sec_contact', 'address', __( 'Address', 'manangatang-energy' ), 'Manangatang, Victoria 3546' );

	/* ---------- Footer (top-level section) ---------- */
	$wp->add_section(
		'me_sec_footer',
		array(
			'title'       => __( 'Footer', 'manangatang-energy' ),
			'description' => __( 'The site footer content.', 'manangatang-energy' ),
			'priority'    => 24,
		)
	);
	me_cz_text( $wp, 'me_sec_footer', 'footer_blurb', __( 'Footer blurb', 'manangatang-energy' ), 'A proposed 300 MW / 600 MWh Battery Energy Storage System in north-west Victoria — strengthening grid reliability as Australia transitions to renewable energy.', 'textarea' );
	me_cz_text( $wp, 'me_sec_footer', 'copyright', __( 'Copyright line', 'manangatang-energy' ), '© 2026 Manangatang Energy Pty Ltd. All rights reserved.' );
	me_cz_text( $wp, 'me_sec_footer', 'acknowledgement', __( 'Acknowledgement of Country', 'manangatang-energy' ), 'Acknowledging the Tati Tati people, Traditional Owners of this Country.' );

	// Repeater: footer contact items (right column).
	$wp->add_setting(
		'mec_settings[footer_contacts]',
		array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'me_sanitize_contacts',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		new ME_Repeater_Control(
			$wp,
			'mec_ctrl_footer_contacts',
			array(
				'label'       => __( 'Contact items', 'manangatang-energy' ),
				'description' => __( 'Add as many rows as you like (icon + text + optional link). Leave empty to use the email / phone / address from Contact details.', 'manangatang-energy' ),
				'section'     => 'me_sec_footer',
				'settings'    => 'mec_settings[footer_contacts]',
			)
		)
	);

	/* ===================== PAGES ===================== */

	/* ---------- Home ---------- */
	$wp->add_section( 'me_sec_home', array( 'title' => __( 'Home', 'manangatang-energy' ), 'panel' => 'me_pages', 'priority' => 10 ) );
	$me_ov = get_page_by_path( 'project-overview' );
	$me_cm = get_page_by_path( 'community' );
	$wp->add_setting( 'mec_settings[home_hero_bg]', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'esc_url_raw', 'transport' => 'refresh' ) );
	$wp->add_control(
		new WP_Customize_Image_Control(
			$wp,
			'mec_ctrl_home_hero_bg',
			array(
				'label'       => __( 'Hero background image', 'manangatang-energy' ),
				'description' => __( 'Defaults to the supplied aerial image if empty.', 'manangatang-energy' ),
				'section'     => 'me_sec_home',
				'settings'    => 'mec_settings[home_hero_bg]',
			)
		)
	);
	me_cz_text( $wp, 'me_sec_home', 'home_hero_heading', __( 'Hero heading', 'manangatang-energy' ), "Manangatang\nEnergy", 'textarea' );
	me_cz_text( $wp, 'me_sec_home', 'home_hero_tagline', __( 'Hero tagline', 'manangatang-energy' ), "Reliable Energy Infrastructure for Victoria's Future", 'textarea' );
	me_cz_text( $wp, 'me_sec_home', 'home_hero_intro', __( 'Hero intro paragraph', 'manangatang-energy' ), "Located near Manangatang in Victoria's Mallee region, the project is exploring utility-scale battery energy storage to support grid reliability and renewable energy integration.", 'textarea' );
	me_cz_text( $wp, 'me_sec_home', 'home_btn1_text', __( 'Button 1 — text', 'manangatang-energy' ), 'Project Overview' );
	me_cz_text( $wp, 'me_sec_home', 'home_btn1_link', __( 'Button 1 — link', 'manangatang-energy' ), $me_ov ? get_permalink( $me_ov ) : '' );
	me_cz_text( $wp, 'me_sec_home', 'home_btn2_text', __( 'Button 2 — text', 'manangatang-energy' ), 'Community Information' );
	me_cz_text( $wp, 'me_sec_home', 'home_btn2_link', __( 'Button 2 — link', 'manangatang-energy' ), $me_cm ? get_permalink( $me_cm ) : '' );
	// Hero statistics (homepage).
	$stat_defaults = array(
		array( '300', 'MW', 'Power capacity' ),
		array( '600', 'MWh', 'Storage' ),
		array( '220', 'kV', 'Transmission line' ),
	);
	foreach ( $stat_defaults as $n => $d ) {
		$i = $n + 1;
		me_cz_text( $wp, 'me_sec_home', "stat{$i}_num", sprintf( __( 'Stat %d — number', 'manangatang-energy' ), $i ), $d[0] );
		me_cz_text( $wp, 'me_sec_home', "stat{$i}_unit", sprintf( __( 'Stat %d — unit', 'manangatang-energy' ), $i ), $d[1] );
		me_cz_text( $wp, 'me_sec_home', "stat{$i}_label", sprintf( __( 'Stat %d — label', 'manangatang-energy' ), $i ), $d[2] );
	}
	me_cz_text( $wp, 'me_sec_home', 'home_marquee', __( 'Marquee ticker (one item per line)', 'manangatang-energy' ), "300 MW Power\n600 MWh Storage\n220 kV Grid Connection\nLFP Chemistry\n~12 Hectares\n25+ Year Design Life\nOperational Q4 2027", 'textarea' );
	me_cz_toggle( $wp, 'me_sec_home', 'home_show_features', __( 'Show “Why this site” cards', 'manangatang-energy' ) );
	me_cz_text( $wp, 'me_sec_home', 'home_features_eyebrow', __( '“Why this site” — subtitle', 'manangatang-energy' ), 'Why this site' );
	me_cz_text( $wp, 'me_sec_home', 'home_features_heading', __( '“Why this site” — title', 'manangatang-energy' ), 'Built on the right foundations' );
	me_cz_toggle( $wp, 'me_sec_home', 'home_show_site', __( 'Show “The Site” section', 'manangatang-energy' ) );
	me_cz_toggle( $wp, 'me_sec_home', 'home_show_battery', __( 'Show “Battery Storage Explained”', 'manangatang-energy' ) );
	me_cz_toggle( $wp, 'me_sec_home', 'home_show_community', __( 'Show “Community Matters” band', 'manangatang-energy' ) );
	me_cz_toggle( $wp, 'me_sec_home', 'home_show_contact', __( 'Show location / contact band', 'manangatang-energy' ) );
	me_cz_text( $wp, 'me_sec_home', 'cf7_home', __( 'Contact Form 7 — form ID', 'manangatang-energy' ), '' );

	/* ---------- About the Project ---------- */
	$wp->add_section( 'me_sec_about', array( 'title' => __( 'About the Project', 'manangatang-energy' ), 'panel' => 'me_pages', 'priority' => 20 ) );
	me_cz_text( $wp, 'me_sec_about', 'about_hero_eyebrow', __( 'Hero eyebrow', 'manangatang-energy' ), 'The Project' );
	me_cz_text( $wp, 'me_sec_about', 'about_hero_heading', __( 'Hero heading', 'manangatang-energy' ), 'A 300 MW / 600 MWh battery for north-west Victoria.', 'textarea' );
	me_cz_text( $wp, 'me_sec_about', 'about_hero_subtitle', __( 'Hero subtitle', 'manangatang-energy' ), 'The Manangatang Battery Energy Storage System (BESS) will store renewable energy generated across the state and release it during peak demand — supporting reliability, lowering prices, and accelerating the retirement of coal-fired generation.', 'textarea' );
	me_cz_toggle( $wp, 'me_sec_about', 'about_show_specs', __( 'Show overview + specs', 'manangatang-energy' ) );
	me_cz_toggle( $wp, 'me_sec_about', 'about_show_safety', __( 'Show “Designed for safety”', 'manangatang-energy' ) );
	me_cz_toggle( $wp, 'me_sec_about', 'about_show_timeline', __( 'Show project timeline', 'manangatang-energy' ) );

	/* ---------- The Site ---------- */
	$wp->add_section( 'me_sec_site', array( 'title' => __( 'The Site', 'manangatang-energy' ), 'panel' => 'me_pages', 'priority' => 30 ) );
	me_cz_text( $wp, 'me_sec_site', 'site_hero_eyebrow', __( 'Hero eyebrow', 'manangatang-energy' ), 'Where it will be built' );
	me_cz_text( $wp, 'me_sec_site', 'site_hero_heading', __( 'Hero heading', 'manangatang-energy' ), 'A purpose-chosen site next to existing transmission.', 'textarea' );
	me_cz_text( $wp, 'me_sec_site', 'site_hero_subtitle', __( 'Hero subtitle', 'manangatang-energy' ), 'The Manangatang site was selected after detailed grid, environmental, and land-use studies — minimising new infrastructure, avoiding sensitive vegetation, and respecting neighbouring farming activity.', 'textarea' );
	me_cz_toggle( $wp, 'me_sec_site', 'site_show_glance', __( 'Show “Site at a glance”', 'manangatang-energy' ) );
	me_cz_toggle( $wp, 'me_sec_site', 'site_show_band', __( 'Show full-bleed image band', 'manangatang-energy' ) );
	me_cz_toggle( $wp, 'me_sec_site', 'site_show_mapping', __( 'Show reference mapping', 'manangatang-energy' ) );

	/* ---------- Community ---------- */
	$wp->add_section( 'me_sec_comm', array( 'title' => __( 'Community', 'manangatang-energy' ), 'panel' => 'me_pages', 'priority' => 40 ) );
	me_cz_text( $wp, 'me_sec_comm', 'comm_hero_eyebrow', __( 'Hero eyebrow', 'manangatang-energy' ), 'Have your say' );
	me_cz_text( $wp, 'me_sec_comm', 'comm_hero_heading', __( 'Hero heading', 'manangatang-energy' ), 'Community consultation is open.', 'textarea' );
	me_cz_text( $wp, 'me_sec_comm', 'comm_hero_subtitle', __( 'Hero subtitle', 'manangatang-energy' ), "We're committed to genuine, two-way engagement with the people who live and work near the proposed Manangatang BESS. Here's how to get involved.", 'textarea' );
	me_cz_toggle( $wp, 'me_sec_comm', 'comm_show_sessions', __( 'Show upcoming sessions', 'manangatang-energy' ) );
	me_cz_toggle( $wp, 'me_sec_comm', 'comm_show_image', __( 'Show community image band', 'manangatang-energy' ) );
	me_cz_toggle( $wp, 'me_sec_comm', 'comm_show_submission', __( 'Show submission + engagement', 'manangatang-energy' ) );
	me_cz_text( $wp, 'me_sec_comm', 'cf7_feedback', __( 'Contact Form 7 — feedback form ID', 'manangatang-energy' ), '' );

	/* ---------- Documents ---------- */
	$wp->add_section( 'me_sec_docs', array( 'title' => __( 'Documents', 'manangatang-energy' ), 'panel' => 'me_pages', 'priority' => 50 ) );
	me_cz_text( $wp, 'me_sec_docs', 'docs_hero_eyebrow', __( 'Hero eyebrow', 'manangatang-energy' ), 'Documents' );
	me_cz_text( $wp, 'me_sec_docs', 'docs_hero_heading', __( 'Hero heading', 'manangatang-energy' ), 'Project documents and reports.', 'textarea' );
	me_cz_text( $wp, 'me_sec_docs', 'docs_hero_subtitle', __( 'Hero subtitle', 'manangatang-energy' ), 'Technical reports, fact sheets and consultation materials are published here as they become available. All documents are also available in hard copy on request.', 'textarea' );

	/* ---------- FAQs ---------- */
	$wp->add_section( 'me_sec_faqs', array( 'title' => __( 'FAQs', 'manangatang-energy' ), 'panel' => 'me_pages', 'priority' => 60 ) );
	me_cz_text( $wp, 'me_sec_faqs', 'faqs_hero_eyebrow', __( 'Hero eyebrow', 'manangatang-energy' ), 'Knowledge base' );
	me_cz_text( $wp, 'me_sec_faqs', 'faqs_hero_heading', __( 'Hero heading', 'manangatang-energy' ), 'Frequently asked questions.', 'textarea' );
	me_cz_text( $wp, 'me_sec_faqs', 'faqs_hero_subtitle', __( 'Hero subtitle', 'manangatang-energy' ), 'Answers to the questions we hear most often from neighbours, councils and community groups.', 'textarea' );

	/* ---------- Contact ---------- */
	$wp->add_section( 'me_sec_contactpage', array( 'title' => __( 'Contact', 'manangatang-energy' ), 'panel' => 'me_pages', 'priority' => 70 ) );
	me_cz_text( $wp, 'me_sec_contactpage', 'contact_hero_eyebrow', __( 'Hero eyebrow', 'manangatang-energy' ), 'Contact' );
	me_cz_text( $wp, 'me_sec_contactpage', 'contact_hero_heading', __( 'Hero heading', 'manangatang-energy' ), 'Get in touch with the project team', 'textarea' );
	me_cz_text( $wp, 'me_sec_contactpage', 'contact_hero_subtitle', __( 'Hero subtitle', 'manangatang-energy' ), 'We welcome questions, feedback and meeting requests from community members, councils, consultants and government stakeholders.', 'textarea' );
	me_cz_text( $wp, 'me_sec_contactpage', 'cf7_contact', __( 'Contact Form 7 — form ID', 'manangatang-energy' ), '' );

	/* Make the contact / footer sections live-refresh on edit. */
	if ( isset( $wp->selective_refresh ) ) {
		$wp->get_setting( 'mec_settings[footer_blurb]' )->transport = 'refresh';
	}
}
add_action( 'customize_register', 'me_customize_register' );

/**
 * Sanitize a Lucide icon name (lowercase letters, digits, hyphens).
 *
 * @param string $value Raw value.
 * @return string
 */
function me_sanitize_icon( $value ) {
	$clean = preg_replace( '/[^a-z0-9-]/', '', strtolower( (string) $value ) );
	return $clean ? $clean : 'phone';
}

/**
 * Sanitize the repeater JSON value.
 *
 * @param string $value JSON string.
 * @return string Clean JSON.
 */
function me_sanitize_contacts( $value ) {
	$items = json_decode( $value, true );
	if ( ! is_array( $items ) ) {
		return '';
	}
	$clean = array();
	foreach ( $items as $it ) {
		if ( ! is_array( $it ) ) {
			continue;
		}
		$label = isset( $it['label'] ) ? sanitize_text_field( $it['label'] ) : '';
		$icon  = isset( $it['icon'] ) ? sanitize_text_field( $it['icon'] ) : '';
		$url   = isset( $it['url'] ) ? esc_url_raw( $it['url'] ) : '';
		// Skip wholly empty rows.
		if ( '' === $label && '' === $icon && '' === $url ) {
			continue;
		}
		$clean[] = array(
			'icon'  => $icon,
			'label' => $label,
			'url'   => $url,
		);
	}
	return wp_json_encode( $clean );
}

/**
 * Curated list of Lucide icon names offered in the icon picker.
 * Filterable via `me_icon_choices`.
 *
 * @return string[]
 */
function me_icon_choices() {
	$icons = array(
		// Contact / general.
		'mail', 'phone', 'phone-call', 'smartphone', 'printer', 'at-sign',
		'map-pin', 'map', 'navigation', 'globe', 'home', 'building', 'building-2',
		'clock', 'calendar', 'calendar-days', 'send', 'message-square', 'message-circle',
		'users', 'user', 'info', 'link', 'external-link', 'file-text', 'download',
		// Social.
		'linkedin', 'facebook', 'twitter', 'instagram', 'youtube', 'github', 'twitch', 'rss',
		// Brand / misc.
		'zap', 'battery-charging', 'sun', 'leaf', 'shield', 'check-circle-2', 'star', 'heart', 'dot',
	);
	return apply_filters( 'me_icon_choices', $icons );
}

/**
 * Enqueue the repeater control script in the Customizer controls pane.
 */
function me_customize_controls_assets() {
	// Lucide so the picker can preview icons inside the Customizer.
	wp_enqueue_script( 'me-lucide-admin', 'https://unpkg.com/lucide@latest', array(), null, true );

	wp_enqueue_script(
		'me-customizer-repeater',
		get_template_directory_uri() . '/assets/js/customizer-repeater.js',
		array( 'jquery', 'customize-controls', 'me-lucide-admin' ),
		ME_VERSION,
		true
	);
	wp_localize_script(
		'me-customizer-repeater',
		'ME_REPEATER',
		array(
			'icons'    => me_icon_choices(),
			'defaults' => me_default_contacts(),
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', 'me_customize_controls_assets' );
