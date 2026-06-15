<?php
/**
 * Manangatang Energy theme functions.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ME_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/github-updater.php';
require_once get_template_directory() . '/inc/smtp.php';
require_once get_template_directory() . '/inc/maintenance.php';

if ( is_admin() ) {
	require_once get_template_directory() . '/inc/admin-customization.php';
}

/**
 * Theme supports & nav menus.
 */
function me_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 80, 'flex-height' => true, 'flex-width' => true ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'manangatang-energy' ),
			'footer'  => __( 'Footer Navigation', 'manangatang-energy' ),
		)
	);
}
add_action( 'after_setup_theme', 'me_setup' );

/**
 * Enqueue styles & scripts.
 */
function me_assets() {
	$uri = get_template_directory_uri();

	// Google Fonts.
	wp_enqueue_style(
		'me-fonts',
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap',
		array(),
		null
	);

	// Theme stylesheet (custom keyframes + CF7 styling).
	wp_enqueue_style( 'me-style', get_stylesheet_uri(), array( 'me-fonts' ), ME_VERSION );

	// Tailwind v4 browser build — scans the DOM and reads the inline @theme config in the header.
	wp_enqueue_script( 'me-tailwind', 'https://unpkg.com/@tailwindcss/browser@4', array(), null, false );

	// Lucide icons.
	wp_enqueue_script( 'me-lucide', 'https://unpkg.com/lucide@latest', array(), null, false );

	// Theme interactions (preloader, parallax, carousel, etc.).
	wp_enqueue_script( 'me-script', $uri . '/assets/js/script.js', array(), ME_VERSION, true );

	// Pass the preloader auto-dismiss time (ms) to the script.
	$pre_ms = (int) me_opt( 'preloader_duration', 4000 );
	if ( $pre_ms < 500 ) {
		$pre_ms = 500;
	}
	wp_add_inline_script( 'me-script', 'window.ME_PRELOADER_MS = ' . $pre_ms . ';', 'before' );
}
add_action( 'wp_enqueue_scripts', 'me_assets' );

/**
 * Body classes mirroring the original markup.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function me_body_class( $classes ) {
	// Only lock scrolling behind the preloader when it's enabled.
	if ( '0' !== (string) me_opt( 'preloader_enable', '1' ) ) {
		$classes[] = 'loading';
	}
	$classes[] = 'bg-white';
	$classes[] = 'text-ink-900';
	$classes[] = 'antialiased';
	return $classes;
}
add_filter( 'body_class', 'me_body_class' );

/**
 * Fallback for me_opt() if the core plugin is inactive, so templates never fatal.
 */
if ( ! function_exists( 'me_opt' ) ) {
	/**
	 * @param string $key     Option key.
	 * @param mixed  $default Fallback.
	 * @return mixed
	 */
	function me_opt( $key, $default = '' ) {
		$fallback = array(
			'phone'           => '0427 739 716',
			'phone_link'      => '0427739716',
			'email'           => 'info@manangatangenergy.com.au',
			'address'         => 'Manangatang, Victoria 3546',
			'footer_blurb'    => 'A proposed 300 MW / 600 MWh Battery Energy Storage System in north-west Victoria — strengthening grid reliability as Australia transitions to renewable energy.',
			'copyright'       => '© 2026 Manangatang Energy Pty Ltd. All rights reserved.',
			'acknowledgement' => 'Acknowledging the Tati Tati people, Traditional Owners of this Country.',
			'stat1_num'       => '300', 'stat1_unit' => 'MW', 'stat1_label' => 'Power capacity',
			'stat2_num'       => '600', 'stat2_unit' => 'MWh', 'stat2_label' => 'Storage',
			'stat3_num'       => '220', 'stat3_unit' => 'kV', 'stat3_label' => 'Transmission line',
			'cf7_home'        => '', 'cf7_contact' => '', 'cf7_feedback' => '',
		);
		if ( isset( $fallback[ $key ] ) ) {
			return $fallback[ $key ];
		}
		return $default;
	}
}

