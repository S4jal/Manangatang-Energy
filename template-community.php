<?php
/**
 * Template Name: Project — Community
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$me_img = get_template_directory_uri() . '/assets/img';
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
				<span class="hero-anim d1 inline-block text-xs font-bold uppercase tracking-[0.25em] text-grass-600"><?php echo esc_html( me_opt( 'comm_hero_eyebrow', 'Have your say' ) ); ?></span>
				<h1 class="hero-anim d2 mt-4 text-5xl font-extrabold leading-[1.04] tracking-tight h-gradient sm:text-6xl">
					<?php me_multiline( 'comm_hero_heading', 'Community consultation is open.' ); ?>
				</h1>
				<p class="hero-anim d3 mt-7 max-w-xl text-base leading-relaxed text-ink-700/75 sm:text-lg">
					<?php me_multiline( 'comm_hero_subtitle', "We're committed to genuine, two-way engagement with the people who live and work near the proposed Manangatang BESS. Here's how to get involved." ); ?>
				</p>
			</div>
		</div>
	</section>

	<?php if ( me_show( 'comm_show_sessions' ) ) : ?>
	<!-- ===================== UPCOMING SESSIONS ===================== -->
	<section id="sessions" class="bg-white">
		<div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
			<div class="reveal flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
				<div>
					<span class="text-xs font-bold uppercase tracking-[0.22em] text-grass-600"><?php echo esc_html( me_opt( 'sessions_eyebrow', 'Upcoming sessions' ) ); ?></span>
					<h2 class="mt-3 text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl"><?php echo esc_html( me_opt( 'sessions_title', 'Drop in. Ask anything.' ) ); ?></h2>
				</div>
				<p class="max-w-sm text-sm leading-relaxed text-ink-700/70"><?php me_multiline( 'sessions_intro', 'No appointment needed. The project team and independent technical experts will be on hand to answer your questions.' ); ?></p>
			</div>

			<div class="mt-10 grid gap-5 md:grid-cols-3">
					<?php foreach ( me_community_events() as $me_se ) : ?>
						<button type="button" class="session-card tilt sheen reveal group relative w-full overflow-hidden rounded-3xl border border-ink-100 bg-white p-6 text-left shadow-sm transition hover:shadow-2xl hover:shadow-grass-600/10"
							data-date="<?php echo esc_attr( $me_se['date'] ?? '' ); ?>" data-time="<?php echo esc_attr( $me_se['time'] ?? '' ); ?>" data-venue="<?php echo esc_attr( $me_se['venue'] ?? '' ); ?>" data-address="<?php echo esc_attr( $me_se['address'] ?? '' ); ?>" data-directions="<?php echo esc_url( $me_se['directions'] ?? '' ); ?>" data-expect="<?php echo esc_attr( implode( "\n", $me_se['expect'] ?? array() ) ); ?>">
							<span class="tilt-pop grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-ink-800 to-ink-900 text-grass-400 shadow-lg shadow-ink-900/20"><i data-lucide="calendar" class="h-5 w-5"></i></span>
							<p class="mt-5 text-sm font-bold text-grass-600"><?php echo esc_html( $me_se['date'] ?? '' ); ?></p>
							<p class="mt-1 text-sm font-semibold text-ink-800"><?php echo esc_html( $me_se['time'] ?? '' ); ?></p>
							<h3 class="mt-4 text-base font-bold text-ink-900"><?php echo esc_html( $me_se['venue'] ?? '' ); ?></h3>
							<p class="mt-1 flex items-center gap-1.5 text-sm text-ink-700/70"><i data-lucide="map-pin" class="h-4 w-4 text-grass-500"></i> <?php echo esc_html( $me_se['address'] ?? '' ); ?></p>
							<span class="mt-5 inline-flex items-center gap-1.5 text-sm font-bold text-grass-700">View details <i data-lucide="arrow-up-right" class="h-4 w-4 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i></span>
						</button>
					<?php endforeach; ?>
				</div>

				<?php
				$me_sessions_note = me_opt( 'sessions_footnote', 'Further sessions to be advised.' );
				if ( '' !== trim( $me_sessions_note ) ) :
					?>
					<p class="reveal mt-8 inline-flex items-center gap-2 rounded-full bg-grass-50 px-4 py-2 text-sm font-semibold text-grass-700"><i data-lucide="calendar-clock" class="h-4 w-4"></i> <?php echo esc_html( $me_sessions_note ); ?></p>
				<?php endif; ?>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( me_show( 'comm_show_image' ) ) : ?>
	<!-- ===================== FULL-BLEED IMAGE BAND ===================== -->
	<section class="relative isolate overflow-hidden bg-ink-900">
		<img src="<?php echo esc_url( me_opt( 'comm_image', $me_img . '/community-session.jpg' ) ); ?>" alt="Local residents gathered at a community consultation session in a town hall"
			class="h-[55vh] min-h-[380px] w-full object-cover" />
		<div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-ink-950/40 via-transparent to-transparent"></div>
	</section>

	<?php endif; ?>

	<?php if ( me_show( 'comm_show_submission' ) ) : ?>
	<!-- ===================== SUBMISSION + ENGAGEMENT ===================== -->
	<section id="submit" class="relative overflow-hidden bg-ink-50">
		<div data-parallax="0.05" class="orb slow pointer-events-none absolute -left-16 top-16 h-56 w-56 rounded-full bg-grass-100/50 blur-3xl"></div>
		<div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
			<div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:gap-12">
				<div class="reveal left rounded-3xl border border-ink-100 bg-white p-7 shadow-xl shadow-ink-900/5 sm:p-9">
					<span class="text-xs font-bold uppercase tracking-[0.22em] text-grass-600"><?php echo esc_html( me_opt( 'submit_eyebrow', 'Make a submission' ) ); ?></span>
					<h2 class="mt-3 text-2xl font-extrabold tracking-tight text-ink-900 sm:text-3xl"><?php echo esc_html( me_opt( 'submit_heading', 'Share your feedback with the project team.' ) ); ?></h2>
					<p class="mt-4 text-sm leading-relaxed text-ink-700/75"><?php me_multiline( 'submit_text', "Every submission is read by our community engagement team and logged in the project's consultation register." ); ?></p>
					<div class="mt-8">
						<?php me_render_form( 'cf7_feedback' ); ?>
					</div>
				</div>

				<div class="reveal right space-y-6">
					<div class="rounded-3xl border border-ink-100 bg-white p-7 shadow-xl shadow-ink-900/5">
						<span class="text-xs font-bold uppercase tracking-[0.22em] text-grass-600"><?php echo esc_html( me_opt( 'engage_eyebrow', 'Speak with us' ) ); ?></span>
						<h3 class="mt-2 text-xl font-extrabold tracking-tight text-ink-900"><?php echo esc_html( me_opt( 'engage_heading', 'Community engagement team' ) ); ?></h3>
						<ul class="mt-6 space-y-5">
							<li class="flex items-start gap-3">
								<span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-grass-50 text-grass-600"><i data-lucide="phone" class="h-5 w-5"></i></span>
								<div>
									<a href="tel:<?php echo esc_attr( me_tel() ); ?>" class="text-sm font-bold text-ink-900 transition hover:text-grass-600"><?php echo esc_html( me_opt( 'phone' ) ); ?></a>
									<p class="text-xs text-ink-700/60"><?php echo esc_html( me_opt( 'engage_phone_note', 'Mon–Fri, 9 am – 5 pm AEST' ) ); ?></p>
								</div>
							</li>
							<li class="flex items-start gap-3">
								<span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-grass-50 text-grass-600"><i data-lucide="mail" class="h-5 w-5"></i></span>
								<div>
									<a href="mailto:<?php echo esc_attr( me_opt( 'email' ) ); ?>" class="text-sm font-bold text-ink-900 transition hover:text-grass-600"><?php echo esc_html( me_opt( 'email' ) ); ?></a>
									<p class="text-xs text-ink-700/60"><?php echo esc_html( me_opt( 'engage_email_note', 'We respond within 2 business days' ) ); ?></p>
								</div>
							</li>
							<li class="flex items-start gap-3">
								<span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-grass-50 text-grass-600"><i data-lucide="message-square" class="h-5 w-5"></i></span>
								<div>
									<p class="text-sm font-bold text-ink-900"><?php echo esc_html( me_opt( 'engage_meeting_title', 'Request a 1-on-1 meeting' ) ); ?></p>
									<p class="text-xs text-ink-700/60"><?php echo esc_html( me_opt( 'engage_meeting_sub', 'In person or by phone' ) ); ?></p>
								</div>
							</li>
						</ul>
					</div>

					<?php
					$me_cbf_eyebrow = me_opt( 'cbf_eyebrow', 'Community Benefit Fund' );
					$me_cbf_text    = me_opt( 'cbf_text', 'A locally administered fund delivering ~$150,000 per year for the 25-year operating life of the project, supporting community groups, infrastructure and bushfire-resilience initiatives.' );
					if ( '' !== trim( $me_cbf_eyebrow ) || '' !== trim( $me_cbf_text ) ) :
						?>
						<div class="rounded-3xl border border-grass-100 bg-grass-50 p-7">
							<?php if ( '' !== trim( $me_cbf_eyebrow ) ) : ?>
								<span class="text-xs font-bold uppercase tracking-[0.22em] text-grass-700"><?php echo esc_html( $me_cbf_eyebrow ); ?></span>
							<?php endif; ?>
							<?php if ( '' !== trim( $me_cbf_text ) ) : ?>
								<p class="mt-3 text-sm leading-relaxed text-ink-700/80"><?php echo nl2br( esc_html( $me_cbf_text ) ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<?php endif; ?>

	<!-- ===================== SESSION DETAILS MODAL ===================== -->
	<div id="sessionModal" class="fixed inset-0 z-[90] hidden">
		<div id="smBackdrop" class="absolute inset-0 bg-ink-950/60 opacity-0 backdrop-blur-sm transition-opacity duration-300"></div>
		<div class="absolute inset-0 grid place-items-center p-4">
			<div id="smCard" class="relative w-full max-w-md translate-y-4 scale-95 overflow-hidden rounded-3xl border border-ink-100 bg-white opacity-0 shadow-2xl shadow-ink-950/30 transition-all duration-300">
				<div class="relative overflow-hidden bg-gradient-to-br from-ink-800 to-ink-950 px-7 pb-6 pt-7">
					<div class="pointer-events-none absolute -right-8 -top-8 h-28 w-28 rounded-full bg-grass-500/20 blur-2xl"></div>
					<button id="smClose" class="absolute right-4 top-4 grid h-9 w-9 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20" aria-label="Close"><i data-lucide="x" class="h-5 w-5"></i></button>
					<span class="grid h-11 w-11 place-items-center rounded-xl bg-white/10 text-grass-400"><i data-lucide="calendar-days" class="h-5 w-5"></i></span>
					<p class="mt-4 text-xs font-bold uppercase tracking-[0.2em] text-grass-400">Drop-in session</p>
					<h3 id="smVenue" class="mt-1 text-xl font-extrabold tracking-tight text-white">Venue</h3>
				</div>
				<div class="px-7 py-6">
					<div class="space-y-4">
						<div class="flex items-start gap-3"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-grass-50 text-grass-600"><i data-lucide="calendar" class="h-4 w-4"></i></span><div><p class="text-[11px] font-bold uppercase tracking-[0.14em] text-ink-700/50">Date</p><p id="smDate" class="text-sm font-bold text-ink-900">—</p></div></div>
						<div class="flex items-start gap-3"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-grass-50 text-grass-600"><i data-lucide="clock" class="h-4 w-4"></i></span><div><p class="text-[11px] font-bold uppercase tracking-[0.14em] text-ink-700/50">Time</p><p id="smTime" class="text-sm font-bold text-ink-900">—</p></div></div>
						<div class="flex items-start gap-3"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-grass-50 text-grass-600"><i data-lucide="map-pin" class="h-4 w-4"></i></span><div><p class="text-[11px] font-bold uppercase tracking-[0.14em] text-ink-700/50">Address</p><p id="smAddr" class="text-sm font-bold text-ink-900">—</p></div></div>
					</div>
					<div id="smExpectBox" class="mt-6 rounded-2xl bg-ink-50 p-5">
						<p class="text-xs font-bold uppercase tracking-[0.14em] text-grass-700">What to expect</p>
						<ul id="smExpect" class="mt-3 space-y-2"></ul>
					</div>
					<div class="mt-6 flex flex-col gap-2.5 sm:flex-row">
						<a id="smDirections" href="#" target="_blank" rel="noopener" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-ink-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-ink-800"><i data-lucide="navigation" class="h-4 w-4 text-grass-400"></i> Get directions</a>
						<a href="#submit" id="smSubmit" class="flex flex-1 items-center justify-center gap-2 rounded-xl border border-ink-100 px-5 py-3 text-sm font-bold text-ink-900 transition hover:bg-ink-50">Can't make it?</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
	(function () {
		var modal = document.getElementById('sessionModal');
		if (!modal) return;
		var backdrop = document.getElementById('smBackdrop');
		var card = document.getElementById('smCard');
		var els = {
			venue: document.getElementById('smVenue'),
			date: document.getElementById('smDate'),
			time: document.getElementById('smTime'),
			addr: document.getElementById('smAddr'),
			dir: document.getElementById('smDirections')
		};
		var lastFocus = null;
		function open(data) {
			els.venue.textContent = data.venue;
			els.date.textContent = data.date;
			els.time.textContent = data.time;
			els.addr.textContent = data.address;
			els.dir.href = data.directions ? data.directions : ('https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(data.venue + ', ' + data.address));
			var exp = document.getElementById('smExpect');
			var expBox = document.getElementById('smExpectBox');
			var items = (data.expect || '').split('\n').filter(function (t) { return t.trim() !== ''; });
			if (exp && items.length) {
				exp.innerHTML = items.map(function (t) {
					return '<li class="flex items-start gap-2.5 text-sm leading-relaxed text-ink-700/80"><i data-lucide="check" class="mt-0.5 h-4 w-4 shrink-0 text-grass-500"></i> ' + t.replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</li>';
				}).join('');
				if (expBox) expBox.style.display = '';
				if (window.lucide) lucide.createIcons();
			} else {
				if (exp) exp.innerHTML = '';
				if (expBox) expBox.style.display = 'none';
			}
			modal.classList.remove('hidden');
			document.body.style.overflow = 'hidden';
			requestAnimationFrame(function () {
				backdrop.classList.remove('opacity-0');
				card.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
			});
			document.getElementById('smClose').focus();
		}
		function close() {
			backdrop.classList.add('opacity-0');
			card.classList.add('opacity-0', 'translate-y-4', 'scale-95');
			document.body.style.overflow = '';
			setTimeout(function () { modal.classList.add('hidden'); }, 300);
			if (lastFocus) lastFocus.focus();
		}
		document.querySelectorAll('.session-card').forEach(function (c) {
			c.addEventListener('click', function () { lastFocus = c; open(c.dataset); });
		});
		document.getElementById('smClose').addEventListener('click', close);
		backdrop.addEventListener('click', close);
		document.getElementById('smSubmit').addEventListener('click', close);
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.classList.contains('hidden')) close(); });
	})();
	</script>

<?php
get_footer();
