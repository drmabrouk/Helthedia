<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="max-w-3xl mx-auto py-24 px-4 bg-white text-[#111111] text-center min-h-[calc(100vh-200px)] flex flex-col justify-center">

	<h1 class="text-4xl md:text-5xl font-sans font-bold uppercase tracking-tight mb-4">Certificate Verification</h1>
	<p class="font-mono text-sm text-gray-500 uppercase mb-12">Verify the authenticity of Healthedia credentials</p>

	<form class="max-w-xl mx-auto w-full relative">
		<input type="text" placeholder="Enter Certificate ID (e.g. HTH-00192-X)" class="w-full border-2 border-[#E0E0E0] rounded-full py-4 px-8 text-lg font-mono text-center tracking-widest outline-none focus:border-black transition-colors shadow-sm uppercase">
		<button type="button" class="mt-8 bg-black text-white px-10 py-4 rounded-full font-sans uppercase text-sm tracking-wide font-bold hover:bg-gray-800 transition-colors w-full md:w-auto">Verify Authenticity</button>
	</form>

	<div class="mt-12 pt-8 border-t border-[#E0E0E0] font-mono text-[10px] uppercase tracking-widest text-gray-400">
		All cryptographic queries are securely logged.
	</div>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
