<?php
include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php';

$user_id = get_query_var('healthedia_profile');
if (!$user_id && get_query_var('healthedia_profile_user')) {
	$user_obj = get_query_var('healthedia_profile_user');
	$user_id = $user_obj->ID;
}

$user = get_userdata($user_id);

if (!$user) {
	echo "<div class='text-center py-20 font-sans'>Researcher not found.</div>";
	include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php';
	return;
}

$privacy_mode = get_user_meta($user_id, '_healthedia_privacy_mode', true) ?: 'public';

if (get_current_user_id() != $user_id) {
	if ($privacy_mode === 'private') {
		echo "<div class='text-center py-20 font-sans'>This profile is private.</div>";
		include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php';
		return;
	} elseif ($privacy_mode === 'hidden') {
		echo "<div class='text-center py-20 font-sans'>This profile is temporarily hidden.</div>";
		include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php';
		return;
	}
}

require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Profiles/class-profile-model.php';
require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Profiles/class-profile-verification.php';
$metrics = Healthedia_Profile_Model::get_metrics($user_id);
$is_verified = Healthedia_Profile_Verification::is_verified($user_id);
$specialty = get_user_meta($user_id, '_healthedia_specialty', true);
$institution = get_user_meta($user_id, '_healthedia_institution', true);
$country = get_user_meta($user_id, '_healthedia_country', true);
$orcid = get_user_meta($user_id, '_healthedia_orcid', true);

