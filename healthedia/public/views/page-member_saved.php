<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-12 py-12 px-4 bg-white text-[#111111]">
	<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-member-sidebar.php'; ?>

	<main class="flex-grow">
		<h1 class="text-3xl font-sans font-bold uppercase tracking-tight mb-8 border-b border-[#E0E0E0] pb-4">Saved Research</h1>

		<div class="space-y-4">
			<!-- Mocked Saved Article mirroring Journal design -->
			<div class="p-6 border border-[#E0E0E0] rounded-2xl hover:border-black transition-colors flex flex-col md:flex-row justify-between md:items-center gap-4 group bg-white shadow-sm hover:shadow-md">
				<div>
					<div class="flex gap-2 mb-2 font-mono text-[10px] uppercase tracking-widest text-gray-500">
						<span class="border border-[#E0E0E0] px-2 py-0.5 rounded-full">Bookmarked Oct 12, 2023</span>
					</div>
					<a href="#" class="font-sans font-bold text-lg block group-hover:underline">Efficacy of Novel Therapeutics in Oncology</a>
					<div class="font-mono text-xs text-gray-500 mt-1">10.5555/healthedia.2023.1042</div>
				</div>
				<button class="flex-shrink-0 border border-[#E0E0E0] px-4 py-2 rounded-full font-mono text-xs uppercase hover:bg-gray-50 transition-colors">Remove</button>
			</div>
		</div>
	</main>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