/**
 * Auto-derive the tel: link digits from the display phone number.
 * (Strips spaces, dashes, etc. — keeps digits and a leading +.)
 *
 * @return string
 */
function me_tel() {
	return preg_replace( '/[^0-9+]/', '', (string) me_opt( 'phone' ) );
}

/**
 * Footer contact items (repeater). Returns a list of
 * array( 'icon' => string, 'label' => string, 'url' => string ).
 * Falls back to the email / phone / address settings when none are defined.
 *
 * @return array[]
 */
function me_footer_contacts() {
	$raw   = me_opt( 'footer_contacts' );
	$items = $raw ? json_decode( $raw, true ) : array();

	if ( is_array( $items ) && ! empty( $items ) ) {
		return $items;
	}

	return me_default_contacts();
}

/**
 * The default footer contact rows, built from the contact settings.
 *
 * @return array[]
 */
function me_default_contacts() {
	return array(
		array( 'icon' => 'mail', 'label' => me_opt( 'email' ), 'url' => 'mailto:' . me_opt( 'email' ) ),
		array( 'icon' => 'phone', 'label' => me_opt( 'phone' ), 'url' => 'tel:' . me_tel() ),
		array( 'icon' => 'map-pin', 'label' => me_opt( 'address' ), 'url' => '' ),
	);
}

/**
 * Homepage "Why this site" feature cards (repeater).
 * Each: array( 'icon' => string, 'title' => string, 'text' => string ).
 *
 * @return array[]
 */
function me_home_features() {
	$raw   = me_opt( 'home_features' );
	$items = $raw ? json_decode( $raw, true ) : array();
	if ( is_array( $items ) && ! empty( $items ) ) {
		return $items;
	}
	return me_default_features();
}

/**
 * Default feature cards.
 *
 * @return array[]
 */
function me_default_features() {
	return array(
		array( 'icon' => 'zap', 'title' => 'Existing 220kV Transmission Corridor', 'text' => 'The site is located adjacent to the existing 220kV Bendigo Mildura transmission line.' ),
		array( 'icon' => 'map-pin', 'title' => 'Strategic Mallee Location', 'text' => "Well positioned to support Victoria's energy transition and strengthen grid reliability." ),
		array( 'icon' => 'wheat', 'title' => 'Productive Farmland Landholding', 'text' => 'The majority of the land will remain in agricultural use, supporting the local economy for future generations.' ),
		array( 'icon' => 'users', 'title' => 'Community Focused', 'text' => 'We are committed to open communication, local engagement and listening to your feedback.' ),
	);
}

/**
 * Homepage "The Site" section list items (repeater).
 * Each: array( 'icon' => string, 'text' => string ).
 *
 * @return array[]
 */
function me_homesite_items() {
	$raw   = me_opt( 'homesite_items' );
	$items = $raw ? json_decode( $raw, true ) : array();
	if ( is_array( $items ) && ! empty( $items ) ) {
		return $items;
	}
	return me_default_homesite_items();
}

/**
 * Default "The Site" list items.
 *
 * @return array[]
 */
function me_default_homesite_items() {
	return array(
		array( 'icon' => 'ruler', 'text' => 'Approximately 1,200 acres' ),
		array( 'icon' => 'cable', 'text' => 'Adjacent to 220kV transmission line' ),
		array( 'icon' => 'route', 'text' => 'Direct road access via Daytrap Road' ),
		array( 'icon' => 'tractor', 'text' => 'Existing farming operation' ),
	);
}

/**
 * Homepage "The Site" carousel slides — a list of image URLs.
 *
 * @return string[]
 */
function me_homesite_slides() {
	$raw   = me_opt( 'homesite_slides' );
	$items = $raw ? json_decode( $raw, true ) : array();
	if ( is_array( $items ) && ! empty( $items ) ) {
		// Normalize (support both a plain URL list and the older {img:url} rows).
		$items = array_map(
			function ( $it ) {
				return is_array( $it ) ? ( isset( $it['img'] ) ? $it['img'] : '' ) : $it;
			},
			$items
		);
		$items = array_values( array_filter( $items ) );
		if ( ! empty( $items ) ) {
			return $items;
		}
	}
	return me_default_homesite_slides();
}

