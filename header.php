<?php
/**
 * Site header: head, preloader, sticky navigation.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$me_img = get_template_directory_uri() . '/assets/img';
$me_logo = me_opt( 'header_logo' );
if ( ! $me_logo ) {
	$me_logo = $me_img . '/logo-mark.png';
}
$me_btn_text = me_opt( 'header_btn_text', me_opt( 'phone' ) );
$me_btn_link = me_opt( 'header_btn_link', 'tel:' . me_tel() );
$me_btn_icon = me_opt( 'header_btn_icon', 'phone' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link rel="icon" href="<?php echo esc_url( $me_img ); ?>/favicon.ico" sizes="any" />
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $me_img ); ?>/favicon-32x32.png" />
	<link rel="icon" type="image/png" sizes="64x64" href="<?php echo esc_url( $me_img ); ?>/favicon-64x64.png" />
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $me_img ); ?>/apple-touch-icon.png" />
	<meta name="theme-color" content="#0a1c30" />

	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

	<!-- Tailwind theme tokens (read by the browser build) -->
	<style type="text/tailwindcss">
		@theme {
			--font-display: "Plus Jakarta Sans", sans-serif;
			--font-sans: "Inter", sans-serif;
			--color-ink-50:#eef2f7; --color-ink-100:#d6deea; --color-ink-700:#16324d;
			--color-ink-800:#102740; --color-ink-900:#0a1c30; --color-ink-950:#061320;
			--color-grass-50:#effaf2; --color-grass-100:#d6f2de; --color-grass-400:#4cc471;
			--color-grass-500:#2faf59; --color-grass-600:#1f8f47; --color-grass-700:#1a7239;
			--color-lime-accent:#b9d44a;
		}
	</style>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

	<noscript><style>#preloader{display:none!important}body.loading{overflow:auto!important}</style></noscript>

	<?php
	$me_pre_on   = ( '0' !== (string) me_opt( 'preloader_enable', '1' ) );
	$me_pre_logo = me_opt( 'preloader_logo' );
	if ( ! $me_pre_logo ) {
		$me_pre_logo = $me_img . '/logo-mark.png';
	}
	if ( $me_pre_on ) :
		?>
	<!-- ===================== PRELOADER ===================== -->
	<div id="preloader" class="fixed inset-0 z-[100] grid place-items-center bg-ink-950">
		<div class="pointer-events-none absolute h-72 w-72 rounded-full bg-grass-500/20 blur-3xl"></div>
		<div class="relative flex flex-col items-center">
			<div class="relative grid h-28 w-28 place-items-center">
				<svg class="absolute inset-0 h-full w-full -rotate-90" viewBox="0 0 100 100">
					<circle cx="50" cy="50" r="46" fill="none" stroke="rgba(255,255,255,.08)" stroke-width="3" />
					<circle id="preRing" cx="50" cy="50" r="46" fill="none" stroke="#4cc471" stroke-width="3" stroke-linecap="round"
							stroke-dasharray="289" stroke-dashoffset="289" style="filter:drop-shadow(0 0 6px rgba(76,196,113,.7))" />
				</svg>
				<img src="<?php echo esc_url( $me_pre_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-14 w-auto animate-[floatY_2.5s_ease-in-out_infinite]" />
			</div>
			<div class="mt-6 text-center">
				<div class="text-sm font-extrabold uppercase tracking-[0.3em] text-white">Manangatang</div>
				<div class="text-xs font-semibold uppercase tracking-[0.4em] text-grass-400">Energy</div>
			</div>
			<div class="mt-4 text-[11px] font-medium uppercase tracking-[0.25em] text-ink-100/40">
				Loading <span id="preNum">0</span>%
			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- scroll progress -->
	<div id="progress"></div>

	<!-- ===================== NAV ===================== -->
	<header id="nav" class="fixed inset-x-0 top-0 z-50">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<nav class="mt-3 flex items-center justify-between rounded-2xl border border-white/40 bg-white/70 px-4 py-3 shadow-lg shadow-ink-900/5 backdrop-blur-xl transition-all duration-300 sm:px-6">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2.5">
					<img src="<?php echo esc_url( $me_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-10 w-auto" />
					<span class="leading-none">
						<span class="block text-sm font-extrabold tracking-tight text-ink-900">MANANGATANG</span>
						<span class="block text-[11px] font-semibold tracking-[0.28em] text-grass-600">ENERGY</span>
					</span>
				</a>

				<div class="hidden items-center gap-1 lg:flex">
					<?php me_nav_items( 'desktop' ); ?>
				</div>

				<div class="flex items-center gap-2">
					<?php if ( me_show( 'header_show_phone' ) ) : ?>
					<a href="<?php echo esc_url( $me_btn_link ); ?>" class="hidden items-center gap-2 rounded-xl bg-ink-900 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-ink-900/20 transition hover:-translate-y-0.5 hover:bg-ink-800 sm:flex">
						<i data-lucide="<?php echo esc_attr( $me_btn_icon ); ?>" class="h-4 w-4 text-grass-400"></i> <?php echo esc_html( $me_btn_text ); ?>
					</a>
					<?php endif; ?>
					<button id="menuBtn" class="grid h-10 w-10 place-items-center rounded-xl border border-ink-100 bg-white text-ink-900 lg:hidden" aria-label="Open menu">
						<i data-lucide="menu" class="h-5 w-5"></i>
					</button>
				</div>
			</nav>

			<div id="mobileMenu" class="mt-2 hidden rounded-2xl border border-ink-100 bg-white/95 p-2 shadow-xl backdrop-blur-xl lg:hidden">
				<?php me_nav_items( 'mobile' ); ?>
				<?php if ( me_show( 'header_show_phone' ) ) : ?>
				<a href="<?php echo esc_url( $me_btn_link ); ?>" class="mt-1 flex items-center justify-center gap-2 rounded-lg bg-ink-900 px-4 py-3 text-sm font-semibold text-white">
					<i data-lucide="<?php echo esc_attr( $me_btn_icon ); ?>" class="h-4 w-4 text-grass-400"></i> <?php echo esc_html( $me_btn_text ); ?>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</header>
