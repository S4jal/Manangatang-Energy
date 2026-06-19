<?php
/**
 * Template Name: Project — Contact
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

		<div class="relative mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-24 lg:pt-44">
			<div class="max-w-3xl">
				<span class="hero-anim d1 inline-block text-xs font-bold uppercase tracking-[0.25em] text-grass-600"><?php echo esc_html( me_opt( 'contact_hero_eyebrow', 'Contact' ) ); ?></span>
				<h1 class="hero-anim d2 mt-4 text-5xl font-extrabold leading-[1.04] tracking-tight h-gradient sm:text-6xl"><?php me_multiline( 'contact_hero_heading', 'Get in touch with the project team' ); ?></h1>
				<p class="hero-anim d3 mt-7 max-w-xl text-base leading-relaxed text-ink-700/75 sm:text-lg">
					<?php me_multiline( 'contact_hero_subtitle', 'We welcome questions, feedback and meeting requests from community members, councils, consultants and government stakeholders.' ); ?>
				</p>
			</div>
		</div>
	</section>

	<!-- ===================== CONTACT ===================== -->
	<section class="bg-white">
		<div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
			<div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
				<div class="reveal left">
					<h2 class="text-3xl font-extrabold tracking-tight text-ink-900"><?php echo esc_html( me_opt( 'contact_info_heading', get_bloginfo( 'name' ) ) ); ?></h2>
					<div class="mt-4 h-1 w-16 rounded-full bg-gradient-to-r from-grass-500 to-lime-accent"></div>
					<ul class="mt-8 space-y-6">
						<li class="flex items-start gap-4">
							<span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-grass-50 text-grass-600"><i data-lucide="phone" class="h-5 w-5"></i></span>
							<div>
								<p class="text-sm font-bold text-ink-900">Phone</p>
								<a href="tel:<?php echo esc_attr( me_tel() ); ?>" class="text-sm text-ink-700/75 transition hover:text-grass-600"><?php echo esc_html( me_opt( 'phone' ) ); ?></a>
							</div>
						</li>
						<li class="flex items-start gap-4">
							<span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-grass-50 text-grass-600"><i data-lucide="mail" class="h-5 w-5"></i></span>
							<div>
								<p class="text-sm font-bold text-ink-900">Email</p>
								<a href="mailto:<?php echo esc_attr( me_opt( 'email' ) ); ?>" class="text-sm text-ink-700/75 transition hover:text-grass-600"><?php echo esc_html( me_opt( 'email' ) ); ?></a>
							</div>
						</li>
						<li class="flex items-start gap-4">
							<span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-grass-50 text-grass-600"><i data-lucide="map-pin" class="h-5 w-5"></i></span>
							<div>
								<p class="text-sm font-bold text-ink-900">Location</p>
								<p class="text-sm text-ink-700/75"><?php echo esc_html( me_opt( 'address' ) ); ?></p>
							</div>
						</li>
					</ul>

					<?php $me_map = me_opt( 'contact_map' ); ?>
					<div class="reveal zoom group mt-8 overflow-hidden rounded-3xl border border-ink-100 shadow-xl shadow-ink-900/10">
						<img src="<?php echo esc_url( $me_map ? $me_map : $me_img . '/map.png' ); ?>" alt="<?php echo esc_attr( me_opt( 'contact_info_heading', get_bloginfo( 'name' ) ) ); ?> — location map" class="aspect-[16/11] w-full object-cover transition duration-700 group-hover:scale-105" />
					</div>
				</div>

				<div class="reveal right">
					<div class="rounded-3xl border border-ink-100 bg-white p-7 shadow-xl shadow-ink-900/5 sm:p-9">
						<h2 class="text-2xl font-extrabold tracking-tight text-ink-900"><?php echo esc_html( me_opt( 'contact_form_heading', 'Send us a message' ) ); ?></h2>
						<p class="mt-2 text-sm text-ink-700/70"><?php echo esc_html( me_opt( 'contact_form_subtitle', 'We aim to respond within two business days.' ) ); ?></p>
						<div class="mt-8">
							<?php me_render_form( 'cf7_contact' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php
get_footer();