/**
 * Default carousel slides (image URLs).
 *
 * @return string[]
 */
function me_default_homesite_slides() {
	$img = get_template_directory_uri() . '/assets/img';
	return array( $img . '/site-1.webp', $img . '/site-2.jpg', $img . '/site-3.webp' );
}

/**
 * Homepage "Battery Storage Explained" steps (repeater).
 * Each: array( 'icon' => string, 'title' => string, 'text' => string ).
 *
 * @return array[]
 */
function me_battery_steps() {
	$raw   = me_opt( 'battery_steps' );
	$items = $raw ? json_decode( $raw, true ) : array();
	if ( is_array( $items ) && ! empty( $items ) ) {
		return $items;
	}
	return me_default_battery_steps();
}

/**
 * Default battery steps.
 *
 * @return array[]
 */
function me_default_battery_steps() {
	return array(
		array( 'icon' => 'sun', 'title' => 'Charge', 'text' => 'Energy is stored in batteries when supply is abundant (e.g. solar or wind).' ),
		array( 'icon' => 'battery-charging', 'title' => 'Store', 'text' => "The energy is stored safely and efficiently until it's needed." ),
		array( 'icon' => 'building-2', 'title' => 'Supply', 'text' => 'Stored energy is released back to the grid when demand increases.' ),
	);
}

/**
 * About page — specification rows. Each: array( 'label', 'value' ).
 *
 * @return array[]
 */
function me_about_specs() {
	$raw = me_opt( 'about_specs' );
	$i   = $raw ? json_decode( $raw, true ) : array();
	return ( is_array( $i ) && ! empty( $i ) ) ? $i : me_default_about_specs();
}

/**
 * @return array[]
 */
function me_default_about_specs() {
	return array(
		array( 'label' => 'Power capacity', 'value' => 'TBC' ),
		array( 'label' => 'Energy capacity', 'value' => 'TBC' ),
		array( 'label' => 'Duration', 'value' => 'TBC' ),
		array( 'label' => 'Technology', 'value' => 'Lithium-iron-phosphate (LFP)' ),
		array( 'label' => 'Site footprint', 'value' => '~5 hectares' ),
		array( 'label' => 'Grid connection', 'value' => '220 kV (TBC)' ),
		array( 'label' => 'Design life', 'value' => '25+ years' ),
		array( 'label' => 'Operational target', 'value' => 'Q4 2028' ),
	);
}

/**
 * About page — "Designed for safety" checklist. Each: array( 'text' ).
 *
 * @return array[]
 */
function me_about_safety() {
	$raw = me_opt( 'about_safety_items' );
	$i   = $raw ? json_decode( $raw, true ) : array();
	return ( is_array( $i ) && ! empty( $i ) ) ? $i : me_default_about_safety();
}

/**
 * @return array[]
 */
function me_default_about_safety() {
	return array(
		array( 'icon' => 'check-circle-2', 'text' => 'Compliant with AEMO grid connection requirements' ),
		array( 'icon' => 'activity', 'text' => 'Multi-layer monitoring: cell, module, container, and site level' ),
		array( 'icon' => 'flame', 'text' => 'Site-wide fire detection and emergency response plan developed with CFA' ),
		array( 'icon' => 'volume-2', 'text' => 'Acoustic design targeting compliance with EPA Victoria noise limits' ),
	);
}

/**
 * Latest 3 Events (me_event CPT) as session cards.
 * Falls back to the bundled defaults when no events exist.
 *
 * @return array[] Each: array( 'venue', 'date', 'time', 'address' ).
 */
