<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="healthedia-academies max-w-7xl mx-auto py-12 px-4 bg-white text-[#111111]">
	<div class="flex flex-col md:flex-row justify-between items-end border-b border-[#E0E0E0] pb-6 mb-8 gap-4">
		<div>
			<h1 class="text-4xl font-sans font-bold uppercase tracking-tight mb-2">Global Encyclopedia of Academies</h1>
			<p class="font-mono text-sm text-gray-500 uppercase">Verified Medical Schools and Research Institutes</p>
		</div>
		<div class="flex gap-2">
			<select class="border border-[#E0E0E0] rounded-full px-4 py-2 text-sm font-sans outline-none focus:border-black cursor-pointer bg-white">
				<option value="">All Regions</option>
				<option value="na">North America</option>
				<option value="eu">Europe</option>
				<option value="as">Asia</option>
			</select>
			<button class="bg-black text-white px-6 py-2 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors">Filter</button>
		</div>
	</div>
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
		<div class="col-span-full text-center py-12 font-mono text-sm text-gray-500">Loading academies data...</div>
	</div>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
