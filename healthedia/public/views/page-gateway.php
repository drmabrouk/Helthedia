<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="healthedia-gateway h-screen flex flex-col items-center justify-center bg-white text-[#111111] -mt-16">
	<h1 class="text-6xl font-sans font-bold tracking-tighter uppercase mb-2">HEALTHEDIA</h1>
	<p class="font-mono text-sm text-gray-500 mb-8 uppercase tracking-widest">Global Health Archive & Network</p>
	<div class="w-full max-w-2xl relative">
		<form action="<?php echo home_url('/archive-search'); ?>" method="GET" class="w-full flex">
			<input type="text" name="q" id="gateway-search-input" class="w-full border border-[#E0E0E0] rounded-full py-4 px-8 text-lg font-sans outline-none focus:border-black transition-colors shadow-sm" placeholder="Search across journals, authors, and DOIs...">
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