function me_community_events() {
	$out = array();
	if ( post_type_exists( 'me_event' ) ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'me_event',
				'posts_per_page' => 3,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'no_found_rows'  => true,
			)
		);
		while ( $q->have_posts() ) {
			$q->the_post();
			$raw_date = get_post_meta( get_the_ID(), '_mec_event_date', true );
			$start    = get_post_meta( get_the_ID(), '_mec_event_time_start', true );
			$end      = get_post_meta( get_the_ID(), '_mec_event_time_end', true );

			$date = $raw_date ? date_i18n( 'D j M Y', strtotime( $raw_date ) ) : '';
			$time = '';
			if ( $start ) {
				$time = date_i18n( 'g:i a', strtotime( $start ) );
			}
			if ( $end ) {
				$time .= ( '' !== $time ? ' – ' : '' ) . date_i18n( 'g:i a', strtotime( $end ) );
			}

			$expect_raw = (string) get_post_meta( get_the_ID(), '_mec_event_expect', true );
			$out[]      = array(
				'venue'      => get_the_title(),
				'date'       => $date,
				'time'       => $time,
				'address'    => get_post_meta( get_the_ID(), '_mec_event_address', true ),
				'directions' => get_post_meta( get_the_ID(), '_mec_event_directions', true ),
				'expect'     => array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $expect_raw ) ) ) ),
			);
		}
		wp_reset_postdata();
	}
	return ! empty( $out ) ? $out : me_default_comm_sessions();
}

/**
 * Community page — drop-in sessions (default cards used as a fallback).
 *
 * @return array[]
 */
function me_comm_sessions() {
	$raw = me_opt( 'comm_sessions' );
	$i   = $raw ? json_decode( $raw, true ) : array();
	return ( is_array( $i ) && ! empty( $i ) ) ? $i : me_default_comm_sessions();
}

/**
 * @return array[]
 */
function me_default_comm_sessions() {
	return array(
		array( 'date' => 'Tue 17 Mar 2026', 'time' => '3:00 – 7:00 pm', 'venue' => 'Manangatang Memorial Hall', 'address' => 'King St, Manangatang VIC 3546' ),
		array( 'date' => 'Wed 18 Mar 2026', 'time' => '10:00 am – 2:00 pm', 'venue' => 'Swan Hill Town Hall', 'address' => 'Splatt St, Swan Hill VIC 3585' ),
		array( 'date' => 'Sat 21 Mar 2026', 'time' => '9:00 am – 12:00 pm', 'venue' => 'Ouyen Community Hub', 'address' => 'Rowe St, Ouyen VIC 3490' ),
	);
}

/**
 * The Site — "at a glance" bento cards. Each: array( 'icon', 'label', 'text' ).
 *
 * @return array[]
 */
function me_site_glance_cards() {
	$raw = me_opt( 'siteglance_cards' );
	$i   = $raw ? json_decode( $raw, true ) : array();
	return ( is_array( $i ) && ! empty( $i ) ) ? $i : me_default_site_glance_cards();
}

/**
 * @return array[]
 */
function me_default_site_glance_cards() {
	return array(
		array( 'icon' => 'map-pin', 'label' => 'Address', 'text' => 'Approx. 4 km north of Manangatang township, Mildura Rural City LGA, VIC 3546' ),
		array( 'icon' => 'wheat', 'label' => 'Land use', 'text' => 'Broadacre agricultural land with existing access tracks and vegetated road reserves' ),
		array( 'icon' => 'trees', 'label' => 'Setting', 'text' => 'Predominantly cleared farmland with local tree belts and remnant vegetation along road corridors' ),
		array( 'icon' => 'cable', 'label' => 'Grid', 'text' => 'Adjacent to the current 220 kV Bendigo–Mildura transmission line and nearby terminal infrastructure' ),
	);
}

/**
 * The Site — image-band cards. Each: array( 'icon', 'title', 'text' ).
 *
 * @return array[]
 */
function me_site_band_cards() {
	$raw = me_opt( 'siteband_cards' );
	$i   = $raw ? json_decode( $raw, true ) : array();
	return ( is_array( $i ) && ! empty( $i ) ) ? $i : me_default_site_band_cards();
}

/**
 * @return array[]
 */
function me_default_site_band_cards() {
	return array(
		array( 'icon' => 'plug-zap', 'title' => 'Why this location?', 'text' => 'Direct access to existing high-voltage infrastructure reduces the need for major new linear assets and helps keep the project compact.' ),
		array( 'icon' => 'tractor', 'title' => 'Neighbouring land', 'text' => 'The surrounding landscape remains in agricultural use, with existing tree lines and road reserves forming the dominant local visual pattern.' ),
		array( 'icon' => 'network', 'title' => 'Regional role', 'text' => 'Manangatang sits within a part of Victoria already closely associated with transmission planning, grid reinforcement and renewable energy development.' ),
	);
}

