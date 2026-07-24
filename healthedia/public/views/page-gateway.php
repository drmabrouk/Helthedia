<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="healthedia-gateway h-screen flex flex-col items-center justify-center bg-white text-[#111111] -mt-16">
	<h1 class="text-6xl font-sans font-bold tracking-tighter uppercase mb-2">HEALTHEDIA</h1>
	<p class="font-mono text-sm text-gray-500 mb-8 uppercase tracking-widest">Global Health Archive & Network</p>
	<div class="w-full max-w-2xl relative">
		<form action="<?php echo home_url('/archive-search'); ?>" method="GET" class="w-full flex relative items-center">
			<input type="text" name="q" id="gateway-search-input" class="w-full border border-[#E0E0E0] rounded-full py-4 pl-8 pr-32 text-lg font-sans outline-none focus:border-black transition-colors shadow-sm" placeholder="Search across journals, authors, and DOIs...">
			<button type="button" id="btn-voice-search" title="Voice Search (English)" class="absolute right-[110px] text-gray-400 hover:text-black transition-colors">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
			</button>
			<button type="submit" class="absolute right-2 top-2 bottom-2 bg-black text-white px-6 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors">Search</button>
		</form>
	</div>
	<div class="mt-6 flex gap-3 font-mono text-xs">
		<span class="search-tag px-3 py-1 border border-[#E0E0E0] rounded-full text-gray-500 hover:text-black hover:border-black cursor-pointer transition-colors" data-type="article">DOIs</span>
		<span class="search-tag px-3 py-1 border border-[#E0E0E0] rounded-full text-gray-500 hover:text-black hover:border-black cursor-pointer transition-colors" data-type="user">Researchers</span>
		<span class="search-tag px-3 py-1 border border-[#E0E0E0] rounded-full text-gray-500 hover:text-black hover:border-black cursor-pointer transition-colors" data-type="article">Clinical Trials</span>
	</div>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
