<?php
/**
 * Site footer.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$me_img      = get_template_directory_uri() . '/assets/img';
$me_overview = get_page_by_path( 'project-overview' );
$me_site     = get_page_by_path( 'the-site' );
$me_docs     = get_page_by_path( 'documents' );
$me_privacy  = get_page_by_path( 'privacy-policy' );
$me_news_url = get_post_type_archive_link( 'me_news' );
?>
	<!-- ===================== FOOTER ===================== -->
	<footer class="bg-ink-950 text-ink-100">
		<div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
			<div class="grid gap-10 lg:grid-cols-[1.5fr_1fr_1fr]">
				<div>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3">
						<img src="<?php echo esc_url( $me_img ); ?>/logo-mark.png" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-11 w-auto" />
						<span class="leading-none">
							<span class="block text-base font-extrabold tracking-tight text-white">MANANGATANG</span>
							<span class="block text-xs font-semibold tracking-[0.28em] text-grass-400">ENERGY</span>
						</span>
					</a>
					<p class="mt-5 max-w-md text-sm leading-relaxed text-ink-100/60"><?php echo esc_html( me_opt( 'footer_blurb' ) ); ?></p>
				</div>
				<div>
					<h4 class="text-xs font-bold uppercase tracking-[0.2em] text-ink-100/40"><?php esc_html_e( 'Project', 'manangatang-energy' ); ?></h4>
					<?php if ( has_nav_menu( 'footer' ) ) : ?>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'menu_class'     => 'mt-5 space-y-3 text-sm me-footer-menu',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					<?php else : ?>
						<ul class="mt-5 space-y-3 text-sm">
							<li><a href="<?php echo esc_url( $me_overview ? get_permalink( $me_overview ) : '#' ); ?>" class="text-ink-100/75 transition hover:text-grass-400"><?php esc_html_e( 'Overview', 'manangatang-energy' ); ?></a></li>
							<li><a href="<?php echo esc_url( $me_site ? get_permalink( $me_site ) : '#' ); ?>" class="text-ink-100/75 transition hover:text-grass-400"><?php esc_html_e( 'Location', 'manangatang-energy' ); ?></a></li>
							<li><a href="<?php echo esc_url( $me_docs ? get_permalink( $me_docs ) : '#' ); ?>" class="text-ink-100/75 transition hover:text-grass-400"><?php esc_html_e( 'Documents', 'manangatang-energy' ); ?></a></li>
							<li><a href="<?php echo esc_url( $me_news_url ); ?>" class="text-ink-100/75 transition hover:text-grass-400"><?php esc_html_e( 'News', 'manangatang-energy' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>
				<div>
					<h4 class="text-xs font-bold uppercase tracking-[0.2em] text-ink-100/40"><?php esc_html_e( 'Contact', 'manangatang-energy' ); ?></h4>
					<ul class="mt-5 space-y-3 text-sm">
						<?php
						foreach ( me_footer_contacts() as $me_c ) :
							$me_c_icon  = ! empty( $me_c['icon'] ) ? $me_c['icon'] : 'dot';
							$me_c_label = isset( $me_c['label'] ) ? $me_c['label'] : '';
							$me_c_url   = isset( $me_c['url'] ) ? $me_c['url'] : '';
							?>
							<li>
								<?php if ( $me_c_url ) : ?>
									<a href="<?php echo esc_url( $me_c_url ); ?>" class="flex items-center gap-2 text-ink-100/75 transition hover:text-grass-400"><i data-lucide="<?php echo esc_attr( $me_c_icon ); ?>" class="h-4 w-4 text-grass-400"></i> <?php echo esc_html( $me_c_label ); ?></a>
								<?php else : ?>
									<span class="flex items-center gap-2 text-ink-100/75"><i data-lucide="<?php echo esc_attr( $me_c_icon ); ?>" class="h-4 w-4 text-grass-400"></i> <?php echo esc_html( $me_c_label ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="mt-14 flex flex-col gap-3 border-t border-white/10 pt-8 text-xs text-ink-100/50 sm:flex-row sm:items-center sm:justify-between">
				<p><?php echo esc_html( me_opt( 'copyright' ) ); ?> · <a href="<?php echo esc_url( $me_privacy ? get_permalink( $me_privacy ) : '#' ); ?>" class="transition hover:text-grass-400"><?php esc_html_e( 'Privacy Policy', 'manangatang-energy' ); ?></a></p>
				<p><?php echo esc_html( me_opt( 'acknowledgement' ) ); ?></p>
			</div>
		</div>
	</footer>

	<!-- ===================== SCROLL TO TOP ===================== -->
	<button id="toTop" aria-label="Back to top"
		class="group fixed bottom-6 right-6 z-50 grid h-12 w-12 translate-y-4 place-items-center rounded-full bg-ink-900 text-white opacity-0 shadow-xl shadow-ink-900/30 transition-all duration-300 hover:-translate-y-1 hover:bg-grass-600">
		<i data-lucide="arrow-up" class="h-5 w-5 transition group-hover:-translate-y-0.5"></i>
	</button>

	<?php if ( me_show( 'cookie_enable' ) ) : ?>
	<!-- ===================== COOKIE CONSENT ===================== -->
	<div id="cookie" class="fixed inset-x-4 bottom-4 z-50 mx-auto hidden max-w-3xl sm:inset-x-0 sm:bottom-6">
		<div class="flex flex-col gap-4 rounded-3xl border border-ink-100 bg-white p-5 shadow-2xl shadow-ink-900/15 sm:flex-row sm:items-center sm:gap-5 sm:px-7 sm:py-5">
			<div class="flex items-center gap-4">
				<span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-grass-50 text-2xl ring-1 ring-grass-100">🍪</span>
				<div>
					<h4 class="text-base font-extrabold tracking-tight text-ink-900"><?php echo esc_html( me_opt( 'cookie_title', 'We use cookies' ) ); ?></h4>
					<p class="mt-0.5 text-sm leading-relaxed text-ink-700/70">
						<?php echo esc_html( me_opt( 'cookie_text', 'We use cookies to improve your experience, analyse traffic, and personalise content.' ) ); ?>
						<?php
						printf(
							/* translators: %s: privacy policy link */
							esc_html__( 'Read our %s for details.', 'manangatang-energy' ),
							'<a href="' . esc_url( $me_privacy ? get_permalink( $me_privacy ) : '#' ) . '" class="font-semibold text-grass-600 underline underline-offset-2 hover:text-grass-700">' . esc_html__( 'privacy policy', 'manangatang-energy' ) . '</a>'
						);
						?>
					</p>
				</div>
			</div>
			<div class="flex shrink-0 gap-2 sm:ml-auto">
				<button id="cookieDecline" class="flex-1 rounded-full border border-ink-100 bg-white px-5 py-2.5 text-sm font-bold text-ink-900 transition hover:bg-ink-50 sm:flex-none"><?php echo esc_html( me_opt( 'cookie_decline', 'Decline' ) ); ?></button>
				<button id="cookieAccept" class="flex-1 rounded-full bg-gradient-to-r from-grass-500 to-grass-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-grass-600/30 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-grass-600/40 sm:flex-none"><?php echo esc_html( me_opt( 'cookie_accept', 'Accept all' ) ); ?></button>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php wp_footer(); ?>
</body>
</html>