/**
 * The Site — reference mapping figures. Each: array( 'image', 'caption' ).
 *
 * @return array[]
 */
function me_site_maps() {
	$raw = me_opt( 'sitemaps' );
	$i   = $raw ? json_decode( $raw, true ) : array();
	return ( is_array( $i ) && ! empty( $i ) ) ? $i : me_default_site_maps();
}

/**
 * @return array[]
 */
function me_default_site_maps() {
	$img = get_template_directory_uri() . '/assets/img';
	return array(
		array( 'image' => $img . '/map.png', 'caption' => 'Indicative boundary mapping showing the broader land parcel and its relationship to Manangatang township.' ),
		array( 'image' => $img . '/victoria-planning.png', 'caption' => 'Wider Victorian planning context showing how Manangatang sits within broader network reinforcement and renewable energy development corridors.' ),
		array( 'image' => $img . '/regional-network.png', 'caption' => 'Regional network context showing Manangatang along the current 220 kV Bendigo–Mildura transmission corridor.' ),
	);
}

/**
 * FAQ items (managed in Customization → FAQs).
 * Each: array( 'category', 'question', 'answer' ).
 *
 * @return array[]
 */
function me_faqs() {
	$raw   = me_opt( 'faqs_items' );
	$items = $raw ? json_decode( $raw, true ) : array();
	return ( is_array( $items ) && ! empty( $items ) ) ? $items : me_default_faqs();
}

/**
 * @return array[]
 */
function me_default_faqs() {
	return array(
		array( 'category' => 'About the project', 'question' => 'What is a Battery Energy Storage System (BESS)?', 'answer' => 'A BESS is a large-scale facility that stores electrical energy in batteries so it can be discharged back to the grid when needed. It acts like a giant rechargeable battery for the entire power system.' ),
		array( 'category' => 'About the project', 'question' => 'How big is the Manangatang BESS?', 'answer' => '300 megawatts of power capacity and 600 megawatt-hours of energy storage — enough to power approximately 180,000 Victorian homes for two hours during peak demand.' ),
		array( 'category' => 'About the project', 'question' => 'Who is developing the project?', 'answer' => 'Manangatang Energy Pty Ltd, an Australian renewable energy developer with experience delivering grid-scale storage projects across the National Electricity Market.' ),
		array( 'category' => 'Safety & environment', 'question' => 'Are batteries safe?', 'answer' => 'The project uses lithium-iron-phosphate (LFP) battery chemistry, which has a significantly lower thermal-runaway risk than older NMC chemistries and is the industry standard for new grid-scale storage in Australia.' ),
		array( 'category' => 'Safety & environment', 'question' => 'What happens in a bushfire?', 'answer' => 'The facility will be designed in consultation with CFA to meet or exceed Victorian bushfire planning requirements, with defendable space, on-site water supply, and 24/7 monitoring.' ),
		array( 'category' => 'Safety & environment', 'question' => 'Will it be noisy?', 'answer' => 'Operational noise from cooling systems is the primary source. Acoustic modelling is being undertaken to demonstrate compliance with EPA Victoria noise limits at the nearest dwellings.' ),
		array( 'category' => 'Safety & environment', 'question' => 'What about visual impact?', 'answer' => 'Battery containers are around 3 metres high — well below the height of existing transmission infrastructure at the adjacent terminal station. Landscape screening will be used where appropriate.' ),
		array( 'category' => 'Community & benefits', 'question' => 'How many jobs will be created?', 'answer' => 'Construction will peak at around 150 workers. The project is committed to local employment and training partnerships with TAFEs in the region. Ongoing operations will support several permanent local roles.' ),
		array( 'category' => 'Community & benefits', 'question' => 'What is the Community Benefit Fund?', 'answer' => 'An independently administered fund delivering approximately $150,000 per year for the 25-year operating life of the project. The local community decides how it\'s invested through a transparent grants process.' ),
		array( 'category' => 'Community & benefits', 'question' => 'Will my power bill go down?', 'answer' => 'Battery storage helps reduce wholesale electricity prices during peak demand periods. The combined effect of new storage across Victoria is forecast by AEMO to lower wholesale prices over time.' ),
		array( 'category' => 'Approvals & timeline', 'question' => 'What approvals are required?', 'answer' => 'A planning permit from Mildura Rural City Council, an Environmental Effects referral to the Minister for Planning, an Aboriginal Cultural Heritage Management Plan, and a grid connection agreement with AEMO and AusNet.' ),
		array( 'category' => 'Approvals & timeline', 'question' => 'When will construction start?', 'answer' => 'Subject to approvals, construction is expected to begin in late 2026 and take approximately 12 months to complete.' ),
		array( 'category' => 'Approvals & timeline', 'question' => 'Who do I contact with questions?', 'answer' => 'Our community engagement team is available on 0427 739 716 or info@manangatangenergy.com.au, Monday to Friday 9am–5pm AEST.' ),
	);
}

