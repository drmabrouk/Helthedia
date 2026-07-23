<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="max-w-4xl mx-auto py-12 px-4 bg-white text-[#111111]">
	<h1 class="text-4xl font-sans font-bold uppercase tracking-tight mb-2">Manuscript Submission</h1>
	<p class="font-mono text-sm text-gray-500 mb-8 border-b border-[#E0E0E0] pb-4">Peer-Review Intake Portal</p>
	<form class="space-y-6">
		<div>
			<label class="block font-mono text-xs uppercase text-gray-500 mb-2">Manuscript Title</label>
			<input type="text" class="w-full border border-[#E0E0E0] rounded px-4 py-3 font-sans outline-none focus:border-black transition-colors">
		</div>
		<div>
			<label class="block font-mono text-xs uppercase text-gray-500 mb-2">Abstract</label>
			<textarea rows="6" class="w-full border border-[#E0E0E0] rounded px-4 py-3 font-sans outline-none focus:border-black transition-colors"></textarea>
		</div>
		<div>
			<label class="block font-mono text-xs uppercase text-gray-500 mb-2">Upload Manuscript (PDF only)</label>
			<input type="file" accept=".pdf" class="w-full border border-[#E0E0E0] rounded px-4 py-3 font-sans outline-none focus:border-black transition-colors bg-gray-50">
		</div>
		<button type="button" class="bg-black text-white px-8 py-3 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors">Submit to Editorial Board</button>
	</form>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
