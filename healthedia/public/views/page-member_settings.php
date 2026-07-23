<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<?php
$user_id = get_current_user_id();
$user = wp_get_current_user();
$username = get_user_meta($user_id, '_healthedia_username', true);
$specialty = get_user_meta($user_id, '_healthedia_specialty', true);
$institution = get_user_meta($user_id, '_healthedia_institution', true);
$country = get_user_meta($user_id, '_healthedia_country', true);
$orcid = get_user_meta($user_id, '_healthedia_orcid', true);
$is_private = get_user_meta($user_id, '_healthedia_is_private', true);
$description = get_user_meta($user_id, 'description', true);
?>
<div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-12 py-12 px-4 bg-white text-[#111111]">
	<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-member-sidebar.php'; ?>

	<main class="flex-grow max-w-3xl">
		<h1 class="text-3xl font-sans font-bold uppercase tracking-tight mb-8 border-b border-[#E0E0E0] pb-4">Account Settings</h1>

		<div id="settings-message" class="hidden mb-6 p-4 rounded-xl font-mono text-sm border"></div>

		<form id="healthedia-settings-form" class="space-y-8">
			<!-- Privacy Controls -->
			<div class="bg-gray-50 border border-[#E0E0E0] rounded-2xl p-8">
				<h2 class="font-sans font-bold uppercase tracking-wider text-sm mb-6 flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
					Profile Privacy & Visibility
				</h2>

				<label class="flex items-start gap-3 cursor-pointer">
					<div class="pt-1">
						<input type="checkbox" name="_healthedia_is_private" value="yes" <?php checked($is_private, 'yes'); ?> class="w-4 h-4 text-black border-gray-300 focus:ring-black">
					</div>
					<div>
						<span class="block font-sans font-bold text-sm">Make my profile private</span>
						<span class="block font-mono text-xs text-gray-500 mt-1">If checked, your profile page and academic portfolio will only be visible to you. It will be hidden from the Global Directory and external visitors.</span>
					</div>
				</label>
			</div>

			<!-- Public URL -->
			<div class="bg-white border border-[#E0E0E0] rounded-2xl p-8">
				<h2 class="font-sans font-bold uppercase tracking-wider text-sm mb-6">Custom Profile URL</h2>
				<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Username Handle</label>
				<div class="flex items-stretch">
					<span class="flex items-center px-4 bg-gray-100 border border-r-0 border-[#E0E0E0] rounded-l-xl font-mono text-sm text-gray-500">healthedia.org/u/</span>
					<input type="text" name="_healthedia_username" value="<?php echo esc_attr($username ?: $user->user_login); ?>" class="flex-grow border border-[#E0E0E0] rounded-r-xl px-4 py-2.5 font-sans outline-none focus:border-black bg-white" pattern="[a-zA-Z0-9_-]+" title="Only letters, numbers, underscores, and hyphens are allowed.">
				</div>
				<p class="font-mono text-xs text-gray-500 mt-2">This is your unique encyclopedia URL. It can be shared publicly for citations.</p>
			</div>

			<!-- Profile Information -->
			<div class="bg-white border border-[#E0E0E0] rounded-2xl p-8">
				<h2 class="font-sans font-bold uppercase tracking-wider text-sm mb-6">Academic Information</h2>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
					<div>
						<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Primary Specialty</label>
						<input type="text" name="_healthedia_specialty" value="<?php echo esc_attr($specialty); ?>" placeholder="e.g. Sports Science & Physiology" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-2.5 font-sans outline-none focus:border-black bg-white">
					</div>
					<div>
						<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Institution / Affiliated Lab</label>
						<input type="text" name="_healthedia_institution" value="<?php echo esc_attr($institution); ?>" placeholder="e.g. Sydney University" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-2.5 font-sans outline-none focus:border-black bg-white">
					</div>
					<div>
						<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Country of Origin</label>
						<input type="text" name="_healthedia_country" value="<?php echo esc_attr($country); ?>" placeholder="e.g. Australia" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-2.5 font-sans outline-none focus:border-black bg-white">
					</div>
					<div>
						<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">ORCID ID</label>
						<input type="text" name="_healthedia_orcid" value="<?php echo esc_attr($orcid); ?>" placeholder="0000-0000-0000-0000" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-2.5 font-mono text-sm outline-none focus:border-black bg-white">
					</div>
				</div>

				<div>
					<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Professional Biography (Abstract)</label>
					<textarea name="description" rows="5" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-2.5 font-sans outline-none focus:border-black bg-white resize-y"><?php echo esc_textarea($description); ?></textarea>
				</div>
			</div>

			<div class="flex justify-end">
				<button type="submit" class="bg-black text-white px-8 py-3 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors flex items-center gap-2">
					<svg class="w-4 h-4 hidden" id="settings-spinner" class="animate-spin" fill="none" viewBox="0 0 24 24">
						<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
						<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
					</svg>
					Save Changes
				</button>
			</div>
		</form>
	</main>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
	const form = document.getElementById('healthedia-settings-form');
	const msgBox = document.getElementById('settings-message');
	const spinner = document.getElementById('settings-spinner');

	if (!form) return;

	form.addEventListener('submit', async (e) => {
		e.preventDefault();
		const formData = new FormData(form);
		const data = Object.fromEntries(formData.entries());

		// Checkbox logic
		if (!formData.has('_healthedia_is_private')) {
			data['_healthedia_is_private'] = 'no';
		}

		msgBox.classList.add('hidden');
		spinner.classList.remove('hidden');

		try {
			const res = await fetch('/wp-json/healthedia/v1/profile', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': '<?php echo wp_create_nonce("wp_rest"); ?>'
				},
				body: JSON.stringify(data)
			});

			const result = await res.json();

			if (res.ok) {
				msgBox.textContent = result.message || 'Settings saved successfully.';
				msgBox.className = 'mb-6 p-4 rounded-xl font-mono text-sm border border-black bg-gray-50 text-black';
			} else {
				msgBox.textContent = result.message || 'An error occurred.';
				msgBox.className = 'mb-6 p-4 rounded-xl font-mono text-sm border border-red-500 bg-red-50 text-red-700';
			}
		} catch (err) {
			msgBox.textContent = 'Network error. Please try again.';
			msgBox.className = 'mb-6 p-4 rounded-xl font-mono text-sm border border-red-500 bg-red-50 text-red-700';
		} finally {
			msgBox.classList.remove('hidden');
			spinner.classList.add('hidden');
			window.scrollTo({top: 0, behavior: 'smooth'});
		}
	});
});
</script>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
