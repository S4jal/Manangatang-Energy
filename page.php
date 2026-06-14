<?php
/**
 * Default page template (Privacy Policy and other simple pages).
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();
	?>

	<!-- ===================== PAGE HERO ===================== -->
	<section id="top" class="relative overflow-hidden bg-ink-50">
		<div data-depth="0.12" class="hero-grid pointer-events-none absolute inset-0"></div>
		<div data-depth="0.28" class="hero-mesh pointer-events-none absolute inset-0"></div>
		<div data-parallax="-0.06" class="blob pointer-events-none absolute -right-24 top-10 h-72 w-72 bg-grass-100/50 blur-3xl"></div>

		<div class="relative mx-auto max-w-3xl px-4 pb-14 pt-36 sm:px-6 lg:px-8 lg:pb-16 lg:pt-44">
			<span class="hero-anim d1 inline-block text-xs font-bold uppercase tracking-[0.25em] text-grass-600"><?php esc_html_e( 'Manangatang Energy', 'manangatang-energy' ); ?></span>
			<h1 class="hero-anim d2 mt-4 text-4xl font-extrabold leading-[1.05] tracking-tight h-gradient sm:text-5xl"><?php the_title(); ?></h1>
		</div>
	</section>

	<!-- ===================== CONTENT ===================== -->
	<section class="bg-white">
		<div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
			<div class="mec-prose">
				<?php the_content(); ?>
			</div>
		</div>
	</section>

	<?php
endwhile;

get_footer();
