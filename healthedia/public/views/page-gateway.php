<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="healthedia-gateway flex flex-col items-center justify-center bg-white text-[#111111] pt-24 pb-12">
	<h1 class="text-6xl md:text-7xl font-sans font-bold tracking-tighter uppercase mb-2">HEALTHEDIA</h1>
	<p class="font-mono text-xs md:text-sm text-gray-500 mb-8 uppercase tracking-widest text-center">Global Health Archive & Network</p>
	<div class="w-full max-w-2xl relative">
		<form action="<?php echo home_url('/archive-search'); ?>" method="GET" class="w-full flex relative items-center">
			<input type="text" name="q" id="gateway-search-input" class="w-full border border-[#E0E0E0] rounded-full py-4 pl-8 pr-40 text-lg font-sans outline-none focus:border-black transition-colors shadow-sm" placeholder="Search across journals, authors, and DOIs...">

			<div class="absolute right-2 top-2 bottom-2 flex items-center gap-2">
				<button type="button" id="btn-voice-search" title="Voice Search (English)" class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition-colors">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
				</button>
				<button type="submit" class="bg-black text-white px-6 h-10 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
					Search
				</button>
			</div>
		</form>
	</div>

	<!-- Search Suggestions -->
	<div class="mt-8 flex flex-wrap justify-center gap-2 font-mono text-[9px] uppercase tracking-widest max-w-lg">
		<span class="text-gray-400 mr-2 flex items-center">Trending:</span>
		<span class="search-tag px-3 py-1 bg-gray-50 border border-[#E0E0E0] rounded-full text-gray-600 hover:bg-gray-100 hover:text-black hover:border-gray-300 cursor-pointer transition-all">Oncology DOIs</span>
		<span class="search-tag px-3 py-1 bg-gray-50 border border-[#E0E0E0] rounded-full text-gray-600 hover:bg-gray-100 hover:text-black hover:border-gray-300 cursor-pointer transition-all">Verified Researchers</span>
		<span class="search-tag px-3 py-1 bg-gray-50 border border-[#E0E0E0] rounded-full text-gray-600 hover:bg-gray-100 hover:text-black hover:border-gray-300 cursor-pointer transition-all">Clinical Trials</span>
		<span class="search-tag px-3 py-1 bg-gray-50 border border-[#E0E0E0] rounded-full text-gray-600 hover:bg-gray-100 hover:text-black hover:border-gray-300 cursor-pointer transition-all">Biomechanics</span>
	</div>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