/**
 * Document items (managed in Customization → Documents).
 * Each: array( 'category', 'title', 'file', 'label' ).
 *
 * @return array[]
 */
function me_documents() {
	$raw   = me_opt( 'docs_items' );
	$items = $raw ? json_decode( $raw, true ) : array();
	return ( is_array( $items ) && ! empty( $items ) ) ? $items : me_default_documents();
}

/**
 * Build an automatic meta line for a document from its uploaded file:
 * "PDF · 1.2 MB · May 2026". Returns '' when the file isn't a known
 * media-library attachment (e.g. an external URL).
 *
 * @param string $url File URL.
 * @return string
 */
function me_doc_meta( $url ) {
	if ( ! $url ) {
		return '';
	}
	$parts = array();
	$id    = attachment_url_to_postid( $url );

	if ( $id ) {
		$path = get_attached_file( $id );
		$type = wp_check_filetype( $path ? $path : $url );
		if ( ! empty( $type['ext'] ) ) {
			$parts[] = strtoupper( $type['ext'] );
		}
		if ( $path && file_exists( $path ) ) {
			$bytes   = filesize( $path );
			$parts[] = size_format( $bytes, $bytes >= 1048576 ? 1 : 0 );
		}
		$date = get_the_date( 'M Y', $id );
		if ( $date ) {
			$parts[] = $date;
		}
	} else {
		$ext = strtoupper( pathinfo( (string) wp_parse_url( $url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
		if ( $ext ) {
			$parts[] = $ext;
		}
	}

	return implode( ' · ', array_filter( $parts ) );
}

/**
 * @return array[]
 */
function me_default_documents() {
	return array(
		array( 'category' => 'Project information', 'title' => 'Project Fact Sheet', 'file' => '', 'label' => 'PDF · 1.2 MB · Published May 2026' ),
		array( 'category' => 'Project information', 'title' => 'Frequently Asked Questions', 'file' => '', 'label' => 'PDF · 0.8 MB · Published May 2026' ),
		array( 'category' => 'Project information', 'title' => 'Community Benefit Fund — Proposed Framework', 'file' => '', 'label' => 'PDF · 2.4 MB · Published Jan 2026' ),
		array( 'category' => 'Environmental assessments', 'title' => 'Preliminary Environmental Assessment', 'file' => '', 'label' => 'PDF · 8.6 MB · Published Apr 2026' ),
		array( 'category' => 'Environmental assessments', 'title' => 'Noise Impact Assessment', 'file' => '', 'label' => 'PDF · 3.1 MB · Published Apr 2026' ),
		array( 'category' => 'Environmental assessments', 'title' => 'Visual Impact Assessment', 'file' => '', 'label' => 'PDF · 12.4 MB · Published Apr 2026' ),
		array( 'category' => 'Environmental assessments', 'title' => 'Traffic Impact Assessment', 'file' => '', 'label' => 'PDF · 2.7 MB · Published Apr 2026' ),
		array( 'category' => 'Environmental assessments', 'title' => 'Bushfire Management Statement', 'file' => '', 'label' => 'PDF · 4.2 MB · Published Apr 2026' ),
		array( 'category' => 'Planning & approvals', 'title' => 'Planning Permit Application', 'file' => '', 'label' => 'PDF · 5.5 MB · Published Apr 2026' ),
		array( 'category' => 'Planning & approvals', 'title' => 'Environmental Effects Referral', 'file' => '', 'label' => 'PDF · 3.8 MB · Published Mar 2026' ),
		array( 'category' => 'Planning & approvals', 'title' => 'AEMO Connection Enquiry Response', 'file' => '', 'label' => 'PDF · 1.6 MB · Published Feb 2026' ),
		array( 'category' => 'Consultation reports', 'title' => 'Stage 1 Engagement Summary', 'file' => '', 'label' => 'PDF · 2.9 MB · Published May 2026' ),
		array( 'category' => 'Consultation reports', 'title' => 'Submissions Register (Q1 2026)', 'file' => '', 'label' => 'PDF · 1.1 MB · Published Apr 2026' ),
	);
}

/**
 * Whether a section toggle is on. Defaults to visible (on) when unset.
 *
 * @param string $key Settings key (e.g. home_show_site).
 * @return bool
 */
function me_show( $key ) {
	return '0' !== (string) me_opt( $key, '1' );
}

/**
 * Echo a multi-line setting as escaped HTML with <br> for line breaks.
 *
 * @param string $key     Settings key.
 * @param string $default Default text.
 */
function me_multiline( $key, $default = '' ) {
	echo nl2br( esc_html( me_opt( $key, $default ) ) );
}

/**
 * Render a Contact Form 7 form by its settings key, wrapped for styling.
 * Falls back to a friendly note if CF7 / the form isn't available.
 *
 * @param string $key      Settings key (cf7_contact, cf7_home, cf7_feedback).
 * @param string $fallback Optional fallback note.
 */
function me_render_form( $key, $fallback = '' ) {
	$form_id = me_opt( $key );
	echo '<div class="mec-cf7">';
	if ( $form_id && shortcode_exists( 'contact-form-7' ) ) {
		echo do_shortcode( '[contact-form-7 id="' . absint( $form_id ) . '"]' );
	} else {
		$note = $fallback ? $fallback : __( 'The contact form will appear here once Contact Form 7 is active and configured under Manangatang → Settings.', 'manangatang-energy' );
		echo '<p class="rounded-xl border border-ink-100 bg-ink-50 p-5 text-sm text-ink-700/80">' . esc_html( $note ) . '</p>';
	}
	echo '</div>';
}

/**
 * Helper: news category name for a post (first term).
 *
 * @param int $post_id Post ID.
 * @return string
 */
function me_news_cat( $post_id ) {
	$terms = get_the_terms( $post_id, 'me_news_cat' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		return $terms[0]->name;
	}
	return '';
}

/**
 * Limit how many articles show per page on the News archive (the rest paginate).
 * Editable via Customization → News → "Articles per page".
 *
 * @param WP_Query $query Main query.
 */
function me_news_posts_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'me_news' ) ) {
		$n = (int) me_opt( 'news_per_page', 9 );
		$query->set( 'posts_per_page', $n > 0 ? $n : 9 );
	}
}
add_action( 'pre_get_posts', 'me_news_posts_per_page' );

