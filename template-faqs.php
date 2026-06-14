<?php
/**
 * Template Name: Project — FAQs
 *
 * Lists the "me_faq" CPT grouped by FAQ category as accordions.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$me_contact = get_page_by_path( 'contact' );
$contact_url = $me_contact ? get_permalink( $me_contact ) : '#';

// Group the FAQ items (managed in Customization → FAQs) by category, in order.
$me_faq_groups = array();
foreach ( me_faqs() as $me_fq ) {
	$cat                       = ! empty( $me_fq['category'] ) ? $me_fq['category'] : 'General';
	$me_faq_groups[ $cat ][]   = $me_fq;
}
?>
	<style>summary::-webkit-details-marker{display:none}summary{list-style:none}</style>

	<!-- ===================== PAGE HERO ===================== -->
	<section id="top" class="relative overflow-hidden bg-ink-50">
		<div data-depth="0.12" class="hero-grid pointer-events-none absolute inset-0"></div>
		<div data-depth="0.28" class="hero-mesh pointer-events-none absolute inset-0"></div>
		<div data-parallax="-0.06" class="blob pointer-events-none absolute -right-24 top-10 h-72 w-72 bg-grass-100/50 blur-3xl"></div>
		<div class="glow-dot pointer-events-none absolute left-[7%] top-[46%] hidden h-2.5 w-2.5 rounded-full bg-grass-500 lg:block"></div>
		<div class="particle pointer-events-none absolute right-[16%] top-[32%] hidden h-2 w-2 rounded-full bg-lime-accent lg:block" style="animation-delay:.6s"></div>

		<div class="relative mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-24 lg:pt-44">
			<div class="max-w-3xl">
				<span class="hero-anim d1 inline-block text-xs font-bold uppercase tracking-[0.25em] text-grass-600"><?php echo esc_html( me_opt( 'faqs_hero_eyebrow', 'Knowledge base' ) ); ?></span>
				<h1 class="hero-anim d2 mt-4 text-5xl font-extrabold leading-[1.04] tracking-tight h-gradient sm:text-6xl"><?php me_multiline( 'faqs_hero_heading', 'Frequently asked questions.' ); ?></h1>
				<p class="hero-anim d3 mt-7 max-w-xl text-base leading-relaxed text-ink-700/75 sm:text-lg">
					<?php me_multiline( 'faqs_hero_subtitle', 'Answers to the questions we hear most often from neighbours, councils and community groups.' ); ?>
					Can't find what you're looking for? <a href="<?php echo esc_url( $contact_url ); ?>" class="font-semibold text-grass-700 underline underline-offset-2 hover:text-grass-600">Get in touch.</a>
				</p>
			</div>
		</div>
	</section>

	<!-- ===================== FAQ GROUPS ===================== -->
	<section class="bg-white">
		<div class="mx-auto max-w-3xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24 space-y-14">

			<?php if ( empty( $me_faq_groups ) ) : ?>
				<p class="rounded-2xl border border-ink-100 bg-ink-50 p-8 text-center text-ink-700/70"><?php esc_html_e( 'No FAQs yet.', 'manangatang-energy' ); ?></p>
			<?php endif; ?>

			<?php foreach ( $me_faq_groups as $me_cat => $me_items ) : $me_first = true; ?>
				<div class="reveal">
					<h2 class="mb-5 text-lg font-extrabold tracking-tight text-ink-900"><?php echo esc_html( $me_cat ); ?></h2>
					<div class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm">
						<?php foreach ( $me_items as $me_item ) : ?>
							<details<?php echo $me_first ? ' open' : ''; ?> class="group px-6">
								<summary class="flex cursor-pointer items-center justify-between gap-4 py-5 text-sm font-bold text-ink-900"><span><?php echo esc_html( isset( $me_item['question'] ) ? $me_item['question'] : '' ); ?></span><i data-lucide="chevron-down" class="h-5 w-5 shrink-0 text-grass-600 transition group-open:rotate-180"></i></summary>
								<div class="-mt-1 pb-5 text-sm leading-relaxed text-ink-700/75"><?php echo wp_kses_post( wpautop( isset( $me_item['answer'] ) ? $me_item['answer'] : '' ) ); ?></div>
							</details>
							<?php $me_first = false; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>

		</div>
	</section>

<?php
get_footer();
