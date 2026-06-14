<?php
/**
 * Archive: News & Updates.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

// Collect the queried posts so we can feature the first one.
$me_posts = array();
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$me_posts[] = get_the_ID();
	}
}
$me_featured = ! empty( $me_posts ) ? array_shift( $me_posts ) : 0;
?>

	<!-- ===================== PAGE HERO ===================== -->
	<section id="top" class="relative overflow-hidden bg-ink-50">
		<div data-depth="0.12" class="hero-grid pointer-events-none absolute inset-0"></div>
		<div data-depth="0.28" class="hero-mesh pointer-events-none absolute inset-0"></div>
		<div data-parallax="-0.06" class="blob pointer-events-none absolute -right-24 top-10 h-72 w-72 bg-grass-100/50 blur-3xl"></div>
		<div class="glow-dot pointer-events-none absolute left-[7%] top-[46%] hidden h-2.5 w-2.5 rounded-full bg-grass-500 lg:block"></div>
		<div class="particle pointer-events-none absolute right-[16%] top-[32%] hidden h-2 w-2 rounded-full bg-lime-accent lg:block" style="animation-delay:.6s"></div>
		<div data-parallax="0.05" class="orb slow pointer-events-none absolute right-[18%] bottom-0 h-48 w-48 rounded-full bg-lime-accent/15 blur-2xl"></div>

		<div class="relative mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-24 lg:pt-44">
			<div class="max-w-3xl">
				<span class="hero-anim d1 inline-block text-xs font-bold uppercase tracking-[0.25em] text-grass-600"><?php echo esc_html( me_opt( 'news_hero_eyebrow', 'Newsroom' ) ); ?></span>
				<h1 class="hero-anim d2 mt-4 text-5xl font-extrabold leading-[1.04] tracking-tight h-gradient sm:text-6xl">
					<?php me_multiline( 'news_hero_heading', 'News and project updates.' ); ?>
				</h1>
				<p class="hero-anim d3 mt-7 max-w-xl text-base leading-relaxed text-ink-700/75 sm:text-lg">
					<?php me_multiline( 'news_hero_subtitle', 'Milestones, consultation outcomes and announcements from the Manangatang BESS project team.' ); ?>
				</p>
			</div>
		</div>
	</section>

	<!-- ===================== NEWS ===================== -->
	<section id="news-list" class="bg-white">
		<div class="mx-auto max-w-5xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">

			<?php if ( ! $me_featured ) : ?>
				<p class="rounded-2xl border border-ink-100 bg-ink-50 p-8 text-center text-ink-700/70"><?php esc_html_e( 'No news has been published yet. Check back soon.', 'manangatang-energy' ); ?></p>
			<?php else : ?>

				<!-- featured -->
				<a href="<?php echo esc_url( get_permalink( $me_featured ) ); ?>" class="reveal tilt group block overflow-hidden rounded-3xl border border-ink-100 bg-white p-7 shadow-sm transition hover:shadow-2xl hover:shadow-grass-600/10 sm:p-10">
					<div class="flex items-center gap-3">
						<?php $fcat = me_news_cat( $me_featured ); ?>
						<?php if ( $fcat ) : ?>
							<span class="rounded-full bg-grass-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-grass-700"><?php echo esc_html( $fcat ); ?></span>
						<?php endif; ?>
						<span class="text-xs font-medium text-ink-700/60"><?php echo esc_html( get_the_date( 'd M Y', $me_featured ) ); ?></span>
					</div>
					<h2 class="mt-5 max-w-2xl text-2xl font-extrabold leading-snug tracking-tight text-ink-900 transition group-hover:text-grass-700 sm:text-3xl">
						<?php echo esc_html( get_the_title( $me_featured ) ); ?>
					</h2>
					<p class="mt-4 max-w-2xl text-base leading-relaxed text-ink-700/75">
						<?php echo esc_html( wp_trim_words( get_the_excerpt( $me_featured ), 40 ) ); ?>
					</p>
					<span class="mt-6 inline-flex items-center gap-1.5 text-sm font-bold text-ink-900">
						Read more <i data-lucide="arrow-up-right" class="h-4 w-4 text-grass-600 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
					</span>
				</a>

				<!-- list -->
				<?php if ( ! empty( $me_posts ) ) : ?>
				<div class="mt-12 divide-y divide-ink-100 border-t border-ink-100">
					<?php foreach ( $me_posts as $pid ) : $cat = me_news_cat( $pid ); ?>
						<a href="<?php echo esc_url( get_permalink( $pid ) ); ?>" class="group grid gap-3 py-8 transition sm:grid-cols-[150px_140px_1fr_auto] sm:items-start sm:gap-6">
							<span class="text-sm font-medium text-ink-700/60"><?php echo esc_html( get_the_date( 'd F Y', $pid ) ); ?></span>
							<span class="text-xs font-bold uppercase tracking-[0.12em] text-grass-600"><?php echo esc_html( $cat ); ?></span>
							<div>
								<h3 class="text-base font-bold text-ink-900 transition group-hover:text-grass-700"><?php echo esc_html( get_the_title( $pid ) ); ?></h3>
								<p class="mt-1.5 text-sm leading-relaxed text-ink-700/70"><?php echo esc_html( wp_trim_words( get_the_excerpt( $pid ), 28 ) ); ?></p>
							</div>
							<i data-lucide="arrow-up-right" class="hidden h-5 w-5 text-ink-700/40 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-grass-600 sm:block"></i>
						</a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<div class="mt-12"><?php the_posts_pagination( array( 'mid_size' => 1, 'class' => 'text-sm' ) ); ?></div>

			<?php endif; ?>
		</div>
	</section>

<?php
get_footer();