/**
 * Admin notice if the core plugin isn't active.
 */
function me_plugin_notice() {
	if ( function_exists( 'mec_get_option' ) ) {
		return;
	}
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>Manangatang Energy:</strong> the theme works best with the <em>Manangatang Energy Core</em> plugin (News, Documents, FAQs &amp; settings). Please activate it.</p></div>';
}
add_action( 'admin_notices', 'me_plugin_notice' );

/**
 * Use a larger excerpt and custom "read more".
 *
 * @param string $more Default more string.
 * @return string
 */
function me_excerpt_more( $more ) {
	return '…';
}
add_filter( 'excerpt_more', 'me_excerpt_more' );

/**
 * Build a normalized list of primary-nav items.
 *
 * Reads the menu assigned to the "primary" location; if none is set,
 * falls back to the expected page/archive structure.
 *
 * @return array[] Each: array( 'url' => string, 'label' => string, 'active' => bool ).
 */
function me_nav_get_items() {
	$items     = array();
	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['primary'] ) ) {
		$menu_items = wp_get_nav_menu_items( $locations['primary'] );
		if ( $menu_items ) {
			foreach ( $menu_items as $mi ) {
				// Top-level only.
				if ( (int) $mi->menu_item_parent !== 0 ) {
					continue;
				}
				$items[] = array(
					'url'    => $mi->url,
					'label'  => $mi->title,
					'active' => me_nav_is_active( $mi ),
				);
			}
		}
	}

	// Fallback structure when no menu has been assigned yet.
	if ( empty( $items ) ) {
		$fallback = array(
			array( 'project-overview', __( 'About the Project', 'manangatang-energy' ) ),
			array( 'the-site', __( 'The Site', 'manangatang-energy' ) ),
			array( 'community', __( 'Community', 'manangatang-energy' ) ),
			array( '__news__', __( 'News & Updates', 'manangatang-energy' ) ),
			array( 'faqs', __( 'FAQs', 'manangatang-energy' ) ),
			array( 'contact', __( 'Contact', 'manangatang-energy' ) ),
		);
		foreach ( $fallback as $f ) {
			if ( '__news__' === $f[0] ) {
				$url    = get_post_type_archive_link( 'me_news' );
				$active = is_post_type_archive( 'me_news' ) || is_singular( 'me_news' );
			} else {
				$page   = get_page_by_path( $f[0] );
				$url    = $page ? get_permalink( $page ) : home_url( '/' . $f[0] . '/' );
				$active = $page ? is_page( $page->ID ) : false;
			}
			$items[] = array( 'url' => $url, 'label' => $f[1], 'active' => $active );
		}
	}

	return $items;
}

