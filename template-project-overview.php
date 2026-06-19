<?php
/**
 * Template Name: Project — About the Project
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$me_img = get_template_directory_uri() . '/assets/img';
?>

	<!-- ===================== PAGE HERO ===================== -->
	<section id="top" class="relative overflow-hidden bg-ink-50">
		<div data-depth="0.12" class="hero-grid pointer-events-none absolute inset-0"></div>
		<div data-depth="0.28" class="hero-mesh pointer-events-none absolute inset-0"></div>
		<div data-parallax="-0.06" class="blob pointer-events-none absolute -right-24 top-10 h-72 w-72 bg-grass-100/50 blur-3xl"></div>
		<div class="glow-dot pointer-events-none absolute right-[10%] bottom-[16%] hidden h-2.5 w-2.5 rounded-full bg-grass-500 lg:block"></div>
		<div class="particle pointer-events-none absolute right-[16%] top-[32%] hidden h-2 w-2 rounded-full bg-lime-accent lg:block" style="animation-delay:.6s"></div>
		<div data-parallax="0.05" class="orb slow pointer-events-none absolute right-[20%] bottom-0 h-48 w-48 rounded-full bg-lime-accent/15 blur-2xl"></div>

		<div class="relative mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-24 lg:pt-44">
			<div class="max-w-3xl">
				<span class="hero-anim d1 inline-block text-xs font-bold uppercase tracking-[0.25em] text-grass-600"><?php echo esc_html( me_opt( 'about_hero_eyebrow', 'The Project' ) ); ?></span>
				<h1 class="hero-anim d2 mt-4 text-5xl font-extrabold leading-[1.04] tracking-tight h-gradient sm:text-6xl">
					<?php me_multiline( 'about_hero_heading', 'A battery for north-west Victoria.' ); ?>
				</h1>
				<p class="hero-anim d3 mt-7 max-w-2xl text-base leading-relaxed text-ink-700/75 sm:text-lg">
					<?php me_multiline( 'about_hero_subtitle', 'The Manangatang Battery Energy Storage System (BESS) will store renewable energy generated across the state and release it during peak demand — supporting reliability, lowering prices, and accelerating the retirement of coal-fired generation.' ); ?>
				</p>
			</div>
		</div>
	</section>

	<?php if ( me_show( 'about_show_specs' ) ) : ?>
	<!-- ===================== OVERVIEW + SPECS ===================== -->
	<section id="about" class="bg-white">
		<div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
			<div class="grid gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16">
				<div class="reveal left space-y-6 text-base leading-relaxed text-ink-700/80">
					<?php
					$me_about_narr_default = "The project is being developed on approximately 5 hectares of cleared agricultural land in the Swan Hill local government area. The site is suitable because of its proximity to high-voltage transmission infrastructure operated by AusNet, minimising the need for new transmission corridors.\n\nThe facility will use modern lithium-iron-phosphate (LFP) battery chemistry, which is widely deployed across Australian grid-scale storage projects. LFP is chosen for its strong safety profile, long cycle life, and absence of cobalt or nickel.\n\nOnce operational, the battery will charge during periods of abundant renewable generation — typically the middle of the day — and discharge during the evening peak. It will also provide essential system services such as frequency control to keep the grid stable.";
					foreach ( preg_split( '/\n\s*\n/', trim( me_opt( 'about_narrative', $me_about_narr_default ) ) ) as $me_para ) :
						if ( '' === trim( $me_para ) ) {
							continue;
						}
						?>
						<p><?php echo nl2br( esc_html( trim( $me_para ) ) ); ?></p>
					<?php endforeach; ?>
				</div>

				<div class="reveal right">
					<div class="tilt relative overflow-hidden rounded-3xl border border-ink-100 bg-white p-7 shadow-xl shadow-ink-900/5 sm:p-8">
						<div data-parallax="-0.04" class="orb pointer-events-none absolute -right-8 -top-8 -z-0 h-28 w-28 rounded-full bg-gradient-to-br from-grass-400/25 to-lime-accent/10 blur-xl"></div>
						<div class="relative">
							<span class="text-xs font-bold uppercase tracking-[0.22em] text-grass-600"><?php echo esc_html( me_opt( 'about_specs_eyebrow', 'Project Specifications' ) ); ?></span>
							<h2 class="mt-2 text-2xl font-extrabold tracking-tight text-ink-900"><?php echo esc_html( me_opt( 'about_specs_title', 'At a glance' ) ); ?></h2>
							<dl class="mt-6 divide-y divide-ink-100">
								<?php foreach ( me_about_specs() as $me_sp ) : ?>
									<div class="flex items-center justify-between gap-4 py-3.5"><dt class="text-sm text-ink-700/70"><?php echo esc_html( isset( $me_sp['label'] ) ? $me_sp['label'] : '' ); ?></dt><dd class="text-right text-sm font-bold text-ink-900"><?php echo esc_html( isset( $me_sp['value'] ) ? $me_sp['value'] : '' ); ?></dd></div>
								<?php endforeach; ?>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( me_show( 'about_show_safety' ) ) : ?>
	<!-- ===================== DESIGNED FOR SAFETY ===================== -->
	<section id="safety" class="relative overflow-hidden bg-ink-50">
		<div data-parallax="0.05" class="orb slow pointer-events-none absolute -left-16 top-10 h-56 w-56 rounded-full bg-grass-100/50 blur-3xl"></div>
		<div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
			<div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
				<div class="reveal left relative">
					<div data-parallax="-0.05" class="orb pointer-events-none absolute -left-6 -top-6 -z-10 h-28 w-28 rounded-full bg-gradient-to-br from-grass-400/40 to-lime-accent/20 blur-xl"></div>
					<div class="reveal zoom group overflow-hidden rounded-3xl border border-ink-100 shadow-2xl shadow-ink-900/10">
						<img src="<?php echo esc_url( me_opt( 'about_safety_image', 'https://manangatangenergy.com.au/wp-content/uploads/2026/06/Manangatang-Site.jpg' ) ); ?>" alt="Aerial view of the Manangatang site"
							class="aspect-[5/4] w-full object-cover transition duration-700 group-hover:scale-105" />
					</div>
				</div>
				<div class="reveal right">
					<span class="text-xs font-bold uppercase tracking-[0.22em] text-grass-600"><?php echo esc_html( me_opt( 'about_safety_eyebrow', 'Designed for safety' ) ); ?></span>
					<h2 class="mt-3 text-3xl font-extrabold leading-[1.1] tracking-tight text-ink-900 sm:text-4xl">
						<?php me_multiline( 'about_safety_heading', 'Built to Australian standards, monitored 24/7.' ); ?>
					</h2>
					<div class="mt-5 h-1 w-16 rounded-full bg-gradient-to-r from-grass-500 to-lime-accent"></div>
					<ul class="mt-8 space-y-4">
						<?php foreach ( me_about_safety() as $me_sf ) : ?>
							<li class="flex items-start gap-3"><i data-lucide="<?php echo esc_attr( ! empty( $me_sf['icon'] ) ? $me_sf['icon'] : 'check-circle-2' ); ?>" class="mt-0.5 h-5 w-5 shrink-0 text-grass-500"></i><span class="text-sm leading-relaxed text-ink-700/85"><?php echo esc_html( isset( $me_sf['text'] ) ? $me_sf['text'] : '' ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<?php endif; ?>

<?php
get_footer();
