<?php
/**
 * Template Name: Project — The Site
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$me_img = get_template_directory_uri() . '/assets/img';
?>

	<?php if ( me_show( 'site_show_glance' ) ) : ?>
	<!-- ===================== SITE AT A GLANCE ===================== -->
	<section id="top" class="bg-white">
		<div class="mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-28 lg:pt-44">
			<div class="grid items-start gap-12 lg:grid-cols-2 lg:gap-16">
				<div class="reveal left lg:sticky lg:top-28">
					<div class="relative">
						<div data-parallax="-0.05" class="orb pointer-events-none absolute -left-6 -top-6 -z-10 h-28 w-28 rounded-full bg-gradient-to-br from-grass-400/40 to-lime-accent/20 blur-xl"></div>
						<div class="group overflow-hidden rounded-3xl border border-ink-100 shadow-2xl shadow-ink-900/10">
							<img src="<?php echo esc_url( me_opt( 'siteglance_image', $me_img . '/site-2.jpg' ) ); ?>" alt="Annotated aerial mapping showing the proposed BESS location, site boundary and the 220 kV Bendigo–Mildura line"
								class="aspect-[4/3] w-full object-cover transition duration-700 group-hover:scale-105" />
						</div>
						<div class="orb alt glass absolute -bottom-6 -right-6 z-10 rounded-2xl px-5 py-4 shadow-xl shadow-ink-900/10">
							<div class="text-2xl font-extrabold text-ink-900"><?php echo esc_html( me_opt( 'siteglance_badge_num', '220' ) ); ?><span class="text-grass-600"> <?php echo esc_html( me_opt( 'siteglance_badge_unit', 'kV' ) ); ?></span></div>
							<div class="text-[11px] font-semibold uppercase tracking-widest text-ink-700/60"><?php echo esc_html( me_opt( 'siteglance_badge_label', 'Bendigo–Mildura line' ) ); ?></div>
						</div>
					</div>
				</div>

				<div class="reveal right">
					<span class="text-xs font-bold uppercase tracking-[0.22em] text-grass-600"><?php echo esc_html( me_opt( 'siteglance_eyebrow', 'Site at a glance' ) ); ?></span>
					<h2 class="mt-3 text-3xl font-extrabold leading-[1.12] tracking-tight text-ink-900 sm:text-4xl"><?php echo esc_html( me_opt( 'siteglance_heading', "Manangatang, in the heart of Victoria's renewables zone." ) ); ?></h2>
					<div class="mt-5 h-1 w-16 rounded-full bg-gradient-to-r from-grass-500 to-lime-accent"></div>
					<p class="mt-6 text-base leading-relaxed text-ink-700/75"><?php me_multiline( 'siteglance_intro', 'The site lies within a strategic north-west Victorian energy corridor and benefits from proximity to established network assets. Aerial mapping shows the site boundary, local road context and the alignment of the current 220 kV Bendigo–Mildura line that underpins the location choice.' ); ?></p>

					<div class="mt-8 grid gap-4 sm:grid-cols-2">
						<?php foreach ( me_site_glance_cards() as $me_gc ) : ?>
							<div class="tilt group rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition hover:shadow-xl hover:shadow-grass-600/10">
								<span class="tilt-pop grid h-11 w-11 place-items-center rounded-xl bg-grass-50 text-grass-600 transition group-hover:bg-grass-500 group-hover:text-white"><i data-lucide="<?php echo esc_attr( ! empty( $me_gc['icon'] ) ? $me_gc['icon'] : 'check' ); ?>" class="h-5 w-5"></i></span>
								<h3 class="mt-4 text-xs font-bold uppercase tracking-[0.18em] text-grass-600"><?php echo esc_html( $me_gc['label'] ?? '' ); ?></h3>
								<p class="mt-1.5 text-sm leading-relaxed text-ink-700/80"><?php echo esc_html( $me_gc['text'] ?? '' ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( me_show( 'site_show_band' ) ) : ?>
	<!-- ===================== FULL-BLEED IMAGE BAND ===================== -->
	<section class="relative overflow-hidden bg-ink-50">
		<div data-parallax="0.05" class="orb slow pointer-events-none absolute -right-16 top-16 h-56 w-56 rounded-full bg-grass-100/50 blur-3xl"></div>
		<div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
			<div class="reveal zoom group overflow-hidden rounded-[2rem] border border-ink-100 shadow-2xl shadow-ink-900/10">
				<img src="<?php echo esc_url( me_opt( 'siteband_image', $me_img . '/site-3.webp' ) ); ?>" alt="Wide aerial view of the cleared farmland that makes up the proposed site"
					class="aspect-[21/9] w-full object-cover transition duration-[1.2s] group-hover:scale-105" />
			</div>

			<div class="mt-12 grid gap-px overflow-hidden rounded-3xl border border-ink-100 bg-ink-100 sm:grid-cols-3">
					<?php foreach ( me_site_band_cards() as $me_bc ) : ?>
						<div class="reveal bg-ink-50 p-7">
							<span class="grid h-10 w-10 place-items-center rounded-xl bg-grass-50 text-grass-600"><i data-lucide="<?php echo esc_attr( ! empty( $me_bc['icon'] ) ? $me_bc['icon'] : 'check' ); ?>" class="h-5 w-5"></i></span>
							<h3 class="mt-4 text-base font-bold text-ink-900"><?php echo esc_html( $me_bc['title'] ?? '' ); ?></h3>
							<p class="mt-2 text-sm leading-relaxed text-ink-700/75"><?php echo esc_html( $me_bc['text'] ?? '' ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( me_show( 'site_show_mapping' ) ) : ?>
	<!-- ===================== REFERENCE MAPPING ===================== -->
	<section id="mapping" class="bg-white">
		<div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
			<div class="reveal mb-12 max-w-2xl">
				<span class="text-xs font-bold uppercase tracking-[0.22em] text-grass-600"><?php echo esc_html( me_opt( 'sitemap_eyebrow', 'Reference mapping' ) ); ?></span>
				<h2 class="mt-3 text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl"><?php echo esc_html( me_opt( 'sitemap_heading', 'Boundary and network context.' ) ); ?></h2>
			</div>
			<div class="grid gap-6 lg:grid-cols-2">
					<?php
					$me_maps = array_values( array_filter( me_site_maps(), function ( $m ) { return ! empty( $m['image'] ); } ) );
					$me_mn   = count( $me_maps );
					foreach ( $me_maps as $me_mi => $me_mp ) :
						$me_wide = ( 1 === $me_mn % 2 && $me_mi === $me_mn - 1 );
						$me_span = $me_wide ? ' lg:col-span-2' : '';
						$me_asp  = $me_wide ? 'aspect-[5/2]' : 'aspect-[3/2]';
						?>
						<figure class="reveal group overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-lg shadow-ink-900/5<?php echo esc_attr( $me_span ); ?>">
							<div class="overflow-hidden">
								<img src="<?php echo esc_url( $me_mp['image'] ); ?>" alt="" class="<?php echo esc_attr( $me_asp ); ?> w-full bg-white object-contain p-2 transition duration-700 group-hover:scale-105" />
							</div>
							<figcaption class="p-6 text-sm leading-relaxed text-ink-700/75"><?php echo esc_html( $me_mp['caption'] ?? '' ); ?></figcaption>
						</figure>
					<?php endforeach; ?>
				</div>
		</div>
	</section>
	<?php endif; ?>

<?php
get_footer();
