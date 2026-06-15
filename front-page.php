<?php
/**
 * Front page (homepage).
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$me_img      = get_template_directory_uri() . '/assets/img';
$me_overview = get_page_by_path( 'project-overview' );
$me_community = get_page_by_path( 'community' );
$me_faqs     = get_page_by_path( 'faqs' );
$me_contact  = get_page_by_path( 'contact' );
$me_site     = get_page_by_path( 'the-site' );
$overview_url = $me_overview ? get_permalink( $me_overview ) : '#';
$community_url = $me_community ? get_permalink( $me_community ) : '#';
$faqs_url     = $me_faqs ? get_permalink( $me_faqs ) : '#';
$contact_url  = $me_contact ? get_permalink( $me_contact ) : '#';
$site_url     = $me_site ? get_permalink( $me_site ) : '#';
?>

	<!-- ===================== HERO ===================== -->
	<section id="top" class="relative isolate overflow-hidden">
		<img src="<?php echo esc_url( me_opt( 'home_hero_bg', $me_img . '/site.jpg' ) ); ?>"
			alt="Aerial view of the proposed site near Manangatang, showing land boundary, proposed BESS location and the 220 kV Bendigo–Mildura line"
			class="hero-img absolute inset-0 -z-10 h-full w-full object-cover" />
		<div class="absolute inset-0 -z-10 bg-gradient-to-r from-ink-950/90 via-ink-900/55 to-transparent"></div>
		<div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950/70 via-transparent to-ink-950/30"></div>

		<div data-parallax="-0.12" class="pointer-events-none absolute right-[12%] top-[18%] -z-0 hidden lg:block">
			<div class="orb h-40 w-40 rounded-full bg-gradient-to-br from-grass-400/30 to-lime-accent/10 blur-2xl"></div>
		</div>
		<div data-parallax="0.08" class="pointer-events-none absolute right-[28%] bottom-[16%] -z-0 hidden lg:block">
			<div class="orb alt h-24 w-24 rounded-full border border-grass-400/30 ring-spin"></div>
		</div>
		<div class="particle pointer-events-none absolute left-[20%] top-[30%] h-2 w-2 rounded-full bg-lime-accent" style="animation-delay:.4s"></div>
		<div class="particle pointer-events-none absolute left-[42%] top-[24%] h-1.5 w-1.5 rounded-full bg-grass-300" style="animation-delay:1.2s"></div>
		<div class="particle pointer-events-none absolute left-[55%] bottom-[28%] h-2 w-2 rounded-full bg-grass-400" style="animation-delay:2s"></div>
		<div class="particle pointer-events-none absolute right-[20%] top-[40%] h-1.5 w-1.5 rounded-full bg-lime-accent" style="animation-delay:.8s"></div>

		<div class="mx-auto flex min-h-[92vh] max-w-7xl items-center px-4 pb-20 pt-40 sm:px-6 lg:px-8">
			<div class="max-w-2xl">
				<h1 class="hero-anim d2 text-5xl font-extrabold leading-[1.05] tracking-tight text-white sm:text-6xl lg:text-7xl">
					<?php me_multiline( 'home_hero_heading', "Manangatang\nEnergy" ); ?>
				</h1>
				<p class="hero-anim d3 shimmer-text mt-5 text-2xl font-bold sm:text-3xl">
					<?php me_multiline( 'home_hero_tagline', "Reliable Energy Infrastructure for Victoria's Future" ); ?>
				</p>
				<p class="hero-anim d4 mt-6 max-w-xl text-base leading-relaxed text-ink-100/85 sm:text-lg">
					<?php me_multiline( 'home_hero_intro', "Located near Manangatang in Victoria's Mallee region, the project is exploring utility-scale battery energy storage to support grid reliability and renewable energy integration." ); ?>
				</p>

				<div class="hero-anim d5 mt-9 flex flex-wrap items-center gap-3">
					<a href="<?php echo esc_url( me_opt( 'home_btn1_link', $overview_url ) ); ?>" class="magnetic group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-grass-500 to-grass-600 px-6 py-3.5 text-sm font-bold uppercase tracking-wide text-white shadow-xl shadow-grass-600/30 transition hover:shadow-2xl hover:shadow-grass-600/40">
						<?php echo esc_html( me_opt( 'home_btn1_text', 'Project Overview' ) ); ?>
						<i data-lucide="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1"></i>
					</a>
					<a href="<?php echo esc_url( me_opt( 'home_btn2_link', $community_url ) ); ?>" class="magnetic inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-6 py-3.5 text-sm font-bold uppercase tracking-wide text-white backdrop-blur-md transition hover:bg-white/20">
						<?php echo esc_html( me_opt( 'home_btn2_text', 'Community Information' ) ); ?>
					</a>
				</div>
			</div>
		</div>

		<a href="#about" class="scroll-cue absolute inset-x-0 bottom-10 z-20 mx-auto hidden w-max flex-col items-center gap-1 text-white/70 transition lg:flex">
			<span class="text-[10px] font-semibold uppercase tracking-[0.3em]">Scroll</span>
			<i data-lucide="chevron-down" class="h-5 w-5"></i>
		</a>
	</section>

	<?php if ( me_show( 'home_show_features' ) ) : ?>
	<!-- ===================== FEATURE STRIP ===================== -->
	<section class="relative -mt-px overflow-hidden bg-ink-50">
		<div data-parallax="-0.06" class="blob pointer-events-none absolute -left-16 top-10 h-64 w-64 bg-grass-100/50 blur-2xl"></div>
		<div data-parallax="0.05" class="orb slow pointer-events-none absolute right-10 bottom-8 h-40 w-40 rounded-full bg-lime-accent/15 blur-2xl"></div>
		<div data-depth="0.18" class="pointer-events-none absolute right-0 top-0 h-56 w-56" style="background-image:url('<?php echo esc_url( $me_img ); ?>/dots.svg');background-size:28px 28px;-webkit-mask-image:radial-gradient(circle at 100% 0,#000,transparent 70%);mask-image:radial-gradient(circle at 100% 0,#000,transparent 70%)"></div>

		<div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
			<div class="reveal mb-12 max-w-xl">
				<span class="text-xs font-bold uppercase tracking-[0.25em] text-grass-600"><?php echo esc_html( me_opt( 'home_features_eyebrow', 'Why this site' ) ); ?></span>
				<h2 class="mt-3 text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl"><?php echo esc_html( me_opt( 'home_features_heading', 'Built on the right foundations' ) ); ?></h2>
			</div>

			<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
				<?php
				foreach ( me_home_features() as $me_i => $me_card ) :
					$me_c_icon = ! empty( $me_card['icon'] ) ? $me_card['icon'] : 'zap';
					?>
					<article class="reveal tilt sheen group relative flex h-full flex-col overflow-hidden rounded-3xl border border-ink-100 bg-white p-7 shadow-sm hover:shadow-2xl hover:shadow-grass-600/10">
						<span class="tilt-pop relative grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-ink-800 to-ink-900 text-grass-400 shadow-lg shadow-ink-900/20"><i data-lucide="<?php echo esc_attr( $me_c_icon ); ?>" class="icon-spin h-6 w-6"></i></span>
						<h3 class="relative mt-5 text-base font-bold text-ink-900"><?php echo esc_html( isset( $me_card['title'] ) ? $me_card['title'] : '' ); ?></h3>
						<p class="relative mt-2 text-sm leading-relaxed text-ink-700/70"><?php echo esc_html( isset( $me_card['text'] ) ? $me_card['text'] : '' ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( me_show( 'home_show_site' ) ) : ?>
	<!-- ===================== THE SITE ===================== -->
	<section id="site" class="bg-white">
		<div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
			<div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
				<div class="reveal left">
					<span class="text-xs font-bold uppercase tracking-[0.2em] text-grass-600"><?php echo esc_html( me_opt( 'homesite_eyebrow', 'Overview' ) ); ?></span>
					<h2 class="mt-3 text-4xl font-extrabold tracking-tight text-ink-900 sm:text-5xl"><?php echo esc_html( me_opt( 'homesite_title', 'The Site' ) ); ?></h2>
					<div class="mt-4 h-1 w-16 rounded-full bg-gradient-to-r from-grass-500 to-lime-accent"></div>
					<p class="mt-6 max-w-md text-base leading-relaxed text-ink-700/75">
						<?php me_multiline( 'homesite_intro', 'A suitable location for battery energy storage with existing infrastructure, strong access and minimal impact on surrounding land uses.' ); ?>
					</p>
					<ul class="mt-8 space-y-1">
						<?php
						foreach ( me_homesite_items() as $me_si ) :
							$me_si_icon = ! empty( $me_si['icon'] ) ? $me_si['icon'] : 'check';
							?>
							<li class="group flex items-center gap-4 border-b border-ink-100 py-4 transition hover:pl-2">
								<span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-grass-50 text-grass-600 transition group-hover:bg-grass-500 group-hover:text-white"><i data-lucide="<?php echo esc_attr( $me_si_icon ); ?>" class="h-5 w-5"></i></span>
								<span class="text-sm font-medium text-ink-800"><?php echo esc_html( isset( $me_si['text'] ) ? $me_si['text'] : '' ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo esc_url( me_opt( 'homesite_btn_link', $site_url ) ); ?>" class="group mt-8 inline-flex items-center gap-2 rounded-xl bg-ink-900 px-6 py-3.5 text-sm font-bold uppercase tracking-wide text-white shadow-lg shadow-ink-900/20 transition hover:-translate-y-0.5 hover:bg-ink-800">
						<?php echo esc_html( me_opt( 'homesite_btn_text', 'Learn more about the site' ) ); ?>
						<i data-lucide="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1"></i>
					</a>
				</div>

				<div class="reveal right relative">
					<div data-parallax="-0.05" class="orb pointer-events-none absolute -right-6 -top-6 -z-10 h-28 w-28 rounded-full bg-gradient-to-br from-grass-400/40 to-lime-accent/20 blur-xl"></div>
					<div class="relative overflow-hidden rounded-3xl border border-ink-100 bg-ink-50 shadow-2xl shadow-ink-900/10">
						<div id="slides" class="slides">
							<?php
							foreach ( me_homesite_slides() as $me_sl_url ) :
								if ( empty( $me_sl_url ) ) {
									continue;
								}
								?>
								<div class="slide"><img src="<?php echo esc_url( $me_sl_url ); ?>" alt="<?php echo esc_attr( me_opt( 'homesite_title', 'The Site' ) ); ?>" class="aspect-[4/3] w-full object-cover" /></div>
							<?php endforeach; ?>
						</div>
						<button id="prevBtn" class="absolute left-3 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/85 text-ink-900 shadow-lg backdrop-blur transition hover:bg-white" aria-label="Previous"><i data-lucide="chevron-left" class="h-5 w-5"></i></button>
						<button id="nextBtn" class="absolute right-3 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/85 text-ink-900 shadow-lg backdrop-blur transition hover:bg-white" aria-label="Next"><i data-lucide="chevron-right" class="h-5 w-5"></i></button>
					</div>
					<div class="orb alt glass absolute -bottom-6 -left-6 z-10 rounded-2xl px-5 py-4 shadow-xl shadow-ink-900/10">
						<div class="text-2xl font-extrabold text-ink-900"><?php echo esc_html( me_opt( 'homesite_badge_num', '≈1,200' ) ); ?><span class="text-grass-600"> <?php echo esc_html( me_opt( 'homesite_badge_unit', 'ac' ) ); ?></span></div>
						<div class="text-[11px] font-semibold uppercase tracking-widest text-ink-700/60"><?php echo esc_html( me_opt( 'homesite_badge_label', 'Site area' ) ); ?></div>
					</div>
					<div id="dots" class="mt-5 flex justify-center gap-2"></div>
				</div>
			</div>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( me_show( 'home_show_battery' ) ) : ?>
	<!-- ===================== BATTERY STORAGE EXPLAINED ===================== -->
	<section id="about" class="relative overflow-hidden bg-grass-50">
		<div data-parallax="-0.08" class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-grass-100/60 blur-3xl"></div>
		<div data-parallax="0.1" class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-lime-accent/20 blur-3xl"></div>
		<div data-parallax="0.18" class="pointer-events-none absolute right-[8%] top-[14%] hidden h-3 w-3 rounded-full bg-grass-400 shadow-lg shadow-grass-400/50 lg:block"></div>

		<div class="relative mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
			<div class="reveal mx-auto max-w-2xl text-center">
				<span class="text-xs font-bold uppercase tracking-[0.25em] text-grass-600"><?php echo esc_html( me_opt( 'battery_eyebrow', 'How it works' ) ); ?></span>
				<h2 class="mt-3 text-4xl font-extrabold leading-[1.1] tracking-tight text-ink-900 sm:text-5xl"><?php echo esc_html( me_opt( 'battery_heading', 'Battery Energy Storage Explained' ) ); ?></h2>
				<div class="mx-auto mt-5 h-1 w-16 rounded-full bg-gradient-to-r from-grass-500 to-lime-accent"></div>
				<p class="mx-auto mt-6 max-w-xl text-base leading-relaxed text-ink-700/75">
					<?php me_multiline( 'battery_intro', 'Battery energy storage helps keep electricity reliable by storing energy when supply is abundant and releasing it when needed.' ); ?>
				</p>
			</div>

			<div class="mt-16 flex flex-col gap-6 md:flex-row md:items-stretch md:gap-0">
				<?php
				$me_bsteps  = me_battery_steps();
				$me_bn      = count( $me_bsteps );
				$me_bstyles = array(
					array( 'grad' => 'from-amber-300 to-amber-500', 'ic' => 'text-white', 'sh' => 'shadow-amber-500/40', 'hov' => 'hover:shadow-amber-500/15', 'numhov' => 'group-hover:text-amber-200' ),
					array( 'grad' => 'from-grass-400 to-grass-600', 'ic' => 'text-white', 'sh' => 'shadow-grass-600/40', 'hov' => 'hover:shadow-grass-600/15', 'numhov' => 'group-hover:text-grass-200' ),
					array( 'grad' => 'from-ink-700 to-ink-900', 'ic' => 'text-grass-400', 'sh' => 'shadow-ink-900/40', 'hov' => 'hover:shadow-ink-900/15', 'numhov' => 'group-hover:text-ink-100' ),
				);
				foreach ( $me_bsteps as $me_bi => $me_step ) :
					$me_bs      = $me_bstyles[ $me_bi % 3 ];
					$me_st_icon = ! empty( $me_step['icon'] ) ? $me_step['icon'] : 'zap';
					?>
					<div class="reveal zoom tilt group relative flex-1 rounded-3xl border border-white bg-white/90 p-7 shadow-lg shadow-grass-900/5 backdrop-blur duration-300 hover:shadow-2xl <?php echo esc_attr( $me_bs['hov'] ); ?>">
						<span class="pointer-events-none absolute right-6 top-5 text-6xl font-extrabold leading-none text-grass-100 transition <?php echo esc_attr( $me_bs['numhov'] ); ?>"><?php echo (int) $me_bi + 1; ?></span>
						<span class="relative z-10 grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br <?php echo esc_attr( $me_bs['grad'] . ' ' . $me_bs['ic'] . ' shadow-lg ' . $me_bs['sh'] ); ?> transition group-hover:scale-105">
							<i data-lucide="<?php echo esc_attr( $me_st_icon ); ?>" class="icon-spin h-7 w-7"></i>
						</span>
						<h3 class="mt-6 text-base font-extrabold uppercase tracking-[0.15em] text-ink-900"><?php echo esc_html( isset( $me_step['title'] ) ? $me_step['title'] : '' ); ?></h3>
						<p class="mt-2 text-sm leading-relaxed text-ink-700/70"><?php echo esc_html( isset( $me_step['text'] ) ? $me_step['text'] : '' ); ?></p>
					</div>
					<?php if ( $me_bi < $me_bn - 1 ) : ?>
						<div class="relative mx-2 hidden h-14 w-16 shrink-0 self-center items-center justify-center md:flex lg:w-24">
							<div class="flow-track relative h-1 w-full rounded-full"><span class="flow-dot<?php echo ( $me_bi % 2 ) ? ' d2' : ''; ?>"></span></div>
							<i data-lucide="chevron-right" class="absolute -right-1 h-5 w-5 text-grass-500"></i>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<div class="reveal mt-14 flex justify-center">
				<a href="<?php echo esc_url( me_opt( 'battery_btn_link', $faqs_url ) ); ?>" class="group inline-flex items-center gap-2 rounded-xl bg-ink-900 px-7 py-3.5 text-sm font-bold uppercase tracking-wide text-white shadow-lg shadow-ink-900/20 transition hover:-translate-y-0.5 hover:bg-ink-800">
					<?php echo esc_html( me_opt( 'battery_btn_text', 'How battery storage works' ) ); ?>
					<i data-lucide="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1"></i>
				</a>
			</div>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( me_show( 'home_show_community' ) ) : ?>
	<!-- ===================== COMMUNITY MATTERS ===================== -->
	<section id="community" class="relative overflow-hidden bg-ink-900">
		<img src="<?php echo esc_url( me_opt( 'homecomm_bg', $me_img . '/site-3.webp' ) ); ?>" alt="Mallee farmland" class="absolute inset-y-0 right-0 hidden h-full w-1/2 object-cover lg:block" />
		<div class="absolute inset-y-0 right-0 hidden w-1/2 bg-gradient-to-r from-ink-900 via-ink-900/65 to-transparent lg:block"></div>
		<div class="pointer-events-none absolute -left-20 top-0 h-72 w-72 rounded-full bg-grass-500/10 blur-3xl"></div>

		<div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="grid lg:grid-cols-2">
				<div class="py-16 sm:py-20 lg:py-28 lg:pr-12">
					<span class="text-xs font-bold uppercase tracking-[0.2em] text-grass-400"><?php echo esc_html( me_opt( 'homecomm_eyebrow', 'Engagement' ) ); ?></span>
					<h2 class="reveal mt-3 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl"><?php echo esc_html( me_opt( 'homecomm_heading', 'Community Matters' ) ); ?></h2>
					<div class="mt-4 h-1 w-16 rounded-full bg-gradient-to-r from-grass-400 to-lime-accent"></div>
					<p class="mt-6 max-w-md text-base leading-relaxed text-ink-100/80">
						<?php me_multiline( 'homecomm_text', 'Community consultation is a key part of project development. We are committed to sharing information openly and listening to local feedback.' ); ?>
					</p>
					<div class="mt-9 flex flex-wrap gap-3">
						<a href="<?php echo esc_url( me_opt( 'homecomm_btn1_link', $faqs_url ) ); ?>" class="magnetic inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-bold uppercase tracking-wide text-ink-900 shadow-lg transition hover:bg-grass-50"><?php echo esc_html( me_opt( 'homecomm_btn1_text', 'Frequently Asked Questions' ) ); ?></a>
						<a href="<?php echo esc_url( me_opt( 'homecomm_btn2_link', $contact_url ) ); ?>" class="magnetic inline-flex items-center gap-2 rounded-xl border border-white/40 bg-white/5 px-6 py-3.5 text-sm font-bold uppercase tracking-wide text-white backdrop-blur transition hover:bg-white/15"><?php echo esc_html( me_opt( 'homecomm_btn2_text', 'Contact Us' ) ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( me_show( 'home_show_contact' ) ) : ?>
	<!-- ===================== LOCATION / ABOUT / CONTACT ===================== -->
	<section id="contact" class="relative overflow-hidden bg-ink-50 py-20 lg:py-28">
		<div data-parallax="-0.07" class="blob pointer-events-none absolute -left-20 top-1/3 h-64 w-64 bg-grass-100/50 blur-2xl"></div>
		<div data-parallax="0.06" class="orb slow pointer-events-none absolute -right-10 top-20 h-48 w-48 rounded-full bg-lime-accent/15 blur-2xl"></div>

		<div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="grid gap-10 lg:grid-cols-3 lg:gap-12">
				<div class="reveal">
					<h3 class="text-xl font-extrabold tracking-tight text-ink-900"><?php echo esc_html( me_opt( 'loc_heading', 'Project Location' ) ); ?></h3>
					<p class="mt-3 text-sm leading-relaxed text-ink-700/75"><?php me_multiline( 'loc_text', 'The project is located north of Manangatang in the Mallee region of north-west Victoria.' ); ?></p>
					<div class="reveal zoom group mt-5 overflow-hidden rounded-2xl border border-ink-100 shadow-lg shadow-ink-900/5">
						<img src="<?php echo esc_url( me_opt( 'loc_map', $me_img . '/map.png' ) ); ?>" alt="Satellite map showing the proposed BESS location and approximate land boundary north of Manangatang"
							class="aspect-[16/10] w-full object-cover transition duration-700 group-hover:scale-105" />
					</div>
					<div class="mt-4 space-y-2 text-xs text-ink-700/80">
						<div class="flex items-center gap-2"><svg viewBox="0 0 24 24" fill="#e3342f" class="h-3 w-3 shrink-0" aria-hidden="true"><path d="M12 2l2.95 5.98 6.6.96-4.77 4.65 1.13 6.57L12 17.02 6.09 20.16l1.13-6.57L2.45 8.94l6.6-.96L12 2z"/></svg> Proposed BESS Location</div>
						<div class="flex items-center gap-2"><span class="h-3 w-3 shrink-0 rounded-sm bg-[#e9b949]"></span> Approximate Land Boundary</div>
					</div>
				</div>

				<div class="reveal">
					<h3 class="text-xl font-extrabold tracking-tight text-ink-900"><?php echo esc_html( me_opt( 'aboutband_heading', 'About the Project' ) ); ?></h3>
					<p class="mt-3 text-sm leading-relaxed text-ink-700/75"><?php me_multiline( 'aboutband_text', 'Manangatang Energy is in the early stages of development. We are undertaking technical studies and community consultation to shape a project that delivers long-term benefits for the region and Victoria.' ); ?></p>
					<div class="mt-6 space-y-3">
						<a href="tel:<?php echo esc_attr( me_tel() ); ?>" class="flex items-center gap-3 rounded-xl border border-ink-100 bg-white p-4 text-sm font-semibold text-ink-800 shadow-sm transition hover:border-grass-200 hover:shadow-md"><span class="grid h-9 w-9 place-items-center rounded-lg bg-grass-50 text-grass-600"><i data-lucide="phone" class="h-4 w-4"></i></span> <?php echo esc_html( me_opt( 'phone' ) ); ?></a>
						<a href="mailto:<?php echo esc_attr( me_opt( 'email' ) ); ?>" class="flex items-center gap-3 rounded-xl border border-ink-100 bg-white p-4 text-sm font-semibold text-ink-800 shadow-sm transition hover:border-grass-200 hover:shadow-md"><span class="grid h-9 w-9 place-items-center rounded-lg bg-grass-50 text-grass-600"><i data-lucide="mail" class="h-4 w-4"></i></span> <?php echo esc_html( me_opt( 'email' ) ); ?></a>
					</div>
				</div>

				<div class="reveal">
					<h3 class="text-xl font-extrabold tracking-tight text-ink-900"><?php echo esc_html( me_opt( 'getintouch_heading', 'Get in Touch' ) ); ?></h3>
					<p class="mt-3 text-sm leading-relaxed text-ink-700/75"><?php me_multiline( 'getintouch_text', 'We welcome your questions and feedback. Please get in touch with the project team.' ); ?></p>
					<div class="mt-6">
						<?php me_render_form( 'cf7_home' ); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

<?php
get_footer();
