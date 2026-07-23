<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="healthedia-directory max-w-7xl mx-auto py-12 px-4 bg-white text-[#111111]">

	<div class="flex flex-col md:flex-row justify-between items-end border-b border-[#E0E0E0] pb-6 mb-8 gap-4">
		<div>
			<h1 class="text-4xl font-sans font-bold uppercase tracking-tight mb-2">Global Directory of Researchers</h1>
			<p class="font-mono text-sm text-gray-500 uppercase">Verified academic and clinical professionals</p>
		</div>

		<div class="flex gap-2">
			<select id="filter-specialty" class="border border-[#E0E0E0] rounded-full px-4 py-2 text-sm font-sans outline-none focus:border-black cursor-pointer bg-white">
				<option value="">All Specialties</option>
				<option value="cardiology">Cardiology</option>
				<option value="neurology">Neurology</option>
				<option value="biomechanics">Biomechanics</option>
			</select>
			<button class="bg-black text-white px-6 py-2 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors">Filter</button>
		</div>
	</div>

	<!-- Directory Grid -->
	<div id="directory-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
		<!-- Populated via JS / REST API -->
		<div class="col-span-full text-center py-12 font-mono text-sm text-gray-500">Loading directory data...</div>
	</div>

	<!-- Pagination -->
	<div class="mt-12 flex justify-center gap-2">
		<button class="px-4 py-2 border border-[#E0E0E0] rounded font-mono text-sm hover:border-black transition-colors disabled:opacity-50">Prev</button>
		<button class="px-4 py-2 border border-[#E0E0E0] rounded font-mono text-sm hover:border-black transition-colors disabled:opacity-50">Next</button>
	</div>

</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
