<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-12 py-12 px-4 bg-white text-[#111111]">
	<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-member-sidebar.php'; ?>

	<main class="flex-grow max-w-3xl">
		<h1 class="text-3xl font-sans font-bold uppercase tracking-tight mb-8 border-b border-[#E0E0E0] pb-4">Account Settings</h1>

		<form class="space-y-8">
			<div class="bg-gray-50 border border-[#E0E0E0] rounded-2xl p-8">
				<h2 class="font-sans font-bold uppercase tracking-wider text-sm mb-6">Profile Information</h2>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
					<div>
						<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Full Name</label>
						<input type="text" value="<?php echo esc_attr(wp_get_current_user()->display_name); ?>" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-2.5 font-sans outline-none focus:border-black bg-white">
					</div>
					<div>
						<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Academic Email</label>
						<input type="email" value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>" disabled class="w-full border border-[#E0E0E0] rounded-xl px-4 py-2.5 font-sans text-gray-400 bg-gray-100 cursor-not-allowed">
					</div>
				</div>
				<div>
					<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">ORCID ID</label>
					<input type="text" placeholder="0000-0000-0000-0000" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-2.5 font-mono text-sm outline-none focus:border-black bg-white">
				</div>
			</div>

			<div class="flex justify-end">
				<button type="submit" class="bg-black text-white px-8 py-3 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors">Save Changes</button>
			</div>
		</form>
	</main>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