/**
 * Decide whether a nav menu item points at what's currently displayed.
 *
 * @param object $mi Menu item.
 * @return bool
 */
function me_nav_is_active( $mi ) {
	// News archive / single news.
	if ( ( is_post_type_archive( 'me_news' ) || is_singular( 'me_news' ) ) && false !== strpos( $mi->url, '/news' ) ) {
		return true;
	}
	// Page object.
	if ( 'post_type' === $mi->type && is_page( (int) $mi->object_id ) ) {
		return true;
	}
	// Generic URL match.
	$current = trailingslashit( strtok( home_url( add_query_arg( array(), $GLOBALS['wp']->request ) ), '?' ) );
	if ( $mi->url && trailingslashit( $mi->url ) === $current ) {
		return true;
	}
	return false;
}

/**
 * Render the primary nav items in either desktop or mobile style.
 *
 * @param string $mode 'desktop' or 'mobile'.
 */
function me_nav_items( $mode = 'desktop' ) {
	foreach ( me_nav_get_items() as $item ) {
		$url   = esc_url( $item['url'] );
		$label = esc_html( $item['label'] );
		if ( 'mobile' === $mode ) {
			$class = $item['active']
				? 'block rounded-lg px-4 py-3 text-sm font-semibold text-grass-600 bg-grass-50'
				: 'block rounded-lg px-4 py-3 text-sm font-medium text-ink-700 hover:bg-ink-50';
			printf( '<a href="%s" class="%s">%s</a>', $url, esc_attr( $class ), $label );
		} else {
			if ( $item['active'] ) {
				printf(
					'<a href="%s" class="relative rounded-lg px-3 py-2 text-sm font-semibold text-ink-900">%s<span class="absolute inset-x-3 -bottom-0.5 h-0.5 rounded-full bg-grass-500"></span></a>',
					$url,
					$label
				);
			} else {
				printf(
					'<a href="%s" class="rounded-lg px-3 py-2 text-sm font-medium text-ink-700/80 transition hover:bg-ink-50 hover:text-ink-900">%s</a>',
					$url,
					$label
				);
			}
		}
	}
}
