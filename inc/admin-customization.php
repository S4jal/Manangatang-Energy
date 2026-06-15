<?php
/**
 * Dashboard "Customization" page under the Manangatang menu.
 *
 * Two-pane editor: page list on the left, that page's edit fields on the
 * right. Saves into the shared `mec_settings` option, so it stays in sync
 * with the Customizer and the front-end.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema: each page and the fields it exposes.
 * type: text | textarea | toggle
 *
 * @return array
 */
function me_page_schema() {

	// Default button links track the matching pages until overridden.
	$ov  = get_page_by_path( 'project-overview' );
	$cm  = get_page_by_path( 'community' );
	$ts  = get_page_by_path( 'the-site' );
	$fq  = get_page_by_path( 'faqs' );
	$ct  = get_page_by_path( 'contact' );
	$ov_url = $ov ? get_permalink( $ov ) : '';
	$cm_url = $cm ? get_permalink( $cm ) : '';
	$ts_url = $ts ? get_permalink( $ts ) : '';
	$fq_url = $fq ? get_permalink( $fq ) : '';
	$ct_url = $ct ? get_permalink( $ct ) : '';

	return array(
		'siteinfo' => array(
			'label'  => __( 'Contact info (global)', 'manangatang-energy' ),
			'fields' => array(
				array( 'type' => 'subheading', 'label' => __( 'Used site-wide — header, footer, contact page & engagement sidebar', 'manangatang-energy' ) ),
				array( 'key' => 'phone', 'label' => __( 'Phone', 'manangatang-energy' ), 'type' => 'text', 'default' => '0427 739 716' ),
				array( 'key' => 'email', 'label' => __( 'Email', 'manangatang-energy' ), 'type' => 'text', 'default' => 'info@manangatangenergy.com.au' ),
				array( 'key' => 'address', 'label' => __( 'Address', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Manangatang, Victoria 3546' ),
			),
		),
		'footer'   => array(
			'label'  => __( 'Footer', 'manangatang-energy' ),
			'fields' => array(
				array( 'key' => 'footer_blurb', 'label' => __( 'Footer blurb', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'A proposed 300 MW / 600 MWh Battery Energy Storage System in north-west Victoria — strengthening grid reliability as Australia transitions to renewable energy.' ),
				array( 'key' => 'copyright', 'label' => __( 'Copyright line', 'manangatang-energy' ), 'type' => 'text', 'default' => '© 2026 Manangatang Energy Pty Ltd. All rights reserved.' ),
				array( 'key' => 'acknowledgement', 'label' => __( 'Acknowledgement of Country', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Acknowledging the Tati Tati people, Traditional Owners of this Country.' ),
				array( 'type' => 'subheading', 'label' => __( 'Contact column items', 'manangatang-energy' ) ),
				array(
					'key'       => 'footer_contacts',
					'label'     => __( 'Contact items', 'manangatang-energy' ),
					'type'      => 'repeater',
					'add_label' => __( 'Add item', 'manangatang-energy' ),
					'default'   => me_default_contacts(),
					'subfields' => array(
						array( 'key' => 'icon', 'type' => 'icon', 'label' => __( 'Icon', 'manangatang-energy' ) ),
						array( 'key' => 'label', 'type' => 'text', 'label' => __( 'Text', 'manangatang-energy' ) ),
						array( 'key' => 'url', 'type' => 'text', 'label' => __( 'Link', 'manangatang-energy' ) ),
					),
				),
			),
		),
		'home'     => array(
			'label'  => __( 'Home', 'manangatang-energy' ),
			'groups' => array(
				array(
					'label'  => __( 'Hero section', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'home_hero_bg', 'label' => __( 'Background image', 'manangatang-energy' ), 'type' => 'image' ),
						array( 'key' => 'home_hero_heading', 'label' => __( 'Hero heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => "Manangatang\nEnergy" ),
						array( 'key' => 'home_hero_tagline', 'label' => __( 'Hero tagline', 'manangatang-energy' ), 'type' => 'textarea', 'default' => "Reliable Energy Infrastructure for Victoria's Future" ),
						array( 'key' => 'home_hero_intro', 'label' => __( 'Hero intro paragraph', 'manangatang-energy' ), 'type' => 'textarea', 'default' => "Located near Manangatang in Victoria's Mallee region, the project is exploring utility-scale battery energy storage to support grid reliability and renewable energy integration." ),
						array(
							'type'   => 'inline',
							'label'  => __( 'Button 1', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'home_btn1_text', 'ph' => __( 'Text', 'manangatang-energy' ), 'default' => 'Project Overview' ),
								array( 'key' => 'home_btn1_link', 'ph' => __( 'Link', 'manangatang-energy' ), 'default' => $ov_url ),
							),
						),
						array(
							'type'   => 'inline',
							'label'  => __( 'Button 2', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'home_btn2_text', 'ph' => __( 'Text', 'manangatang-energy' ), 'default' => 'Community Information' ),
								array( 'key' => 'home_btn2_link', 'ph' => __( 'Link', 'manangatang-energy' ), 'default' => $cm_url ),
							),
						),
						array(
							'type'   => 'inline',
							'label'  => __( 'Statistic 1', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'stat1_num', 'ph' => __( 'Number', 'manangatang-energy' ), 'default' => '300' ),
								array( 'key' => 'stat1_unit', 'ph' => __( 'Unit', 'manangatang-energy' ), 'default' => 'MW' ),
								array( 'key' => 'stat1_label', 'ph' => __( 'Label', 'manangatang-energy' ), 'default' => 'Power capacity' ),
							),
						),
						array(
							'type'   => 'inline',
							'label'  => __( 'Statistic 2', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'stat2_num', 'ph' => __( 'Number', 'manangatang-energy' ), 'default' => '600' ),
								array( 'key' => 'stat2_unit', 'ph' => __( 'Unit', 'manangatang-energy' ), 'default' => 'MWh' ),
								array( 'key' => 'stat2_label', 'ph' => __( 'Label', 'manangatang-energy' ), 'default' => 'Storage' ),
							),
						),
						array(
							'type'   => 'inline',
							'label'  => __( 'Statistic 3', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'stat3_num', 'ph' => __( 'Number', 'manangatang-energy' ), 'default' => '220' ),
								array( 'key' => 'stat3_unit', 'ph' => __( 'Unit', 'manangatang-energy' ), 'default' => 'kV' ),
								array( 'key' => 'stat3_label', 'ph' => __( 'Label', 'manangatang-energy' ), 'default' => 'Transmission line' ),
							),
						),
						array( 'key' => 'home_marquee', 'label' => __( 'Marquee ticker (one item per line)', 'manangatang-energy' ), 'type' => 'textarea', 'default' => "300 MW Power\n600 MWh Storage\n220 kV Grid Connection\nLFP Chemistry\n~12 Hectares\n25+ Year Design Life\nOperational Q4 2027" ),
					),
				),
				array(
					'label'  => __( '“Why this site” cards', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'home_show_features', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'home_features_eyebrow', 'label' => __( 'Subtitle (eyebrow)', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Why this site' ),
						array( 'key' => 'home_features_heading', 'label' => __( 'Title', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Built on the right foundations' ),
						array(
							'key'       => 'home_features',
							'label'     => __( 'Cards', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add card', 'manangatang-energy' ),
							'default'   => me_default_features(),
							'subfields' => array(
								array( 'key' => 'icon', 'type' => 'icon', 'label' => __( 'Icon', 'manangatang-energy' ) ),
								array( 'key' => 'title', 'type' => 'text', 'label' => __( 'Title', 'manangatang-energy' ) ),
								array( 'key' => 'text', 'type' => 'textarea', 'label' => __( 'Description', 'manangatang-energy' ) ),
							),
						),
					),
				),
				array(
					'label'  => __( '“The Site” section', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'home_show_site', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'homesite_eyebrow', 'label' => __( 'Subtitle (eyebrow)', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Overview' ),
						array( 'key' => 'homesite_title', 'label' => __( 'Title', 'manangatang-energy' ), 'type' => 'text', 'default' => 'The Site' ),
						array( 'key' => 'homesite_intro', 'label' => __( 'Intro paragraph', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'A suitable location for battery energy storage with existing infrastructure, strong access and minimal impact on surrounding land uses.' ),
						array(
							'key'       => 'homesite_items',
							'label'     => __( 'List items', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add item', 'manangatang-energy' ),
							'default'   => me_default_homesite_items(),
							'subfields' => array(
								array( 'key' => 'icon', 'type' => 'icon', 'label' => __( 'Icon', 'manangatang-energy' ) ),
								array( 'key' => 'text', 'type' => 'text', 'label' => __( 'Text', 'manangatang-energy' ) ),
							),
						),
						array( 'key' => 'homesite_slides', 'label' => __( 'Carousel images', 'manangatang-energy' ), 'type' => 'gallery', 'default' => me_default_homesite_slides() ),
						array(
							'type'   => 'inline',
							'label'  => __( 'Button', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'homesite_btn_text', 'ph' => __( 'Text', 'manangatang-energy' ), 'default' => 'Learn more about the site' ),
								array( 'key' => 'homesite_btn_link', 'ph' => __( 'Link', 'manangatang-energy' ), 'default' => $ts_url ),
							),
						),
						array(
							'type'   => 'inline',
							'label'  => __( 'Floating badge', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'homesite_badge_num', 'ph' => __( 'Number', 'manangatang-energy' ), 'default' => '≈1,200' ),
								array( 'key' => 'homesite_badge_unit', 'ph' => __( 'Unit', 'manangatang-energy' ), 'default' => 'ac' ),
								array( 'key' => 'homesite_badge_label', 'ph' => __( 'Label', 'manangatang-energy' ), 'default' => 'Site area' ),
							),
						),
					),
				),
				array(
					'label'  => __( '“Battery Storage Explained”', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'home_show_battery', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'battery_eyebrow', 'label' => __( 'Subtitle (eyebrow)', 'manangatang-energy' ), 'type' => 'text', 'default' => 'How it works' ),
						array( 'key' => 'battery_heading', 'label' => __( 'Title', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Battery Energy Storage Explained' ),
						array( 'key' => 'battery_intro', 'label' => __( 'Intro paragraph', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Battery energy storage helps keep electricity reliable by storing energy when supply is abundant and releasing it when needed.' ),
						array(
							'key'       => 'battery_steps',
							'label'     => __( 'Steps', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add step', 'manangatang-energy' ),
							'default'   => me_default_battery_steps(),
							'subfields' => array(
								array( 'key' => 'icon', 'type' => 'icon', 'label' => __( 'Icon', 'manangatang-energy' ) ),
								array( 'key' => 'title', 'type' => 'text', 'label' => __( 'Title', 'manangatang-energy' ) ),
								array( 'key' => 'text', 'type' => 'textarea', 'label' => __( 'Description', 'manangatang-energy' ) ),
							),
						),
						array(
							'type'   => 'inline',
							'label'  => __( 'Button', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'battery_btn_text', 'ph' => __( 'Text', 'manangatang-energy' ), 'default' => 'How battery storage works' ),
								array( 'key' => 'battery_btn_link', 'ph' => __( 'Link', 'manangatang-energy' ), 'default' => $fq_url ),
							),
						),
					),
				),
				array(
					'label'  => __( '“Community Matters” band', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'home_show_community', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'homecomm_bg', 'label' => __( 'Background image', 'manangatang-energy' ), 'type' => 'image' ),
						array( 'key' => 'homecomm_eyebrow', 'label' => __( 'Subtitle (eyebrow)', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Engagement' ),
						array( 'key' => 'homecomm_heading', 'label' => __( 'Title', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Community Matters' ),
						array( 'key' => 'homecomm_text', 'label' => __( 'Paragraph', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Community consultation is a key part of project development. We are committed to sharing information openly and listening to local feedback.' ),
						array(
							'type'   => 'inline',
							'label'  => __( 'Button 1', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'homecomm_btn1_text', 'ph' => __( 'Text', 'manangatang-energy' ), 'default' => 'Frequently Asked Questions' ),
								array( 'key' => 'homecomm_btn1_link', 'ph' => __( 'Link', 'manangatang-energy' ), 'default' => $fq_url ),
							),
						),
						array(
							'type'   => 'inline',
							'label'  => __( 'Button 2', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'homecomm_btn2_text', 'ph' => __( 'Text', 'manangatang-energy' ), 'default' => 'Contact Us' ),
								array( 'key' => 'homecomm_btn2_link', 'ph' => __( 'Link', 'manangatang-energy' ), 'default' => $ct_url ),
							),
						),
					),
				),
				array(
					'label'  => __( 'Location / Contact band', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'home_show_contact', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),

						array( 'type' => 'subheading', 'label' => __( 'Column 1 — Project Location', 'manangatang-energy' ) ),
						array( 'key' => 'loc_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Project Location' ),
						array( 'key' => 'loc_text', 'label' => __( 'Text', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'The project is located north of Manangatang in the Mallee region of north-west Victoria.' ),
						array( 'key' => 'loc_map', 'label' => __( 'Map image', 'manangatang-energy' ), 'type' => 'image' ),

						array( 'type' => 'subheading', 'label' => __( 'Column 2 — About the Project', 'manangatang-energy' ) ),
						array( 'key' => 'aboutband_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'text', 'default' => 'About the Project' ),
						array( 'key' => 'aboutband_text', 'label' => __( 'Text', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Manangatang Energy is in the early stages of development. We are undertaking technical studies and community consultation to shape a project that delivers long-term benefits for the region and Victoria.' ),

						array( 'type' => 'subheading', 'label' => __( 'Column 3 — Get in Touch', 'manangatang-energy' ) ),
						array( 'key' => 'getintouch_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Get in Touch' ),
						array( 'key' => 'getintouch_text', 'label' => __( 'Text', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'We welcome your questions and feedback. Please get in touch with the project team.' ),
						array( 'key' => 'cf7_home', 'label' => __( 'Contact form', 'manangatang-energy' ), 'type' => 'cf7' ),
					),
				),
			),
		),
		'about'    => array(
			'label'  => __( 'About the Project', 'manangatang-energy' ),
			'groups' => array(
				array(
					'label'  => __( 'Hero section', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'about_hero_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'The Project' ),
						array( 'key' => 'about_hero_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'A battery for north-west Victoria.' ),
						array( 'key' => 'about_hero_subtitle', 'label' => __( 'Subtitle', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'The Manangatang Battery Energy Storage System (BESS) will store renewable energy generated across the state and release it during peak demand — supporting reliability, lowering prices, and accelerating the retirement of coal-fired generation.' ),
					),
				),
				array(
					'label'  => __( 'Overview & specifications', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'about_show_specs', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'about_narrative', 'label' => __( 'Narrative (blank line = new paragraph)', 'manangatang-energy' ), 'type' => 'textarea', 'default' => "The project is being developed on approximately 5 hectares of cleared agricultural land in the Swan Hill local government area. The site is suitable because of its proximity to high-voltage transmission infrastructure operated by AusNet, minimising the need for new transmission corridors.\n\nThe facility will use modern lithium-iron-phosphate (LFP) battery chemistry, which is widely deployed across Australian grid-scale storage projects. LFP is chosen for its strong safety profile, long cycle life, and absence of cobalt or nickel.\n\nOnce operational, the battery will charge during periods of abundant renewable generation — typically the middle of the day — and discharge during the evening peak. It will also provide essential system services such as frequency control to keep the grid stable." ),
						array( 'type' => 'subheading', 'label' => __( 'Specifications card', 'manangatang-energy' ) ),
						array( 'key' => 'about_specs_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Project Specifications' ),
						array( 'key' => 'about_specs_title', 'label' => __( 'Title', 'manangatang-energy' ), 'type' => 'text', 'default' => 'At a glance' ),
						array(
							'key'       => 'about_specs',
							'label'     => __( 'Spec rows', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add row', 'manangatang-energy' ),
							'default'   => me_default_about_specs(),
							'subfields' => array(
								array( 'key' => 'label', 'type' => 'text', 'label' => __( 'Label', 'manangatang-energy' ) ),
								array( 'key' => 'value', 'type' => 'text', 'label' => __( 'Value', 'manangatang-energy' ) ),
							),
						),
					),
				),
				array(
					'label'  => __( 'Designed for safety', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'about_show_safety', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'about_safety_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Designed for safety' ),
						array( 'key' => 'about_safety_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Built to Australian standards, monitored 24/7.' ),
						array( 'key' => 'about_safety_image', 'label' => __( 'Image', 'manangatang-energy' ), 'type' => 'image' ),
						array(
							'key'       => 'about_safety_items',
							'label'     => __( 'Checklist', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add item', 'manangatang-energy' ),
							'default'   => me_default_about_safety(),
							'subfields' => array(
								array( 'key' => 'icon', 'type' => 'icon', 'label' => __( 'Icon', 'manangatang-energy' ) ),
								array( 'key' => 'text', 'type' => 'text', 'label' => __( 'Text', 'manangatang-energy' ) ),
							),
						),
					),
				),
			),
		),
		'site'     => array(
			'label'  => __( 'The Site', 'manangatang-energy' ),
			'groups' => array(
				array(
					'label'  => __( 'Hero section', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'site_hero_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Where it will be built' ),
						array( 'key' => 'site_hero_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'A purpose-chosen site next to existing transmission.' ),
						array( 'key' => 'site_hero_subtitle', 'label' => __( 'Subtitle', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'The Manangatang site was chosen after detailed grid, environmental, and land-use studies — minimising new infrastructure, avoiding sensitive vegetation, and respecting neighbouring farming activity.' ),
					),
				),
				array(
					'label'  => __( 'Site at a glance', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'site_show_glance', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'siteglance_image', 'label' => __( 'Image', 'manangatang-energy' ), 'type' => 'image' ),
						array(
							'type'   => 'inline',
							'label'  => __( 'Floating badge', 'manangatang-energy' ),
							'fields' => array(
								array( 'key' => 'siteglance_badge_num', 'ph' => __( 'Number', 'manangatang-energy' ), 'default' => '220' ),
								array( 'key' => 'siteglance_badge_unit', 'ph' => __( 'Unit', 'manangatang-energy' ), 'default' => 'kV' ),
								array( 'key' => 'siteglance_badge_label', 'ph' => __( 'Label', 'manangatang-energy' ), 'default' => 'Bendigo–Mildura line' ),
							),
						),
						array( 'key' => 'siteglance_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Site at a glance' ),
						array( 'key' => 'siteglance_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => "Manangatang, in the heart of Victoria's renewables zone." ),
						array( 'key' => 'siteglance_intro', 'label' => __( 'Intro text', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'The site lies within a strategic north-west Victorian energy corridor and benefits from proximity to established network assets. Aerial mapping shows the site boundary, local road context and the alignment of the current 220 kV Bendigo–Mildura line that underpins the location choice.' ),
						array(
							'key'       => 'siteglance_cards',
							'label'     => __( 'Info cards', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add card', 'manangatang-energy' ),
							'default'   => me_default_site_glance_cards(),
							'subfields' => array(
								array( 'key' => 'icon', 'type' => 'icon', 'label' => __( 'Icon', 'manangatang-energy' ) ),
								array( 'key' => 'label', 'type' => 'text', 'label' => __( 'Label', 'manangatang-energy' ) ),
								array( 'key' => 'text', 'type' => 'textarea', 'label' => __( 'Text', 'manangatang-energy' ) ),
							),
						),
					),
				),
				array(
					'label'  => __( 'Full-bleed image band', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'site_show_band', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'siteband_image', 'label' => __( 'Band image', 'manangatang-energy' ), 'type' => 'image' ),
						array(
							'key'       => 'siteband_cards',
							'label'     => __( 'Cards', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add card', 'manangatang-energy' ),
							'default'   => me_default_site_band_cards(),
							'subfields' => array(
								array( 'key' => 'icon', 'type' => 'icon', 'label' => __( 'Icon', 'manangatang-energy' ) ),
								array( 'key' => 'title', 'type' => 'text', 'label' => __( 'Title', 'manangatang-energy' ) ),
								array( 'key' => 'text', 'type' => 'textarea', 'label' => __( 'Text', 'manangatang-energy' ) ),
							),
						),
					),
				),
				array(
					'label'  => __( 'Reference mapping', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'site_show_mapping', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'sitemap_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Reference mapping' ),
						array( 'key' => 'sitemap_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Boundary and network context.' ),
						array(
							'key'       => 'sitemaps',
							'label'     => __( 'Map figures', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add figure', 'manangatang-energy' ),
							'default'   => me_default_site_maps(),
							'subfields' => array(
								array( 'key' => 'image', 'type' => 'image', 'label' => __( 'Image', 'manangatang-energy' ) ),
								array( 'key' => 'caption', 'type' => 'textarea', 'label' => __( 'Caption', 'manangatang-energy' ) ),
							),
						),
					),
				),
			),
		),
		'community' => array(
			'label'  => __( 'Community', 'manangatang-energy' ),
			'groups' => array(
				array(
					'label'  => __( 'Hero section', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'comm_hero_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Have your say' ),
						array( 'key' => 'comm_hero_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Community consultation is open.' ),
						array( 'key' => 'comm_hero_subtitle', 'label' => __( 'Subtitle', 'manangatang-energy' ), 'type' => 'textarea', 'default' => "We're committed to genuine, two-way engagement with the people who live and work near the proposed Manangatang BESS. Here's how to get involved." ),
					),
				),
				array(
					'label'  => __( 'Upcoming sessions', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'comm_show_sessions', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'sessions_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Upcoming sessions' ),
						array( 'key' => 'sessions_title', 'label' => __( 'Title', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Drop in. Ask anything.' ),
						array( 'key' => 'sessions_intro', 'label' => __( 'Intro text', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'No appointment needed. The project team and independent technical experts will be on hand to answer your questions.' ),
						array( 'key' => 'sessions_footnote', 'label' => __( 'Note under sessions (e.g. “Further sessions to be advised”) — leave blank to hide', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Further sessions to be advised.' ),
						array( 'type' => 'subheading', 'label' => __( 'Sessions → managed under the Events menu (latest 3 shown)', 'manangatang-energy' ) ),
					),
				),
				array(
					'label'  => __( 'Image band', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'comm_show_image', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),
						array( 'key' => 'comm_image', 'label' => __( 'Image', 'manangatang-energy' ), 'type' => 'image' ),
					),
				),
				array(
					'label'  => __( 'Submission & engagement', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'comm_show_submission', 'label' => __( 'Show this section', 'manangatang-energy' ), 'type' => 'toggle' ),

						array( 'type' => 'subheading', 'label' => __( 'Submission form', 'manangatang-energy' ) ),
						array( 'key' => 'submit_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Make a submission' ),
						array( 'key' => 'submit_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Share your feedback with the project team.' ),
						array( 'key' => 'submit_text', 'label' => __( 'Text', 'manangatang-energy' ), 'type' => 'textarea', 'default' => "Every submission is read by our community engagement team and logged in the project's consultation register." ),
						array( 'key' => 'cf7_feedback', 'label' => __( 'Contact Form 7 form', 'manangatang-energy' ), 'type' => 'cf7' ),

						array( 'type' => 'subheading', 'label' => __( 'Engagement sidebar', 'manangatang-energy' ) ),
						array( 'key' => 'engage_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Speak with us' ),
						array( 'key' => 'engage_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Community engagement team' ),
						array( 'key' => 'phone', 'label' => __( 'Phone (global)', 'manangatang-energy' ), 'type' => 'text', 'default' => '0427 739 716' ),
						array( 'key' => 'engage_phone_note', 'label' => __( 'Phone note', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Mon–Fri, 9 am – 5 pm AEST' ),
						array( 'key' => 'email', 'label' => __( 'Email (global)', 'manangatang-energy' ), 'type' => 'text', 'default' => 'info@manangatangenergy.com.au' ),
						array( 'key' => 'engage_email_note', 'label' => __( 'Email note', 'manangatang-energy' ), 'type' => 'text', 'default' => 'We respond within 2 business days' ),
						array( 'key' => 'engage_meeting_title', 'label' => __( 'Meeting item — title', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Request a 1-on-1 meeting' ),
						array( 'key' => 'engage_meeting_sub', 'label' => __( 'Meeting item — subtitle', 'manangatang-energy' ), 'type' => 'text', 'default' => 'In person or by phone' ),

						array( 'type' => 'subheading', 'label' => __( 'Community Benefit Fund box', 'manangatang-energy' ) ),
						array( 'key' => 'cbf_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Community Benefit Fund' ),
						array( 'key' => 'cbf_text', 'label' => __( 'Text', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'A locally administered fund delivering ~$150,000 per year for the 25-year operating life of the project, supporting community groups, infrastructure and bushfire-resilience initiatives.' ),
					),
				),
			),
		),
		'news'      => array(
			'label'  => __( 'News', 'manangatang-energy' ),
			'groups' => array(
				array(
					'label'  => __( 'Hero section', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'news_hero_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Newsroom' ),
						array( 'key' => 'news_hero_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'News and project updates.' ),
						array( 'key' => 'news_hero_subtitle', 'label' => __( 'Subtitle', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Milestones, consultation outcomes and announcements from the Manangatang BESS project team.' ),
					),
				),
				array(
					'label'  => __( 'Single article layout', 'manangatang-energy' ),
					'fields' => array(
						array(
							'key'     => 'news_single_layout',
							'type'    => 'layout',
							'label'   => __( 'Choose how each news article page looks', 'manangatang-energy' ),
							'default' => 'editorial',
							'choices' => array(
								array( 'key' => 'editorial', 'label' => __( 'Editorial', 'manangatang-energy' ), 'desc' => __( 'Centered, magazine style', 'manangatang-energy' ) ),
								array( 'key' => 'classic', 'label' => __( 'Classic', 'manangatang-energy' ), 'desc' => __( 'Image on top, left aligned', 'manangatang-energy' ) ),
								array( 'key' => 'cover', 'label' => __( 'Cover', 'manangatang-energy' ), 'desc' => __( 'Full-bleed image, text overlaid', 'manangatang-energy' ) ),
								array( 'key' => 'minimal', 'label' => __( 'Minimal', 'manangatang-energy' ), 'desc' => __( 'Clean, text-first', 'manangatang-energy' ) ),
							),
						),
					),
				),
				array(
					'label'  => __( 'Articles', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'news_per_page', 'label' => __( 'Articles per page (before pagination)', 'manangatang-energy' ), 'type' => 'text', 'default' => '9' ),
						array( 'type' => 'subheading', 'label' => __( 'News articles are added & edited under the “News & Updates” menu (title, content, image, category, date). Only the page hero, layout & per-page count are set here.', 'manangatang-energy' ) ),
					),
				),
			),
		),
		'documents' => array(
			'label'  => __( 'Documents', 'manangatang-energy' ),
			'groups' => array(
				array(
					'label'  => __( 'Hero section', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'docs_hero_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Documents' ),
						array( 'key' => 'docs_hero_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Project documents and reports.' ),
						array( 'key' => 'docs_hero_subtitle', 'label' => __( 'Subtitle', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Technical reports, fact sheets and consultation materials are published here as they become available. All documents are also available in hard copy on request.' ),
					),
				),
				array(
					'label'  => __( 'Documents', 'manangatang-energy' ),
					'fields' => array(
						array( 'type' => 'subheading', 'label' => __( 'Each item has a Category — items are grouped by category, in order', 'manangatang-energy' ) ),
						array(
							'key'       => 'docs_items',
							'label'     => __( 'Documents', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add document', 'manangatang-energy' ),
							'default'   => me_default_documents(),
							'subfields' => array(
								array( 'key' => 'category', 'type' => 'text', 'label' => __( 'Category', 'manangatang-energy' ) ),
								array( 'key' => 'title', 'type' => 'text', 'label' => __( 'Title', 'manangatang-energy' ) ),
								array( 'key' => 'file', 'type' => 'file', 'label' => __( 'File (PDF)', 'manangatang-energy' ) ),
								array( 'key' => 'label', 'type' => 'text', 'label' => __( 'Meta line — leave blank to auto-fill type · size · date from the file', 'manangatang-energy' ) ),
							),
						),
					),
				),
				array(
					'label'  => __( 'Bottom call-to-action', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'docs_cta_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Need something else?' ),
						array( 'key' => 'docs_cta_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Request a hard copy or additional information' ),
						array( 'key' => 'docs_cta_text', 'label' => __( 'Paragraph — use {phone} and {email} to insert clickable contact links', 'manangatang-energy' ), 'type' => 'textarea', 'default' => "Contact our community engagement team on {phone} or email {email} and we'll post you a hard copy of any document at no cost.", 'full' => true ),
					),
				),
			),
		),
		'faqs'     => array(
			'label'  => __( 'FAQs', 'manangatang-energy' ),
			'groups' => array(
				array(
					'label'  => __( 'Hero section', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'faqs_hero_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Knowledge base' ),
						array( 'key' => 'faqs_hero_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Frequently asked questions.' ),
						array( 'key' => 'faqs_hero_subtitle', 'label' => __( 'Subtitle', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Answers to the questions we hear most often from neighbours, councils and community groups.' ),
					),
				),
				array(
					'label'  => __( 'Questions & answers', 'manangatang-energy' ),
					'fields' => array(
						array( 'type' => 'subheading', 'label' => __( 'Each item has a Category — items are grouped by category, in order', 'manangatang-energy' ) ),
						array(
							'key'       => 'faqs_items',
							'label'     => __( 'FAQs', 'manangatang-energy' ),
							'type'      => 'repeater',
							'add_label' => __( 'Add FAQ', 'manangatang-energy' ),
							'default'   => me_default_faqs(),
							'subfields' => array(
								array( 'key' => 'category', 'type' => 'text', 'label' => __( 'Category', 'manangatang-energy' ) ),
								array( 'key' => 'question', 'type' => 'text', 'label' => __( 'Question', 'manangatang-energy' ) ),
								array( 'key' => 'answer', 'type' => 'textarea', 'label' => __( 'Answer', 'manangatang-energy' ) ),
							),
						),
					),
				),
			),
		),
		'contact'  => array(
			'label'  => __( 'Contact', 'manangatang-energy' ),
			'groups' => array(
				array(
					'label'  => __( 'Hero section', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'contact_hero_eyebrow', 'label' => __( 'Eyebrow', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Contact' ),
						array( 'key' => 'contact_hero_heading', 'label' => __( 'Heading', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'Get in touch with the project team' ),
						array( 'key' => 'contact_hero_subtitle', 'label' => __( 'Subtitle', 'manangatang-energy' ), 'type' => 'textarea', 'default' => 'We welcome questions, feedback and meeting requests from community members, councils, consultants and government stakeholders.' ),
					),
				),
				array(
					'label'  => __( 'Contact details', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'contact_info_heading', 'label' => __( 'Card heading', 'manangatang-energy' ), 'type' => 'text', 'default' => get_bloginfo( 'name' ), 'full' => true ),
						array( 'type' => 'subheading', 'label' => __( 'These contact values are shared across the whole site', 'manangatang-energy' ) ),
						array( 'key' => 'phone', 'label' => __( 'Phone (global)', 'manangatang-energy' ), 'type' => 'text', 'default' => '0427 739 716' ),
						array( 'key' => 'email', 'label' => __( 'Email (global)', 'manangatang-energy' ), 'type' => 'text', 'default' => 'info@manangatangenergy.com.au' ),
						array( 'key' => 'address', 'label' => __( 'Location (global)', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Manangatang, Victoria 3546', 'full' => true ),
						array( 'type' => 'subheading', 'label' => __( 'Map image', 'manangatang-energy' ) ),
						array( 'key' => 'contact_map', 'label' => __( 'Map / location image', 'manangatang-energy' ), 'type' => 'image', 'full' => true ),
					),
				),
				array(
					'label'  => __( 'Message form', 'manangatang-energy' ),
					'fields' => array(
						array( 'key' => 'contact_form_heading', 'label' => __( 'Form heading', 'manangatang-energy' ), 'type' => 'text', 'default' => 'Send us a message' ),
						array( 'key' => 'contact_form_subtitle', 'label' => __( 'Form subtitle', 'manangatang-energy' ), 'type' => 'text', 'default' => 'We aim to respond within two business days.' ),
						array( 'key' => 'cf7_contact', 'label' => __( 'Contact Form 7 form', 'manangatang-energy' ), 'type' => 'cf7', 'full' => true ),
					),
				),
			),
		),
	);
}

/**
 * All published Contact Form 7 forms, as id => title.
 *
 * @return array
 */
function me_cf7_forms() {
	if ( ! post_type_exists( 'wpcf7_contact_form' ) ) {
		return array();
	}
	$forms = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	$out = array();
	foreach ( $forms as $f ) {
		$out[ $f->ID ] = $f->post_title ? $f->post_title : ( '#' . $f->ID );
	}
	return $out;
}

/**
 * Add the "Customization" submenu under the plugin's Manangatang menu.
 */
function me_admin_customization_menu() {
	add_submenu_page(
		'mec-settings',                       // Parent (plugin menu).
		__( 'Customization', 'manangatang-energy' ),
		__( 'Customization', 'manangatang-energy' ),
		'manage_options',
		'mec-customization',
		'me_render_customization_page',
		1
	);
}
add_action( 'admin_menu', 'me_admin_customization_menu', 20 );

/**
 * Render a compact list of fields.
 *
 * @param array $fields Field definitions.
 */
/**
 * Tiny CSS mockup of a single-article layout, shown inside the layout picker.
 *
 * @param string $key Layout key.
 * @return string HTML.
 */
function me_layout_mockup( $key ) {
	$g   = '#cfd3d8';
	$gr  = '#1f8f47';
	$img = '#b8c0c7';
	switch ( $key ) {
		case 'classic':
			return '<span style="display:flex;flex-direction:column;height:100%;box-sizing:border-box;padding:10px;gap:5px">'
				. '<span style="display:block;height:36px;background:' . $img . ';border-radius:4px"></span>'
				. '<span style="display:block;height:5px;width:38%;background:' . $gr . ';border-radius:3px;margin-top:2px"></span>'
				. '<span style="display:block;height:8px;width:78%;background:' . $g . ';border-radius:3px"></span>'
				. '<span style="display:block;height:4px;width:100%;background:' . $g . ';border-radius:3px;margin-top:1px"></span>'
				. '<span style="display:block;height:4px;width:94%;background:' . $g . ';border-radius:3px"></span>'
				. '<span style="display:block;height:4px;width:88%;background:' . $g . ';border-radius:3px"></span></span>';
		case 'cover':
			return '<span style="position:relative;display:block;height:100%;box-sizing:border-box">'
				. '<span style="position:absolute;inset:0;background:linear-gradient(160deg,#5b6b60,#2c352f);border-radius:6px"></span>'
				. '<span style="position:absolute;left:11px;right:11px;bottom:30px;display:flex;flex-direction:column;gap:4px">'
				. '<span style="height:5px;width:42%;background:#9be7b4;border-radius:3px"></span>'
				. '<span style="height:8px;width:82%;background:#fff;border-radius:3px"></span>'
				. '<span style="height:8px;width:58%;background:#fff;border-radius:3px"></span></span>'
				. '<span style="position:absolute;left:11px;right:11px;bottom:11px;display:flex;flex-direction:column;gap:3px">'
				. '<span style="height:3px;width:100%;background:rgba(255,255,255,.45);border-radius:2px"></span>'
				. '<span style="height:3px;width:88%;background:rgba(255,255,255,.45);border-radius:2px"></span></span></span>';
		case 'minimal':
			return '<span style="display:flex;flex-direction:column;height:100%;box-sizing:border-box;padding:12px;gap:6px">'
				. '<span style="display:block;height:4px;width:30%;background:' . $gr . ';border-radius:3px"></span>'
				. '<span style="display:block;height:9px;width:82%;background:' . $g . ';border-radius:3px"></span>'
				. '<span style="display:block;height:9px;width:55%;background:' . $g . ';border-radius:3px"></span>'
				. '<span style="display:block;height:1px;width:100%;background:#e2e4e7;margin:3px 0"></span>'
				. '<span style="display:block;height:4px;width:100%;background:' . $g . ';border-radius:3px"></span>'
				. '<span style="display:block;height:4px;width:96%;background:' . $g . ';border-radius:3px"></span>'
				. '<span style="display:block;height:4px;width:90%;background:' . $g . ';border-radius:3px"></span></span>';
		case 'editorial':
		default:
			return '<span style="display:flex;flex-direction:column;align-items:center;height:100%;box-sizing:border-box;padding:10px;gap:5px">'
				. '<span style="display:block;height:5px;width:26px;background:' . $gr . ';border-radius:3px"></span>'
				. '<span style="display:block;height:7px;width:68%;background:' . $g . ';border-radius:3px"></span>'
				. '<span style="display:block;height:7px;width:48%;background:' . $g . ';border-radius:3px;margin-bottom:2px"></span>'
				. '<span style="display:block;height:30px;width:100%;background:' . $img . ';border-radius:4px"></span>'
				. '<span style="display:block;height:4px;width:78%;background:' . $g . ';border-radius:3px;margin-top:2px"></span>'
				. '<span style="display:block;height:4px;width:66%;background:' . $g . ';border-radius:3px"></span></span>';
	}
}

function me_render_fields( $fields ) {
	foreach ( $fields as $f ) {
		$type = $f['type'];
		// Full-width field types span both grid columns; plain text fields pack 2-per-row.
		$full = in_array( $type, array( 'textarea', 'image', 'gallery', 'repeater', 'inline', 'cf7', 'toggle' ), true ) ? ' me-cz-full' : '';

		// Sub-heading divider (groups fields visually, e.g. by column).
		if ( 'subheading' === $type ) {
			echo '<div class="me-cz-sub">' . esc_html( $f['label'] ) . '</div>';
			continue;
		}

		// Image picker (media library).
		if ( 'image' === $type ) {
			$key = $f['key'];
			$val = me_opt( $key, isset( $f['default'] ) ? $f['default'] : '' );
			echo '<div class="me-cz-field' . $full . '"><span class="me-cz-label">' . esc_html( $f['label'] ) . '</span>';
			echo '<div class="me-img me-cz-img"><div class="me-img-preview">';
			if ( $val ) {
				echo '<img src="' . esc_url( $val ) . '" />';
			}
			echo '</div><input type="hidden" name="mec[' . esc_attr( $key ) . ']" class="me-img-url" value="' . esc_attr( $val ) . '" />';
			echo '<span class="me-cz-imgbtns"><button type="button" class="button button-small me-img-select">' . esc_html__( 'Select', 'manangatang-energy' ) . '</button> <button type="button" class="button-link me-img-remove">' . esc_html__( 'Remove', 'manangatang-energy' ) . '</button></span>';
			echo '</div></div>';
			continue;
		}

		// Gallery (multiple images in one grid, stored as a JSON URL list).
		if ( 'gallery' === $type ) {
			$key  = $f['key'];
			$json = me_opt( $key, isset( $f['default'] ) ? wp_json_encode( $f['default'] ) : '[]' );
			echo '<div class="me-cz-field' . $full . '"><span class="me-cz-label">' . esc_html( $f['label'] ) . '</span>';
			echo '<div class="me-gallery"><div class="me-gallery-grid"></div>';
			echo '<input type="hidden" name="mec[' . esc_attr( $key ) . ']" class="me-gallery-data" value="' . esc_attr( $json ) . '" />';
			echo '<button type="button" class="button button-small me-gallery-add">' . esc_html__( 'Select images', 'manangatang-energy' ) . '</button>';
			echo ' <span class="description">' . esc_html__( 'Select multiple — every image shows in the carousel.', 'manangatang-energy' ) . '</span>';
			echo '</div></div>';
			continue;
		}

		// Repeater (add/remove rows of sub-fields, stored as JSON).
		if ( 'repeater' === $type ) {
			$default_json = isset( $f['default'] ) ? wp_json_encode( $f['default'] ) : '[]';
			$json         = me_opt( $f['key'], $default_json );
			echo '<div class="me-cz-field' . $full . '"><span class="me-cz-label">' . esc_html( $f['label'] ) . '</span>';
			printf(
				'<div class="me-rep2" data-fields="%1$s" data-icons="%2$s"><div class="me-rep2-rows"></div><button type="button" class="button button-small me-rep2-add">%3$s</button><input type="hidden" name="mec[%4$s]" class="me-rep2-data" value="%5$s" /></div>',
				esc_attr( wp_json_encode( $f['subfields'] ) ),
				esc_attr( wp_json_encode( me_icon_choices() ) ),
				esc_html( isset( $f['add_label'] ) ? $f['add_label'] : __( 'Add', 'manangatang-energy' ) ),
				esc_attr( $f['key'] ),
				esc_attr( $json )
			);
			echo '</div>';
			continue;
		}

		// Inline composite (e.g. a stat: number + unit + label on one line).
		if ( 'inline' === $type ) {
			echo '<div class="me-cz-field' . $full . '"><span class="me-cz-label">' . esc_html( $f['label'] ) . '</span><div class="me-cz-inline">';
			foreach ( $f['fields'] as $sf ) {
				$sv = me_opt( $sf['key'], isset( $sf['default'] ) ? $sf['default'] : '' );
				printf(
					'<input type="text" name="mec[%1$s]" value="%2$s" placeholder="%3$s" />',
					esc_attr( $sf['key'] ),
					esc_attr( $sv ),
					esc_attr( isset( $sf['ph'] ) ? $sf['ph'] : '' )
				);
			}
			echo '</div></div>';
			continue;
		}

		// Contact Form 7 — dropdown of available forms.
		if ( 'cf7' === $type ) {
			$key   = $f['key'];
			$val   = me_opt( $key, '' );
			$forms = me_cf7_forms();
			echo '<div class="me-cz-field' . $full . '"><label class="me-cz-label" for="mec_' . esc_attr( $key ) . '">' . esc_html( $f['label'] ) . '</label>';
			if ( empty( $forms ) ) {
				echo '<input type="hidden" name="mec[' . esc_attr( $key ) . ']" value="' . esc_attr( $val ) . '" />';
				echo '<p class="description">' . esc_html__( 'No Contact Form 7 forms found — install/activate Contact Form 7 and create a form.', 'manangatang-energy' ) . '</p>';
			} else {
				echo '<select id="mec_' . esc_attr( $key ) . '" name="mec[' . esc_attr( $key ) . ']" class="me-cz-input">';
				echo '<option value="">' . esc_html__( '— Select a form —', 'manangatang-energy' ) . '</option>';
				foreach ( $forms as $id => $title ) {
					printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $id ), selected( (string) $val, (string) $id, false ), esc_html( $title ) );
				}
				echo '</select>';
			}
			echo '</div>';
			continue;
		}

		// Layout picker — selectable cards with a visual preview of each design.
		if ( 'layout' === $type ) {
			$key = $f['key'];
			$val = me_opt( $key, isset( $f['default'] ) ? $f['default'] : '' );
			echo '<div class="me-cz-field me-cz-full"><span class="me-cz-label">' . esc_html( $f['label'] ) . '</span>';
			echo '<div class="me-lay-grid">';
			foreach ( $f['choices'] as $c ) {
				$cid = 'me_lay_' . $key . '_' . $c['key'];
				printf(
					'<label class="me-lay-card" for="%1$s"><input type="radio" id="%1$s" name="mec[%2$s]" value="%3$s" %4$s /><span class="me-lay-prev">%5$s</span><span class="me-lay-meta"><b>%6$s</b><small>%7$s</small></span></label>',
					esc_attr( $cid ),
					esc_attr( $key ),
					esc_attr( $c['key'] ),
					checked( (string) $val, (string) $c['key'], false ),
					me_layout_mockup( $c['key'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted inline mockup markup.
					esc_html( $c['label'] ),
					esc_html( isset( $c['desc'] ) ? $c['desc'] : '' )
				);
			}
			echo '</div></div>';
			continue;
		}

		$key     = $f['key'];
		$default = isset( $f['default'] ) ? $f['default'] : '';
		$value   = me_opt( $key, $default );

		// Toggle as a switch.
		if ( 'toggle' === $type ) {
			printf(
				'<div class="me-cz-field me-cz-toggle me-cz-full"><label class="me-cz-switch"><input type="checkbox" name="mec[%1$s]" value="1" %2$s /><span class="me-cz-track"></span></label><span class="me-cz-toggle-lbl">%3$s</span></div>',
				esc_attr( $key ),
				checked( '0' !== (string) $value, true, false ),
				esc_html( $f['label'] )
			);
			continue;
		}

		echo '<div class="me-cz-field' . $full . '"><label class="me-cz-label" for="mec_' . esc_attr( $key ) . '">' . esc_html( $f['label'] ) . '</label>';
		if ( 'textarea' === $type ) {
			printf( '<textarea id="mec_%1$s" name="mec[%1$s]" rows="2" class="me-cz-input">%2$s</textarea>', esc_attr( $key ), esc_textarea( $value ) );
		} else {
			printf( '<input type="text" id="mec_%1$s" name="mec[%1$s]" value="%2$s" class="me-cz-input" />', esc_attr( $key ), esc_attr( $value ) );
		}
		echo '</div>';
	}
}

/**
 * Save a list of fields into the options array (in place).
 *
 * @param array $fields Field definitions.
 * @param array $opt    Options array (by reference).
 * @param array $in     Submitted values.
 */
function me_save_field_list( $fields, &$opt, $in ) {
	foreach ( $fields as $f ) {
		$type = $f['type'];
		if ( 'subheading' === $type ) {
			continue;
		}
		if ( 'repeater' === $type ) {
			$opt[ $f['key'] ] = me_sanitize_repeater( isset( $in[ $f['key'] ] ) ? $in[ $f['key'] ] : '', $f['subfields'] );
			continue;
		}
		if ( 'image' === $type ) {
			$opt[ $f['key'] ] = isset( $in[ $f['key'] ] ) ? esc_url_raw( $in[ $f['key'] ] ) : '';
			continue;
		}
		if ( 'cf7' === $type ) {
			$opt[ $f['key'] ] = isset( $in[ $f['key'] ] ) ? sanitize_text_field( $in[ $f['key'] ] ) : '';
			continue;
		}
		if ( 'gallery' === $type ) {
			$urls  = json_decode( isset( $in[ $f['key'] ] ) ? $in[ $f['key'] ] : '', true );
			$clean = array();
			if ( is_array( $urls ) ) {
				foreach ( $urls as $u ) {
					$u = esc_url_raw( $u );
					if ( $u ) {
						$clean[] = $u;
					}
				}
			}
			$opt[ $f['key'] ] = wp_json_encode( $clean );
			continue;
		}
		if ( 'inline' === $type ) {
			foreach ( $f['fields'] as $sf ) {
				$sk          = $sf['key'];
				$opt[ $sk ] = isset( $in[ $sk ] ) ? sanitize_text_field( $in[ $sk ] ) : '';
			}
			continue;
		}
		$key = $f['key'];
		if ( 'toggle' === $type ) {
			$opt[ $key ] = isset( $in[ $key ] ) ? '1' : '0';
		} elseif ( 'textarea' === $type ) {
			$opt[ $key ] = isset( $in[ $key ] ) ? sanitize_textarea_field( $in[ $key ] ) : '';
		} else {
			$opt[ $key ] = isset( $in[ $key ] ) ? sanitize_text_field( $in[ $key ] ) : '';
		}
	}
}

/**
 * Render the two-pane customization page.
 */
function me_render_customization_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$schema  = me_page_schema();
	$current = isset( $_GET['pg'] ) ? sanitize_key( $_GET['pg'] ) : 'home';
	if ( ! isset( $schema[ $current ] ) ) {
		$current = 'home';
	}
	$base = admin_url( 'admin.php?page=mec-customization' );
	$page = $schema[ $current ];
	?>
	<style>
		.me-cz-app{display:flex;gap:16px;align-items:flex-start;margin:16px 0 40px}
		.me-cz-nav{flex:0 0 200px;position:sticky;top:46px}
		.me-cz-nav-card{background:#fff;border:1px solid #e2e4e7;border-radius:10px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,.04)}
		.me-cz-nav a{display:block;padding:10px 14px;text-decoration:none;color:#1d2327;font-weight:500;font-size:13px;border-left:3px solid transparent}
		.me-cz-nav a:hover{background:#f6f7f7}
		.me-cz-nav a.active{background:#eafaf0;color:#13682f;font-weight:700;border-left-color:#1f8f47}
		.me-cz-main{flex:1;min-width:0;max-width:760px}
		.me-cz-title{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:0 2px 12px}
		.me-cz-title h2{margin:0;font-size:18px}
		.me-cz-group{border:1px solid #e2e4e7;border-radius:10px;background:#fff;margin-bottom:10px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,.04)}
		.me-cz-group>summary{cursor:pointer;list-style:none;padding:12px 16px;font-weight:600;font-size:13px;display:flex;align-items:center;justify-content:space-between;user-select:none}
		.me-cz-group>summary::-webkit-details-marker{display:none}
		.me-cz-group>summary::after{content:"";width:7px;height:7px;border-right:2px solid #8c8f94;border-bottom:2px solid #8c8f94;transform:rotate(-45deg);transition:transform .2s}
		.me-cz-group[open]>summary{border-bottom:1px solid #eef0f1;color:#13682f}
		.me-cz-group[open]>summary::after{transform:rotate(45deg)}
		.me-cz-group-body{padding:14px 16px;display:grid;grid-template-columns:1fr 1fr;gap:14px 16px;align-items:start}
		.me-cz-flat{border:1px solid #e2e4e7;border-radius:10px;background:#fff;padding:14px 16px;box-shadow:0 1px 2px rgba(0,0,0,.04);display:grid;grid-template-columns:1fr 1fr;gap:14px 16px;align-items:start}
		.me-cz-field{margin:0;min-width:0}
		.me-cz-full{grid-column:1 / -1}
		@media (max-width:1180px){.me-cz-group-body,.me-cz-flat{grid-template-columns:1fr}}
		.me-cz-sub{grid-column:1 / -1;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#13682f;margin:6px 0 0;padding:8px 10px;background:#eafaf0;border-radius:6px}
		.me-cz-label{display:block;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:#646970;margin-bottom:5px}
		.me-cz-input{width:100%;border-radius:6px;border:1px solid #c3c4c7;padding:6px 10px;font-size:13px;box-sizing:border-box}
		textarea.me-cz-input{min-height:54px;line-height:1.5}
		.me-cz-input:focus{border-color:#1f8f47;box-shadow:0 0 0 1px #1f8f47;outline:none}
		.me-cz-inline{display:flex;gap:8px;flex-wrap:wrap}
		.me-cz-inline input{flex:1;min-width:90px;border-radius:6px;border:1px solid #c3c4c7;padding:6px 10px;font-size:13px}
		.me-cz-toggle{display:flex;align-items:center;gap:10px}
		.me-cz-switch{position:relative;display:inline-block;width:38px;height:22px;flex:none}
		.me-cz-switch input{opacity:0;width:0;height:0}
		.me-cz-track{position:absolute;inset:0;background:#c3c4c7;border-radius:22px;transition:.2s}
		.me-cz-track:before{content:"";position:absolute;height:16px;width:16px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.2s}
		.me-cz-switch input:checked+.me-cz-track{background:#1f8f47}
		.me-cz-switch input:checked+.me-cz-track:before{transform:translateX(16px)}
		.me-cz-toggle-lbl{font-size:13px;color:#1d2327}
		.me-cz-img .me-img-preview img{max-width:200px;height:auto;border:1px solid #dcdcde;border-radius:6px;display:block;margin-bottom:6px}
		.me-cz-imgbtns{display:inline-flex;gap:8px;align-items:center}
		.me-rep2-row{border:1px solid #e2e4e7;border-radius:8px;padding:10px;margin-bottom:8px;background:#fbfbfc}
		.me-gallery-grid{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:8px}
		.me-gallery-item{position:relative;width:96px;height:72px;border:1px solid #dcdcde;border-radius:6px;overflow:hidden}
		.me-gallery-item img{width:100%;height:100%;object-fit:cover;display:block}
		.me-gallery-rm{position:absolute;top:3px;right:3px;width:18px;height:18px;line-height:16px;text-align:center;border:0;border-radius:50%;background:rgba(0,0,0,.6);color:#fff;cursor:pointer;font-size:13px;padding:0}
			.me-lay-grid{grid-column:1 / -1;display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
			@media (min-width:900px){.me-lay-grid{grid-template-columns:repeat(4,1fr)}}
			.me-lay-card{position:relative;display:flex;flex-direction:column;gap:8px;border:2px solid #e2e4e7;border-radius:10px;padding:8px;background:#fff;cursor:pointer;transition:border-color .15s,box-shadow .15s}
			.me-lay-card:hover{border-color:#9bd3ad}
			.me-lay-card input{position:absolute;opacity:0;pointer-events:none}
			.me-lay-prev{display:block;height:112px;border-radius:6px;overflow:hidden;background:#f6f7f7;border:1px solid #eef0f1}
			.me-lay-meta{display:flex;flex-direction:column;gap:1px;padding:0 2px 2px}
			.me-lay-meta b{font-size:12px;color:#1d2327}
			.me-lay-meta small{font-size:11px;color:#787c82;line-height:1.3}
			.me-lay-card:has(input:checked){border-color:#1f8f47;box-shadow:0 0 0 3px #eafaf0}
			.me-lay-card:has(input:checked) .me-lay-meta b{color:#13682f}
			.me-lay-card::after{content:"";position:absolute;top:14px;right:14px;width:18px;height:18px;border-radius:50%;background:#1f8f47;opacity:0;transform:scale(.6);transition:.15s;box-shadow:0 1px 3px rgba(0,0,0,.25)}
			.me-lay-card::before{content:"";position:absolute;top:19px;right:20px;width:6px;height:3px;border-left:2px solid #fff;border-bottom:2px solid #fff;transform:rotate(-45deg) scale(.6);opacity:0;transition:.15s;z-index:1}
			.me-lay-card:has(input:checked)::after{opacity:1;transform:scale(1)}
			.me-lay-card:has(input:checked)::before{opacity:1;transform:rotate(-45deg) scale(1)}
	</style>
	<div class="wrap">
		<h1><?php esc_html_e( 'Customization', 'manangatang-energy' ); ?></h1>

		<?php if ( isset( $_GET['updated'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Saved.', 'manangatang-energy' ); ?></p></div>
		<?php endif; ?>

		<div class="me-cz-app">
			<div class="me-cz-nav">
				<div class="me-cz-nav-card">
					<?php foreach ( $schema as $key => $pg ) : ?>
						<a href="<?php echo esc_url( add_query_arg( 'pg', $key, $base ) ); ?>" class="<?php echo $key === $current ? 'active' : ''; ?>"><?php echo esc_html( $pg['label'] ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="me-cz-main">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="mec_save_customization" />
					<input type="hidden" name="mec_pg" value="<?php echo esc_attr( $current ); ?>" />
					<?php wp_nonce_field( 'mec_save_customization', 'mec_cz_nonce' ); ?>

					<div class="me-cz-title">
						<h2><?php echo esc_html( $page['label'] ); ?></h2>
						<?php submit_button( __( 'Save changes', 'manangatang-energy' ), 'primary', 'submit_top', false ); ?>
					</div>

					<?php
					if ( isset( $page['groups'] ) ) {
						foreach ( $page['groups'] as $gi => $grp ) {
							echo '<details class="me-cz-group"' . ( 0 === $gi ? ' open' : '' ) . '><summary>' . esc_html( $grp['label'] ) . '</summary><div class="me-cz-group-body">';
							me_render_fields( $grp['fields'] );
							echo '</div></details>';
						}
					} else {
						echo '<div class="me-cz-flat">';
						me_render_fields( $page['fields'] );
						echo '</div>';
					}
					?>

					<?php submit_button( __( 'Save changes', 'manangatang-energy' ) ); ?>
				</form>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Save handler — merges the submitted page fields into mec_settings.
 */
function me_handle_save_customization() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Permission denied.', 'manangatang-energy' ) );
	}
	check_admin_referer( 'mec_save_customization', 'mec_cz_nonce' );

	$schema = me_page_schema();
	$pg     = isset( $_POST['mec_pg'] ) ? sanitize_key( $_POST['mec_pg'] ) : '';
	if ( ! isset( $schema[ $pg ] ) ) {
		wp_safe_redirect( admin_url( 'admin.php?page=mec-customization' ) );
		exit;
	}

	$opt  = get_option( 'mec_settings', array() );
	$in   = isset( $_POST['mec'] ) && is_array( $_POST['mec'] ) ? wp_unslash( $_POST['mec'] ) : array();
	$page = $schema[ $pg ];

	if ( isset( $page['groups'] ) ) {
		foreach ( $page['groups'] as $grp ) {
			me_save_field_list( $grp['fields'], $opt, $in );
		}
	} else {
		me_save_field_list( $page['fields'], $opt, $in );
	}

	update_option( 'mec_settings', $opt );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'mec-customization', 'pg' => $pg, 'updated' => '1' ),
		admin_url( 'admin.php' )
	) );
	exit;
}
add_action( 'admin_post_mec_save_customization', 'me_handle_save_customization' );

/**
 * Sanitize a JSON repeater value against its sub-field definitions.
 *
 * @param string $json      JSON string.
 * @param array  $subfields Sub-field defs ( key + type ).
 * @return string Clean JSON.
 */
function me_sanitize_repeater( $json, $subfields ) {
	$items = json_decode( $json, true );
	if ( ! is_array( $items ) ) {
		return '';
	}
	$types = array();
	foreach ( (array) $subfields as $sf ) {
		$types[ $sf['key'] ] = isset( $sf['type'] ) ? $sf['type'] : 'text';
	}
	$clean = array();
	foreach ( $items as $it ) {
		if ( ! is_array( $it ) ) {
			continue;
		}
		$row     = array();
		$has_val = false;
		foreach ( $types as $k => $t ) {
			$v = isset( $it[ $k ] ) ? $it[ $k ] : '';
			if ( 'icon' === $t ) {
				$v = me_sanitize_icon( $v );
			} elseif ( 'image' === $t || 'file' === $t ) {
				$v = esc_url_raw( $v );
			} elseif ( 'textarea' === $t ) {
				$v = sanitize_textarea_field( $v );
			} else {
				$v = sanitize_text_field( $v );
			}
			$row[ $k ] = $v;
			if ( '' !== $v && 'icon' !== $t ) {
				$has_val = true;
			}
		}
		if ( $has_val ) {
			$clean[] = $row;
		}
	}
	return wp_json_encode( $clean );
}

/**
 * Enqueue the repeater script on the Customization admin page.
 *
 * @param string $hook Current admin page hook.
 */
function me_customization_admin_assets( $hook ) {
	if ( false === strpos( $hook, 'mec-customization' ) ) {
		return;
	}
	wp_enqueue_media();
	$rep_path = get_template_directory() . '/assets/js/admin-repeater.js';
	$rep_ver  = file_exists( $rep_path ) ? (string) filemtime( $rep_path ) : ME_VERSION;
	wp_enqueue_script(
		'me-admin-repeater',
		get_template_directory_uri() . '/assets/js/admin-repeater.js',
		array( 'jquery' ),
		$rep_ver,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'me_customization_admin_assets' );
