<?php
/**
 * Single: News article.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$me_img      = get_template_directory_uri() . '/assets/img';
$me_news_url = get_post_type_archive_link( 'me_news' );

while ( have_posts() ) :
	the_post();
	$cat       = me_news_cat( get_the_ID() );
	$subtitle  = get_post_meta( get_the_ID(), '_mec_subtitle', true );
	$read_time = get_post_meta( get_the_ID(), '_mec_read_time', true );
	if ( ! $subtitle ) {
		$subtitle = get_the_excerpt();
	}
	$me_layout = me_opt( 'news_single_layout', 'editorial' );
	// Cover layout needs a featured image; fall back to editorial when missing.
	if ( 'cover' === $me_layout && ! has_post_thumbnail() ) {
		$me_layout = 'editorial';
	}
	?>

	<article>

		<?php if ( 'classic' === $me_layout ) : ?>
			<!-- ===== CLASSIC: image on top, left-aligned ===== -->
			<header class="bg-white">
				<div class="mx-auto max-w-4xl px-4 pt-32 sm:px-6 lg:px-8 lg:pt-40">
					<a href="<?php echo esc_url( $me_news_url ); ?>" class="inline-flex items-center gap-1.5 text-sm font-semibold text-grass-700 transition hover:gap-2.5"><i data-lucide="arrow-left" class="h-4 w-4"></i> <?php echo esc_html( me_opt( 'news_hero_eyebrow', 'Newsroom' ) ); ?></a>
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="reveal mt-6 overflow-hidden rounded-3xl border border-ink-100 shadow-xl shadow-ink-900/10">
							<?php the_post_thumbnail( 'large', array( 'class' => 'aspect-[16/9] w-full object-cover' ) ); ?>
						</figure>
					<?php endif; ?>
					<div class="mt-7 flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-medium text-ink-700/60">
						<?php if ( $cat ) : ?>
							<span class="rounded-full bg-grass-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-grass-700"><?php echo esc_html( $cat ); ?></span>
						<?php endif; ?>
						<span class="flex items-center gap-1.5"><i data-lucide="calendar" class="h-3.5 w-3.5 text-grass-600"></i> <?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
						<?php if ( $read_time ) : ?>
							<span class="text-ink-300">•</span>
							<span class="flex items-center gap-1.5"><i data-lucide="clock" class="h-3.5 w-3.5 text-grass-600"></i> <?php echo esc_html( $read_time ); ?></span>
						<?php endif; ?>
					</div>
					<h1 class="mt-4 text-4xl font-extrabold leading-[1.08] tracking-tight text-ink-900 sm:text-5xl"><?php the_title(); ?></h1>
					<?php if ( $subtitle ) : ?>
						<p class="mt-5 max-w-2xl text-lg leading-relaxed text-ink-700/75"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
			</header>

		<?php elseif ( 'cover' === $me_layout ) : ?>
			<!-- ===== COVER: full-bleed image, text overlaid ===== -->
			<header class="relative isolate overflow-hidden">
				<?php the_post_thumbnail( 'large', array( 'class' => 'absolute inset-0 -z-10 h-full w-full object-cover' ) ); ?>
				<span class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-900/92 via-ink-900/60 to-ink-900/35"></span>
				<div class="mx-auto max-w-4xl px-4 pb-14 pt-36 sm:px-6 lg:px-8 lg:pb-20 lg:pt-48">
					<a href="<?php echo esc_url( $me_news_url ); ?>" class="hero-anim d1 inline-flex items-center gap-1.5 text-sm font-semibold text-white/85 transition hover:gap-2.5"><i data-lucide="arrow-left" class="h-4 w-4"></i> <?php echo esc_html( me_opt( 'news_hero_eyebrow', 'Newsroom' ) ); ?></a>
					<div class="hero-anim d2 mt-8 flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-medium text-white/75">
						<?php if ( $cat ) : ?>
							<span class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-white ring-1 ring-white/25"><?php echo esc_html( $cat ); ?></span>
						<?php endif; ?>
						<span class="flex items-center gap-1.5"><i data-lucide="calendar" class="h-3.5 w-3.5"></i> <?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
						<?php if ( $read_time ) : ?>
							<span class="text-white/40">•</span>
							<span class="flex items-center gap-1.5"><i data-lucide="clock" class="h-3.5 w-3.5"></i> <?php echo esc_html( $read_time ); ?></span>
						<?php endif; ?>
					</div>
					<h1 class="hero-anim d3 mt-5 max-w-3xl text-4xl font-extrabold leading-[1.06] tracking-tight text-white sm:text-6xl"><?php the_title(); ?></h1>
					<?php if ( $subtitle ) : ?>
						<p class="hero-anim d4 mt-6 max-w-2xl text-lg leading-relaxed text-white/85"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
			</header>

		<?php elseif ( 'minimal' === $me_layout ) : ?>
			<!-- ===== MINIMAL: clean, text-first ===== -->
			<header class="bg-white">
				<div class="mx-auto max-w-2xl px-4 pt-32 sm:px-6 lg:px-8 lg:pt-40">
					<a href="<?php echo esc_url( $me_news_url ); ?>" class="inline-flex items-center gap-1.5 text-sm font-semibold text-grass-700 transition hover:gap-2.5"><i data-lucide="arrow-left" class="h-4 w-4"></i> <?php echo esc_html( me_opt( 'news_hero_eyebrow', 'Newsroom' ) ); ?></a>
					<div class="mt-8 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs font-medium text-ink-700/55">
						<?php if ( $cat ) : ?>
							<span class="font-bold uppercase tracking-[0.12em] text-grass-600"><?php echo esc_html( $cat ); ?></span>
							<span class="text-ink-300">•</span>
						<?php endif; ?>
						<span><?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
						<?php if ( $read_time ) : ?>
							<span class="text-ink-300">•</span>
							<span><?php echo esc_html( $read_time ); ?></span>
						<?php endif; ?>
					</div>
					<h1 class="mt-3 text-3xl font-extrabold leading-[1.12] tracking-tight text-ink-900 sm:text-4xl"><?php the_title(); ?></h1>
					<?php if ( $subtitle ) : ?>
						<p class="mt-4 text-lg leading-relaxed text-ink-700/75"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
					<div class="mt-8 h-px w-full bg-ink-100"></div>
				</div>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="mx-auto mt-2 max-w-2xl px-4 sm:px-6 lg:px-8">
						<figure class="overflow-hidden rounded-2xl border border-ink-100">
							<?php the_post_thumbnail( 'large', array( 'class' => 'aspect-[16/9] w-full object-cover' ) ); ?>
						</figure>
					</div>
				<?php endif; ?>
			</header>

		<?php else : ?>
			<!-- ===== EDITORIAL (default): centered, magazine ===== -->
			<header class="relative overflow-hidden bg-ink-50">
				<div data-depth="0.12" class="hero-grid pointer-events-none absolute inset-0"></div>
				<div data-depth="0.28" class="hero-mesh pointer-events-none absolute inset-0"></div>
				<div class="glow-dot pointer-events-none absolute left-[10%] top-[42%] hidden h-2.5 w-2.5 rounded-full bg-grass-500 lg:block"></div>

				<div class="relative mx-auto max-w-3xl px-4 pb-12 pt-36 text-center sm:px-6 lg:px-8 lg:pt-44">
					<a href="<?php echo esc_url( $me_news_url ); ?>" class="hero-anim d1 inline-flex items-center gap-1.5 text-sm font-semibold text-grass-700 transition hover:gap-2.5"><i data-lucide="arrow-left" class="h-4 w-4"></i> <?php echo esc_html( me_opt( 'news_hero_eyebrow', 'Newsroom' ) ); ?></a>

					<div class="hero-anim d2 mt-7 flex flex-wrap items-center justify-center gap-x-3 gap-y-2 text-xs font-medium text-ink-700/60">
						<?php if ( $cat ) : ?>
							<span class="rounded-full bg-grass-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-grass-700"><?php echo esc_html( $cat ); ?></span>
						<?php endif; ?>
						<span class="flex items-center gap-1.5"><i data-lucide="calendar" class="h-3.5 w-3.5 text-grass-600"></i> <?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
						<?php if ( $read_time ) : ?>
							<span class="text-ink-300">•</span>
							<span class="flex items-center gap-1.5"><i data-lucide="clock" class="h-3.5 w-3.5 text-grass-600"></i> <?php echo esc_html( $read_time ); ?></span>
						<?php endif; ?>
					</div>

					<h1 class="hero-anim d3 mt-6 text-4xl font-extrabold leading-[1.08] tracking-tight h-gradient sm:text-5xl"><?php the_title(); ?></h1>
					<?php if ( $subtitle ) : ?>
						<p class="hero-anim d4 mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-ink-700/75"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>

					<div class="hero-anim d5 mt-8 flex items-center justify-center gap-3">
						<img src="<?php echo esc_url( $me_img ); ?>/logo-mark.png" alt="" class="h-9 w-9 rounded-full bg-ink-900 p-1.5" />
						<div class="text-left">
							<p class="text-sm font-bold text-ink-900"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
							<p class="text-xs text-ink-700/55">Project team</p>
						</div>
					</div>
				</div>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="relative bg-gradient-to-b from-ink-50 to-white">
					<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
						<figure class="reveal zoom group overflow-hidden rounded-[2rem] border border-ink-100 shadow-2xl shadow-ink-900/15">
							<?php the_post_thumbnail( 'large', array( 'class' => 'aspect-[16/9] w-full object-cover transition duration-[1.2s] group-hover:scale-105' ) ); ?>
						</figure>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<!-- body -->
		<div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8">
			<div class="mec-prose">
				<?php the_content(); ?>
			</div>

			<!-- share -->
			<div class="mt-12 flex flex-wrap items-center justify-between gap-4 border-t border-ink-100 pt-8">
				<a href="<?php echo esc_url( $me_news_url ); ?>" class="inline-flex items-center gap-1.5 text-sm font-bold text-ink-900 transition hover:text-grass-700"><i data-lucide="arrow-left" class="h-4 w-4 text-grass-600"></i> All updates</a>
				<div class="flex items-center gap-2">
					<span class="mr-1 text-xs font-bold uppercase tracking-[0.15em] text-ink-700/50">Share</span>
					<button onclick="navigator.share?.({title:document.title,url:location.href})||navigator.clipboard?.writeText(location.href)" class="grid h-9 w-9 place-items-center rounded-full border border-ink-100 bg-white text-ink-700 transition hover:border-grass-300 hover:text-grass-700" aria-label="Share"><i data-lucide="share-2" class="h-4 w-4"></i></button>
					<button onclick="navigator.clipboard?.writeText(location.href)" class="grid h-9 w-9 place-items-center rounded-full border border-ink-100 bg-white text-ink-700 transition hover:border-grass-300 hover:text-grass-700" aria-label="Copy link"><i data-lucide="link" class="h-4 w-4"></i></button>
				</div>
			</div>
		</div>

		<!-- more from newsroom -->
		<?php
		$related = new WP_Query(
			array(
				'post_type'           => 'me_news',
				'posts_per_page'      => 2,
				'post__not_in'        => array( get_the_ID() ),
				'ignore_sticky_posts' => true,
			)
		);
		if ( $related->have_posts() ) :
			?>
		<section class="relative overflow-hidden bg-ink-50">
			<div data-parallax="0.05" class="orb slow pointer-events-none absolute -right-16 top-10 h-56 w-56 rounded-full bg-grass-100/50 blur-3xl"></div>
			<div class="relative mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
				<div class="reveal mb-8 flex items-end justify-between gap-4">
					<h2 class="text-2xl font-extrabold tracking-tight text-ink-900 sm:text-3xl">More from the newsroom</h2>
					<a href="<?php echo esc_url( $me_news_url ); ?>" class="hidden shrink-0 items-center gap-1.5 text-sm font-bold text-grass-700 transition hover:gap-2.5 sm:inline-flex">View all <i data-lucide="arrow-right" class="h-4 w-4"></i></a>
				</div>
				<div class="reveal grid gap-5 sm:grid-cols-2">
					<?php
					while ( $related->have_posts() ) :
						$related->the_post();
						$rcat = me_news_cat( get_the_ID() );
						?>
						<a href="<?php the_permalink(); ?>" class="tilt group relative flex flex-col overflow-hidden rounded-3xl border border-ink-100 bg-white p-7 shadow-sm transition hover:shadow-2xl hover:shadow-grass-600/10">
							<div class="flex items-center gap-3">
								<?php if ( $rcat ) : ?>
									<span class="rounded-full bg-grass-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-grass-700"><?php echo esc_html( $rcat ); ?></span>
								<?php endif; ?>
								<span class="text-xs font-medium text-ink-700/55"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
							</div>
							<h3 class="mt-4 text-lg font-bold leading-snug text-ink-900 transition group-hover:text-grass-700"><?php the_title(); ?></h3>
							<span class="mt-5 inline-flex items-center gap-1.5 text-sm font-bold text-ink-900">Read article <i data-lucide="arrow-up-right" class="h-4 w-4 text-grass-600 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i></span>
						</a>
					<?php endwhile; ?>
				</div>
			</div>
		</section>
			<?php
			wp_reset_postdata();
		endif;
		?>
	</article>

	<?php
endwhile;

get_footer();
