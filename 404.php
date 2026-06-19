<?php
/**
 * 404 — page not found.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

	<section id="top" class="relative overflow-hidden bg-ink-50">
		<div data-depth="0.12" class="hero-grid pointer-events-none absolute inset-0"></div>
		<div data-depth="0.28" class="hero-mesh pointer-events-none absolute inset-0"></div>
		<div data-parallax="-0.06" class="blob pointer-events-none absolute -right-24 top-10 h-72 w-72 bg-grass-100/50 blur-3xl"></div>
		<div class="glow-dot pointer-events-none absolute right-[10%] bottom-[20%] hidden h-2.5 w-2.5 rounded-full bg-grass-500 lg:block"></div>

		<div class="relative mx-auto flex min-h-[80vh] max-w-3xl flex-col items-center justify-center px-4 py-32 text-center sm:px-6 lg:px-8">
			<span class="hero-anim d1 text-8xl font-extrabold tracking-tight h-gradient sm:text-9xl">404</span>
			<h1 class="hero-anim d2 mt-4 text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl"><?php esc_html_e( 'Page not found', 'manangatang-energy' ); ?></h1>
			<p class="hero-anim d3 mx-auto mt-5 max-w-md text-base leading-relaxed text-ink-700/75">
				<?php esc_html_e( 'The page you were looking for may have moved or no longer exists. Let\'s get you back on track.', 'manangatang-energy' ); ?>
			</p>
			<div class="hero-anim d4 mt-9 flex flex-wrap items-center justify-center gap-3">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-grass-500 to-grass-600 px-6 py-3.5 text-sm font-bold uppercase tracking-wide text-white shadow-xl shadow-grass-600/30 transition hover:shadow-2xl hover:shadow-grass-600/40">
					<i data-lucide="home" class="h-4 w-4"></i> <?php esc_html_e( 'Back to home', 'manangatang-energy' ); ?>
				</a>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'me_news' ) ); ?>" class="inline-flex items-center gap-2 rounded-xl border border-ink-100 bg-white px-6 py-3.5 text-sm font-bold uppercase tracking-wide text-ink-900 transition hover:bg-ink-50">
					<?php esc_html_e( 'Latest news', 'manangatang-energy' ); ?>
				</a>
			</div>
		</div>
	</section>

<?php
get_footer();