$experience = get_user_meta($user_id, '_healthedia_experience', true);
$degree = get_user_meta($user_id, '_healthedia_degree', true);
$qualifications = get_user_meta($user_id, '_healthedia_qualifications', true);
$interests = get_user_meta($user_id, '_healthedia_interests', true);
$honors = get_user_meta($user_id, '_healthedia_honors', true);
?>
<div class="healthedia-profile max-w-5xl mx-auto py-12 px-4 bg-white text-[#111111]">
	<?php
	require_once HEALTHEDIA_PLUGIN_DIR . 'public/views/partials/breadcrumbs.php';
	healthedia_breadcrumbs();
	?>

	<!-- Top Bar Action -->
	<div class="flex justify-between items-center mb-8">
		<a href="<?php echo home_url('/directory'); ?>" class="border border-[#E0E0E0] rounded-full px-4 py-1.5 text-xs font-mono uppercase tracking-widest hover:bg-gray-50 flex items-center gap-2">
			&larr; Back to Directory
		</a>
		<div class="flex items-center gap-2 border border-[#E0E0E0] rounded-full px-3 py-1 font-mono text-xs uppercase tracking-widest">
			<span class="text-gray-400">Profile Lang:</span>
			<span class="bg-black text-white rounded-full px-2 py-0.5">EN</span>
		</div>
	</div>

	<!-- Profile Card Header -->
	<div class="border border-[#E0E0E0] rounded-2xl p-6 md:p-8 mb-8 flex flex-col md:flex-row items-start gap-6 relative">
		<div class="w-32 h-32 bg-gray-200 rounded-xl flex-shrink-0 relative overflow-hidden">
			<?php
			$photo_url = get_user_meta($user_id, '_healthedia_profile_photo', true);
			if ($photo_url) {
				echo '<img src="'.esc_url($photo_url).'" class="w-full h-full object-cover" loading="lazy" alt="'.esc_attr($user->display_name).' profile picture">';
			} else {
				echo get_avatar($user_id, 256, '', esc_attr($user->display_name) . ' profile picture', array('class' => 'w-full h-full object-cover', 'loading' => 'lazy'));
			}
			?>
			<?php if ($is_verified): ?>
			<div class="absolute bottom-[-8px] right-[-8px] bg-black text-white p-1.5 rounded-full border-4 border-white">
				<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
			</div>
			<?php endif; ?>
		</div>

		<div class="flex-1">
			<div class="flex items-center gap-3 mb-2">
				<h1 class="text-3xl font-sans font-bold tracking-tight"><?php echo esc_html($user->display_name); ?></h1>
				<?php if ($is_verified): ?>
				<span class="bg-black text-white px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-widest flex items-center gap-1">
					<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
					Verified Specialist
				</span>
				<?php endif; ?>
			</div>
			<p class="font-sans text-lg font-bold mb-4"><?php echo esc_html($specialty ?: 'Independent Researcher'); ?></p>

			<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 font-mono text-xs text-gray-600 mb-6">
				<div class="flex items-center gap-2">
					<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
					<?php echo esc_html($institution ?: 'N/A'); ?>
				</div>
				<div class="flex items-center gap-2">
					<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
					Origin: <?php echo esc_html($country ?: 'N/A'); ?>
				</div>
				<div class="flex items-center gap-2">
					<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
					ORCID: <?php echo esc_html($orcid ?: 'N/A'); ?>
				</div>
			</div>

			<button class="bg-black text-white font-sans font-bold uppercase text-xs px-4 py-2 rounded-full flex items-center gap-2 hover:bg-gray-800 transition-colors">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
				Contact Affiliated Lab
			</button>
		</div>
	</div>

	<!-- Tabs -->
	<div class="border-b border-[#E0E0E0] mb-8 flex gap-8">
		<button id="btn-tab-overview" class="pb-3 text-sm font-sans font-bold uppercase tracking-wider border-b-2 border-black text-black">Overview & Academic Portfolio</button>
		<button id="btn-tab-impact" class="pb-3 text-sm font-sans font-bold uppercase tracking-wider text-gray-400 hover:text-black border-b-2 border-transparent">Research Impact</button>
	</div>

	<!-- Tab Content: Overview & Academic Portfolio -->
	<div id="tab-overview" class="block">
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
			<!-- Left Column: Biography & Experience -->
			<div class="lg:col-span-2 space-y-6">
				<!-- Biography Card -->
				<div class="border border-[#E0E0E0] rounded-2xl p-6 md:p-8">
					<h3 class="font-mono text-xs font-bold uppercase tracking-widest mb-4 flex items-center gap-2 text-gray-400">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
						Biography
					</h3>
					<p class="font-sans text-sm text-gray-600 leading-relaxed">
						<?php echo nl2br(esc_html($user->description ?: 'No biography provided.')); ?>
					</p>
				</div>

				<!-- Professional Experience Card -->
				<div class="border border-[#E0E0E0] rounded-2xl p-6 md:p-8">
					<h3 class="font-mono text-xs font-bold uppercase tracking-widest mb-4 flex items-center gap-2 text-gray-400">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
						Professional Experience
					</h3>
					<ul class="list-disc list-outside ml-4 space-y-3 font-sans text-sm text-gray-600">
						<?php
						if ($experience) {
							$exp_lines = explode("\n", $experience);
							foreach ($exp_lines as $line) {
								if (trim($line)) {
									echo '<li>' . esc_html(trim($line)) . '</li>';
								}
							}
						} else {
							echo '<li>No professional experience listed.</li>';
						}
						?>
					</ul>
				</div>
			</div>

			<!-- Right Column: Credentials & Interests -->
			<div class="space-y-6">
				<!-- Academic Credentials Card -->
				<div class="border border-[#E0E0E0] rounded-2xl p-6 md:p-8">
					<h3 class="font-mono text-xs font-bold uppercase tracking-widest mb-6 flex items-center gap-2 text-gray-400">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
						Academic Credentials
					</h3>

					<div class="mb-4">
						<div class="font-mono text-[10px] uppercase tracking-widest text-gray-400 mb-1">Affiliation</div>
						<div class="font-sans text-xs font-bold text-gray-800"><?php echo esc_html($institution ?: 'N/A'); ?></div>
					</div>

					<div class="mb-4">
						<div class="font-mono text-[10px] uppercase tracking-widest text-gray-400 mb-1">Degree Level</div>
						<div class="font-sans text-xs font-bold text-gray-800"><?php echo esc_html($degree ?: 'N/A'); ?></div>
					</div>

					<div>
						<div class="font-mono text-[10px] uppercase tracking-widest text-gray-400 mb-2">Academic Qualifications</div>
						<ul class="list-disc list-outside ml-4 space-y-1 font-sans text-xs text-gray-600">
							<?php
							if ($qualifications) {
								$qual_lines = explode("\n", $qualifications);
								foreach ($qual_lines as $line) {
									if (trim($line)) {
										echo '<li>' . esc_html(trim($line)) . '</li>';
									}
								}
							} else {
								echo '<li>None listed.</li>';
							}
							?>
						</ul>
					</div>
				</div>

				<!-- Research Interests Card -->
				<div class="border border-[#E0E0E0] rounded-2xl p-6 md:p-8">
					<h3 class="font-mono text-xs font-bold uppercase tracking-widest mb-4 flex items-center gap-2 text-gray-400">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
						Research Interests
					</h3>
					<div class="flex flex-wrap gap-2">
						<?php
						if ($interests) {
							$int_lines = explode("\n", $interests);
							foreach ($int_lines as $line) {
								if (trim($line)) {
									echo '<span class="border border-[#E0E0E0] rounded-full px-3 py-1 font-sans text-xs text-gray-600 bg-white hover:bg-gray-50 transition-colors">' . esc_html(trim($line)) . '</span>';
								}
							}
						} else {
							echo '<span class="font-sans text-xs text-gray-500">No specific interests listed.</span>';
						}
						?>
					</div>
				</div>

				<!-- Honors & Certifications Card -->
				<div class="border border-[#E0E0E0] rounded-2xl p-6 md:p-8">
					<h3 class="font-mono text-xs font-bold uppercase tracking-widest mb-4 flex items-center gap-2 text-gray-400">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
						Honors & Certifications
					</h3>
					<div class="space-y-4">
						<?php
						if ($honors) {
							$honor_lines = explode("\n", $honors);
							foreach ($honor_lines as $line) {
								if (trim($line)) {
									echo '
									<div class="flex items-start gap-2">
										<svg class="w-3.5 h-3.5 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
										<span class="font-sans text-xs text-gray-600">' . esc_html(trim($line)) . '</span>
									</div>';
								}
							}
						} else {
							echo '<span class="font-sans text-xs text-gray-500">None listed.</span>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Tab Content: Research Impact -->
	<div id="tab-impact" class="hidden">
	<!-- Metrics Grid -->
	<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
		<div class="border border-[#E0E0E0] rounded-2xl p-6">
			<div class="font-mono text-xs uppercase tracking-widest text-gray-400 mb-2 flex justify-between">
				Aggregate Citations
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
			</div>
			<div class="text-3xl font-sans font-bold mb-2"><?php echo number_format_i18n($metrics->citations); ?></div>
			<p class="text-xs text-gray-500">Cumulative citations across all published platform manuscripts.</p>
		</div>

		<div class="border border-[#E0E0E0] rounded-2xl p-6 relative overflow-hidden">
			<div class="font-mono text-xs uppercase tracking-widest text-gray-400 mb-2 flex justify-between">
				Platform H-Index
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
			</div>
			<div class="text-3xl font-sans font-bold mb-2">5</div>
			<p class="text-xs text-gray-500">5 papers with at least 5 citations each on Healthedia.</p>
		</div>

		<div class="border border-[#E0E0E0] rounded-2xl p-6">
			<div class="font-mono text-xs uppercase tracking-widest text-gray-400 mb-2 flex justify-between">
				Avg Citations / Paper
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
			</div>
			<div class="text-3xl font-sans font-bold mb-2"><?php $post_count = count_user_posts($user_id, 'post') + count_user_posts($user_id, 'healthedia_ext_res') + count_user_posts($user_id, 'healthedia_journal') + count_user_posts($user_id, 'healthedia_article'); echo $metrics->citations > 0 ? number_format($metrics->citations / max(1, $post_count), 1) : 0; ?></div>
			<p class="text-xs text-gray-500">Mean citation rate per index publication.</p>
		</div>

		<div class="border border-[#E0E0E0] rounded-2xl p-6">
			<div class="font-mono text-xs uppercase tracking-widest text-gray-400 mb-2 flex justify-between">
				Mean FWCI Impact
				<span class="font-sans font-bold">%</span>
			</div>
			<div class="text-3xl font-sans font-bold mb-2">3.43x</div>
			<p class="text-xs text-gray-500">Compared to global specialty baseline (1.0x).</p>
		</div>
	</div>

	<!-- Bottom Split Section -->
	<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
		<div class="md:col-span-2 border border-[#E0E0E0] rounded-2xl p-6 md:p-8">
			<div class="flex justify-between items-center mb-6">
				<h3 class="font-mono text-xs font-bold uppercase tracking-widest flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
					Citation Velocity Timeline (Aggregate)
				</h3>
				<span class="bg-gray-100 text-[10px] font-mono uppercase tracking-widest px-2 py-1 rounded">Cumulative Trend</span>
			</div>
			<p class="text-sm text-gray-500 mb-8">This graph represents the cumulative citation progress across all platform publications. Hover over the bars to view detailed metrics.</p>

			<div class="bg-[#FAFAFA] border border-[#E0E0E0] rounded-xl h-48 flex items-end px-12 pb-4 gap-12 justify-between">
				<div class="flex flex-col items-center w-full">
					<div class="w-16 bg-[#222] rounded-t-lg" style="height: 15%"></div>
					<div class="mt-2 text-xs font-mono text-gray-500">2023</div>
				</div>
				<div class="flex flex-col items-center w-full">
					<div class="w-16 bg-[#222] rounded-t-lg" style="height: 15%"></div>
					<div class="mt-2 text-xs font-mono text-gray-500">2024</div>
				</div>
				<div class="flex flex-col items-center w-full">
					<div class="w-16 bg-[#222] rounded-t-lg" style="height: 20%"></div>
					<div class="mt-2 text-xs font-mono text-gray-500">2025</div>
				</div>
				<div class="flex flex-col items-center w-full">
					<div class="w-16 bg-[#222] rounded-t-lg" style="height: 25%"></div>
					<div class="mt-2 text-xs font-mono text-gray-500">2026</div>
				</div>
			</div>
		</div>

		<div class="border border-[#E0E0E0] rounded-2xl p-6 md:p-8">
			<h3 class="font-mono text-xs font-bold uppercase tracking-widest mb-6 flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
				Scientific Metrics Registry
			</h3>

			<div class="space-y-4">
				<div class="bg-[#FAFAFA] border border-[#E0E0E0] rounded-xl p-4">
					<h4 class="font-mono text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Understanding H-Index</h4>
					<p class="text-xs text-gray-600">The <strong>h-index</strong> measures both scientific productivity and citation impact. A platform h-index of <strong>5</strong> indicates that this researcher has authored at least 5 papers on the platform with 5+ citations each.</p>
				</div>
				<div class="bg-[#FAFAFA] border border-[#E0E0E0] rounded-xl p-4">
					<h4 class="font-mono text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Field-Weighted Citation Impact</h4>
					<p class="text-xs text-gray-600"><strong>FWCI</strong> compares citations received with global averages for similar publications in <?php echo strtolower(esc_html($specialty ?: 'this field')); ?>. A value of <strong>3.43x</strong> demonstrates an impact 243% higher than world averages.</p>
				</div>

				<div class="flex items-center gap-4 mt-6">
					<div class="bg-black text-white p-2 rounded-lg">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
					</div>
					<div>
						<div class="font-mono text-[10px] uppercase tracking-widest text-gray-400">Affiliate Standing</div>
						<div class="text-xs font-bold">Top-ranked researcher in <?php echo esc_html($specialty ?: 'Independent Research'); ?> on Healthedia.</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- List -->
	<div class="mt-8 border border-[#E0E0E0] rounded-2xl p-6 md:p-8">
		<div class="flex justify-between items-center mb-4">
			<h3 class="font-mono text-xs font-bold uppercase tracking-widest flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
				Manuscript Citation Impact & H-Core Contributions (<?php echo $post_count; ?>)
			</h3>
			<span class="bg-gray-100 text-[10px] font-mono uppercase tracking-widest px-2 py-1 rounded border border-[#E0E0E0]">Sorted by Citations</span>
		</div>
		<p class="text-xs text-gray-500 mb-6">The registry below details all manuscripts authored by this researcher. Highlighted publications with the <strong>h-index Core</strong> indicator are the key drivers of this researcher's scientific index.</p>

		<div class="space-y-4">
			<?php
			$args = array('post_type' => array('healthedia_article', 'post', 'healthedia_ext_res', 'healthedia_journal'), 'author' => $user_id, 'posts_per_page' => 10, 'orderby' => 'meta_value_num', 'meta_key' => '_healthedia_citations', 'order' => 'DESC');
			$query = new WP_Query($args);
			if ($query->have_posts()): while ($query->have_posts()): $query->the_post();
			?>
			<div class="p-4 border border-[#E0E0E0] rounded-lg hover:border-black transition-colors flex justify-between items-center">
				<div>
					<a href="<?php the_permalink(); ?>" class="font-sans font-bold text-base mb-1 block hover:underline"><?php the_title(); ?></a>
					<div class="font-mono text-[10px] uppercase text-gray-500 tracking-wider"><?php the_date(); ?> • <?php echo get_post_meta(get_the_ID(), '_healthedia_doi', true); ?></div>
				</div>
				<div class="text-right">
					<div class="font-mono text-xs text-gray-400 uppercase tracking-widest mb-1">Citations</div>
					<div class="font-sans font-bold text-lg"><?php echo (int)get_post_meta(get_the_ID(), '_healthedia_citations', true); ?></div>
				</div>
			</div>
			<?php endwhile; wp_reset_postdata(); else: ?>
			<p class="font-mono text-sm text-gray-500">No publications found.</p>
			<?php endif; ?>
		</div>
	</div>
	</div> <!-- End Tab Content: Research Impact -->

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
	const btnTabOverview = document.getElementById('btn-tab-overview');
	const btnTabImpact = document.getElementById('btn-tab-impact');
	const tabOverview = document.getElementById('tab-overview');
	const tabImpact = document.getElementById('tab-impact');

	if (btnTabOverview && btnTabImpact) {
		btnTabOverview.addEventListener('click', () => {
			// Update button styles
			btnTabOverview.classList.replace('text-gray-400', 'text-black');
			btnTabOverview.classList.replace('border-transparent', 'border-black');
			btnTabOverview.classList.remove('hover:text-black');

			btnTabImpact.classList.replace('text-black', 'text-gray-400');
			btnTabImpact.classList.replace('border-black', 'border-transparent');
			btnTabImpact.classList.add('hover:text-black');

			// Update tab visibility
			tabOverview.classList.remove('hidden');
			tabOverview.classList.add('block');
			tabImpact.classList.add('hidden');
			tabImpact.classList.remove('block');
		});

		btnTabImpact.addEventListener('click', () => {
			// Update button styles
			btnTabImpact.classList.replace('text-gray-400', 'text-black');
			btnTabImpact.classList.replace('border-transparent', 'border-black');
			btnTabImpact.classList.remove('hover:text-black');

			btnTabOverview.classList.replace('text-black', 'text-gray-400');
			btnTabOverview.classList.replace('border-black', 'border-transparent');
			btnTabOverview.classList.add('hover:text-black');

			// Update tab visibility
			tabImpact.classList.remove('hidden');
			tabImpact.classList.add('block');
			tabOverview.classList.add('hidden');
			tabOverview.classList.remove('block');
		});
	}
});
</script>

<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
