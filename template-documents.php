<?php
/**
 * Template Name: Project — Documents
 *
 * Lists the "me_document" CPT grouped by document category.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$me_all_docs = me_documents();

// ---- Single-document viewer mode: /documents/?docview=<index> ----
$me_view = isset( $_GET['docview'] ) ? (int) $_GET['docview'] : -1;
if ( $me_view >= 0 && isset( $me_all_docs[ $me_view ] ) && ! empty( $me_all_docs[ $me_view ]['file'] ) ) :
	$me_d     = $me_all_docs[ $me_view ];
	$me_file  = $me_d['file'];
	$me_title = isset( $me_d['title'] ) ? $me_d['title'] : '';
	$me_auto  = me_doc_meta( $me_file );
	$me_meta  = $me_auto ? $me_auto : ( isset( $me_d['label'] ) ? $me_d['label'] : '' );
	$me_cat   = isset( $me_d['category'] ) ? $me_d['category'] : '';
	$me_back  = get_permalink();

	// A nice filename for the chrome bar.
	$me_fname = $me_title ? sanitize_title( $me_title ) . '.pdf' : basename( wp_parse_url( $me_file, PHP_URL_PATH ) );

	// Prev / next navigation across documents that actually have a file.
	$me_have = array();
	foreach ( $me_all_docs as $me_k => $me_row ) {
		if ( ! empty( $me_row['file'] ) ) {
			$me_have[] = $me_k;
		}
	}
	$me_pos      = array_search( $me_view, $me_have, true );
	$me_prev_idx = ( false !== $me_pos && $me_pos > 0 ) ? $me_have[ $me_pos - 1 ] : null;
	$me_next_idx = ( false !== $me_pos && $me_pos < count( $me_have ) - 1 ) ? $me_have[ $me_pos + 1 ] : null;
	?>
	<section class="relative overflow-hidden bg-ink-50">
		<div data-depth="0.10" class="hero-grid pointer-events-none absolute inset-0"></div>
		<div data-depth="0.24" class="hero-mesh pointer-events-none absolute inset-0"></div>
		<div data-parallax="-0.05" class="blob pointer-events-none absolute -right-24 top-0 h-72 w-72 bg-grass-100/50 blur-3xl"></div>

		<div class="relative mx-auto max-w-6xl px-4 pb-20 pt-28 sm:px-6 lg:px-8 lg:pb-24 lg:pt-36">

			<!-- Breadcrumb / back -->
			<div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium text-ink-700/55">
				<a href="<?php echo esc_url( $me_back ); ?>" class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 font-semibold text-grass-700 shadow-sm ring-1 ring-ink-900/5 transition hover:text-grass-600"><i data-lucide="arrow-left" class="h-3.5 w-3.5"></i> <?php esc_html_e( 'All documents', 'manangatang-energy' ); ?></a>
				<?php if ( $me_cat ) : ?>
					<i data-lucide="chevron-right" class="h-3.5 w-3.5 text-ink-700/30"></i>
					<span><?php echo esc_html( $me_cat ); ?></span>
				<?php endif; ?>
			</div>

			<!-- Toolbar card -->
			<div class="mt-5 flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/80 p-4 shadow-lg shadow-ink-900/[0.06] ring-1 ring-ink-900/5 backdrop-blur sm:flex-row sm:items-center sm:justify-between sm:p-5">
				<div class="flex min-w-0 items-center gap-4">
					<span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-grass-500 to-grass-700 text-white shadow-sm"><i data-lucide="file-text" class="h-6 w-6"></i></span>
					<div class="min-w-0">
						<h1 class="truncate text-lg font-extrabold tracking-tight text-ink-900 sm:text-xl"><?php echo esc_html( $me_title ); ?></h1>
						<?php if ( $me_meta ) : ?>
							<p class="mt-0.5 flex items-center gap-1.5 text-xs text-ink-700/55"><i data-lucide="info" class="h-3.5 w-3.5"></i> <?php echo esc_html( $me_meta ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<div class="flex shrink-0 items-center gap-2">
					<button type="button" onclick="(function(el){var v=document.getElementById('me-doc-viewer');(v.requestFullscreen||v.webkitRequestFullscreen||function(){}).call(v);})()" class="hidden items-center gap-1.5 rounded-xl border border-ink-100 bg-white px-3.5 py-2.5 text-xs font-bold text-ink-700 shadow-sm transition hover:border-grass-300 hover:text-grass-700 sm:inline-flex"><i data-lucide="maximize-2" class="h-3.5 w-3.5"></i> <?php esc_html_e( 'Fullscreen', 'manangatang-energy' ); ?></button>
					<a href="<?php echo esc_url( $me_file ); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-xl border border-ink-100 bg-white px-3.5 py-2.5 text-xs font-bold text-ink-700 shadow-sm transition hover:border-grass-300 hover:text-grass-700"><i data-lucide="external-link" class="h-3.5 w-3.5"></i> <span class="hidden sm:inline"><?php esc_html_e( 'New tab', 'manangatang-energy' ); ?></span></a>
					<a href="<?php echo esc_url( $me_file ); ?>" download class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-br from-grass-500 to-grass-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm shadow-grass-600/20 transition hover:shadow-md hover:shadow-grass-600/30"><i data-lucide="download" class="h-3.5 w-3.5"></i> <?php esc_html_e( 'Download', 'manangatang-energy' ); ?></a>
				</div>
			</div>

			<!-- Viewer with browser-chrome top bar -->
			<div id="me-doc-viewer" class="group mt-5 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-2xl shadow-ink-900/10">
				<div class="flex items-center gap-3 border-b border-ink-100 bg-gradient-to-b from-ink-50 to-white px-4 py-3">
					<span class="flex items-center gap-1.5">
						<span class="h-3 w-3 rounded-full bg-[#ff5f57]"></span>
						<span class="h-3 w-3 rounded-full bg-[#febc2e]"></span>
						<span class="h-3 w-3 rounded-full bg-[#28c840]"></span>
					</span>
					<span class="mx-auto inline-flex max-w-[60%] items-center gap-1.5 truncate rounded-lg bg-white px-3 py-1 text-xs font-medium text-ink-700/60 ring-1 ring-ink-900/5">
						<i data-lucide="lock" class="h-3 w-3 text-grass-600"></i> <span class="truncate"><?php echo esc_html( $me_fname ); ?></span>
					</span>
					<span class="hidden text-xs font-medium text-ink-700/40 sm:inline"><i data-lucide="file" class="inline h-3.5 w-3.5"></i> PDF</span>
				</div>
				<iframe src="<?php echo esc_url( $me_file ); ?>#view=FitH" title="<?php echo esc_attr( $me_title ); ?>" class="w-full bg-ink-100" style="height:80vh" loading="lazy"></iframe>
			</div>

			<p class="mt-3 flex items-center justify-center gap-1.5 text-center text-xs text-ink-700/45"><i data-lucide="info" class="h-3.5 w-3.5"></i> <?php esc_html_e( 'On some mobile browsers the preview may not load — use Download or New tab to open it.', 'manangatang-energy' ); ?></p>

			<!-- Prev / next navigation -->
			<?php if ( null !== $me_prev_idx || null !== $me_next_idx ) : ?>
				<div class="mt-8 grid gap-3 sm:grid-cols-2">
					<?php if ( null !== $me_prev_idx ) : $me_p = $me_all_docs[ $me_prev_idx ]; ?>
						<a href="<?php echo esc_url( add_query_arg( 'docview', (int) $me_prev_idx, $me_back ) ); ?>" class="group flex items-center gap-3 rounded-2xl border border-ink-100 bg-white p-4 shadow-sm transition hover:border-grass-300 hover:shadow-md">
							<i data-lucide="chevron-left" class="h-5 w-5 shrink-0 text-ink-700/40 transition group-hover:text-grass-600"></i>
							<span class="min-w-0">
								<span class="block text-[11px] font-bold uppercase tracking-wider text-ink-700/45"><?php esc_html_e( 'Previous', 'manangatang-energy' ); ?></span>
								<span class="block truncate text-sm font-bold text-ink-900"><?php echo esc_html( isset( $me_p['title'] ) ? $me_p['title'] : '' ); ?></span>
							</span>
						</a>
					<?php else : ?>
						<span class="hidden sm:block"></span>
					<?php endif; ?>
					<?php if ( null !== $me_next_idx ) : $me_n = $me_all_docs[ $me_next_idx ]; ?>
						<a href="<?php echo esc_url( add_query_arg( 'docview', (int) $me_next_idx, $me_back ) ); ?>" class="group flex items-center justify-end gap-3 rounded-2xl border border-ink-100 bg-white p-4 text-right shadow-sm transition hover:border-grass-300 hover:shadow-md">
							<span class="min-w-0">
								<span class="block text-[11px] font-bold uppercase tracking-wider text-ink-700/45"><?php esc_html_e( 'Next', 'manangatang-energy' ); ?></span>
								<span class="block truncate text-sm font-bold text-ink-900"><?php echo esc_html( isset( $me_n['title'] ) ? $me_n['title'] : '' ); ?></span>
							</span>
							<i data-lucide="chevron-right" class="h-5 w-5 shrink-0 text-ink-700/40 transition group-hover:text-grass-600"></i>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>
	</section>
	<?php
	get_footer();
	return;
endif;

// Group the document items (managed in Customization → Documents) by category.
// Preserve the original index so each item can link to its viewer.
$me_doc_groups = array();
foreach ( $me_all_docs as $me_i => $me_doc ) {
	$cat                     = ! empty( $me_doc['category'] ) ? $me_doc['category'] : 'Documents';
	$me_doc['_index']        = $me_i;
	$me_doc_groups[ $cat ][] = $me_doc;
}
?>

	<!-- ===================== PAGE HERO ===================== -->
	<section id="top" class="relative overflow-hidden bg-ink-50">
		<div data-depth="0.12" class="hero-grid pointer-events-none absolute inset-0"></div>
		<div data-depth="0.28" class="hero-mesh pointer-events-none absolute inset-0"></div>
		<div data-parallax="-0.06" class="blob pointer-events-none absolute -right-24 top-10 h-72 w-72 bg-grass-100/50 blur-3xl"></div>
		<div class="glow-dot pointer-events-none absolute right-[10%] bottom-[16%] hidden h-2.5 w-2.5 rounded-full bg-grass-500 lg:block"></div>
		<div class="particle pointer-events-none absolute right-[16%] top-[32%] hidden h-2 w-2 rounded-full bg-lime-accent lg:block" style="animation-delay:.6s"></div>

		<div class="relative mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-24 lg:pt-44">
			<div class="max-w-2xl">
				<span class="hero-anim d1 inline-block text-xs font-bold uppercase tracking-[0.25em] text-grass-600"><?php echo esc_html( me_opt( 'docs_hero_eyebrow', 'Documents' ) ); ?></span>
				<h1 class="hero-anim d2 mt-4 text-5xl font-extrabold leading-[1.04] tracking-tight h-gradient sm:text-6xl"><?php me_multiline( 'docs_hero_heading', 'Project documents and reports.' ); ?></h1>
				<p class="hero-anim d3 mt-7 max-w-xl text-base leading-relaxed text-ink-700/75 sm:text-lg">
					<?php me_multiline( 'docs_hero_subtitle', 'Technical reports, fact sheets and consultation materials are published here as they become available. All documents are also available in hard copy on request.' ); ?>
				</p>
			</div>
		</div>
	</section>

	<!-- ===================== DOCUMENTS ===================== -->
	<section class="bg-white">
		<div class="mx-auto max-w-4xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24 space-y-14">

			<?php if ( empty( $me_doc_groups ) ) : ?>
				<p class="rounded-2xl border border-ink-100 bg-ink-50 p-8 text-center text-ink-700/70"><?php esc_html_e( 'No documents yet.', 'manangatang-energy' ); ?></p>
			<?php endif; ?>

			<?php foreach ( $me_doc_groups as $me_cat => $me_items ) : ?>
				<div class="reveal">
					<div class="border-b border-ink-100 pb-4">
						<h2 class="text-lg font-extrabold tracking-tight text-ink-900"><?php echo esc_html( $me_cat ); ?></h2>
					</div>
					<ul class="divide-y divide-ink-100">
						<?php
						foreach ( $me_items as $me_d ) :
							$file  = ! empty( $me_d['file'] ) ? $me_d['file'] : '';
							$auto  = $file ? me_doc_meta( $file ) : '';
							$label = $auto ? $auto : ( isset( $me_d['label'] ) ? $me_d['label'] : '' );
							$vhref = $file ? add_query_arg( 'docview', (int) $me_d['_index'], get_permalink() ) : '';
							?>
							<li class="group flex items-center gap-4 rounded-xl px-3 py-4 transition hover:bg-ink-50">
								<span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-grass-50 text-grass-600"><i data-lucide="file-text" class="h-5 w-5"></i></span>
								<div class="min-w-0 flex-1">
									<p class="text-sm font-bold text-ink-900"><?php echo esc_html( isset( $me_d['title'] ) ? $me_d['title'] : '' ); ?></p>
									<?php if ( $label ) : ?>
										<p class="text-xs text-ink-700/55"><?php echo esc_html( $label ); ?></p>
									<?php endif; ?>
								</div>
								<?php if ( $file ) : ?>
									<div class="flex shrink-0 items-center gap-2">
										<a href="<?php echo esc_url( $vhref ); ?>" class="inline-flex items-center gap-1.5 rounded-lg border border-ink-100 bg-white px-3.5 py-2 text-xs font-bold text-ink-900 shadow-sm transition hover:border-grass-300 hover:text-grass-700"><i data-lucide="eye" class="h-3.5 w-3.5"></i> View</a>
										<a href="<?php echo esc_url( $file ); ?>" download target="_blank" rel="noopener" title="<?php esc_attr_e( 'Download', 'manangatang-energy' ); ?>" class="grid h-8 w-8 place-items-center rounded-lg border border-ink-100 bg-white text-ink-700/70 shadow-sm transition hover:border-grass-300 hover:text-grass-700"><i data-lucide="download" class="h-3.5 w-3.5"></i></a>
									</div>
								<?php else : ?>
									<span class="inline-flex shrink-0 items-center rounded-lg border border-ink-100 bg-ink-50 px-3.5 py-2 text-xs font-semibold text-ink-700/40"><?php esc_html_e( 'Coming soon', 'manangatang-energy' ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>

			<!-- CTA -->
			<div class="reveal rounded-3xl border border-ink-100 bg-ink-50 p-8 sm:p-10">
				<span class="text-xs font-bold uppercase tracking-[0.22em] text-grass-600"><?php echo esc_html( me_opt( 'docs_cta_eyebrow', 'Need something else?' ) ); ?></span>
				<h3 class="mt-2 text-xl font-extrabold tracking-tight text-ink-900"><?php echo esc_html( me_opt( 'docs_cta_heading', 'Request a hard copy or additional information' ) ); ?></h3>
				<?php
				$me_cta_text = me_opt( 'docs_cta_text', "Contact our community engagement team on {phone} or email {email} and we'll post you a hard copy of any document at no cost." );
				$me_phone_lnk = '<a href="tel:' . esc_attr( me_tel() ) . '" class="font-semibold text-grass-700 hover:text-grass-600">' . esc_html( me_opt( 'phone' ) ) . '</a>';
				$me_email_lnk = '<a href="mailto:' . esc_attr( me_opt( 'email' ) ) . '" class="font-semibold text-grass-700 hover:text-grass-600">' . esc_html( me_opt( 'email' ) ) . '</a>';
				$me_cta_html  = str_replace(
					array( '{phone}', '{email}' ),
					array( $me_phone_lnk, $me_email_lnk ),
					esc_html( $me_cta_text )
				);
				?>
				<p class="mt-3 max-w-2xl text-sm leading-relaxed text-ink-700/75"><?php echo wp_kses_post( $me_cta_html ); ?></p>
			</div>

		</div>
	</section>

<?php
get_footer();
