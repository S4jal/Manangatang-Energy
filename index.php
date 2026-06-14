<?php
/**
 * Generic fallback template (blog, search, archives).
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
		<div class="relative mx-auto max-w-5xl px-4 pb-12 pt-36 sm:px-6 lg:px-8 lg:pt-44">
			<h1 class="hero-anim d2 text-4xl font-extrabold leading-[1.05] tracking-tight h-gradient sm:text-5xl">
				<?php
				if ( is_search() ) {
					/* translators: %s: search query */
					printf( esc_html__( 'Search results for “%s”', 'manangatang-energy' ), esc_html( get_search_query() ) );
				} elseif ( is_archive() ) {
					the_archive_title();
				} else {
					echo esc_html( get_bloginfo( 'name' ) );
				}
				?>
			</h1>
		</div>
	</section>

	<section class="bg-white">
		<div class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
			<?php if ( have_posts() ) : ?>
				<div class="divide-y divide-ink-100 border-t border-ink-100">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article class="group grid gap-2 py-8 sm:grid-cols-[160px_1fr] sm:gap-6">
							<span class="text-sm font-medium text-ink-700/60"><?php echo esc_html( get_the_date() ); ?></span>
							<div>
								<h2 class="text-lg font-bold text-ink-900 transition group-hover:text-grass-700"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p class="mt-1.5 text-sm leading-relaxed text-ink-700/70"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
				<div class="mt-12"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
			<?php else : ?>
				<p class="rounded-2xl border border-ink-100 bg-ink-50 p-8 text-center text-ink-700/70"><?php esc_html_e( 'Nothing found.', 'manangatang-energy' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

<?php
get_footer();
