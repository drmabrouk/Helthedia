<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="max-w-4xl mx-auto py-12 px-4 bg-white text-[#111111]">

	<div class="text-center border-b border-[#E0E0E0] pb-8 mb-10">
		<h1 class="text-4xl font-sans font-bold uppercase tracking-tight mb-2">Manuscript Submission</h1>
		<p class="font-mono text-xs text-gray-500 uppercase tracking-widest">Secure Peer-Review Intake Portal</p>
	</div>

	<!-- Wizard Progress -->
	<div class="flex justify-between items-center mb-12 font-mono text-[10px] uppercase tracking-widest relative">
		<div class="absolute left-0 top-1/2 w-full h-px bg-[#E0E0E0] -z-10"></div>
		<div class="bg-black text-white px-4 py-1.5 rounded-full z-10 border-2 border-white">1. Metadata</div>
		<div class="bg-gray-100 text-gray-400 px-4 py-1.5 rounded-full z-10 border-2 border-white">2. Authors</div>
		<div class="bg-gray-100 text-gray-400 px-4 py-1.5 rounded-full z-10 border-2 border-white">3. Upload</div>
	</div>

	<form class="space-y-8 bg-white border border-[#E0E0E0] rounded-2xl p-8 shadow-sm">
		<div>
			<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Manuscript Title</label>
			<input type="text" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-3 font-sans outline-none focus:border-black transition-colors">
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			<div>
				<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Primary Specialty</label>
				<select class="w-full border border-[#E0E0E0] rounded-xl px-4 py-3 font-sans outline-none focus:border-black transition-colors bg-white">
					<option>Select Specialty...</option>
					<option>Biomechanics</option>
					<option>Cardiology</option>
				</select>
			</div>
			<div>
				<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Trial Registration (Optional)</label>
				<input type="text" placeholder="NCT Number" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-3 font-sans outline-none focus:border-black transition-colors font-mono text-sm">
			</div>
		</div>

		<div>
			<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Interactive Abstract</label>
			<div class="border border-[#E0E0E0] rounded-xl overflow-hidden focus-within:border-black transition-colors">
				<div class="bg-gray-50 border-b border-[#E0E0E0] px-4 py-2 flex gap-2">
					<button type="button" class="font-sans font-bold text-sm hover:text-gray-600">B</button>
					<button type="button" class="font-sans italic text-sm hover:text-gray-600">I</button>
				</div>
				<textarea rows="6" class="w-full px-4 py-3 font-sans outline-none resize-y"></textarea>
			</div>
		</div>

		<div class="border-t border-dashed border-[#E0E0E0] pt-8">
			<label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Upload Manuscript Securely</label>
			<div class="border-2 border-dashed border-[#E0E0E0] rounded-xl p-8 text-center hover:border-black transition-colors cursor-pointer bg-gray-50">
				<div class="font-sans font-bold text-lg mb-1">Drag & Drop file here</div>
				<div class="font-mono text-[10px] uppercase tracking-widest text-gray-500">Accepted formats: .PDF, .DOCX (Max 20MB)</div>
				<input type="file" accept=".pdf,.docx" class="hidden">
			</div>
		</div>

		<div class="flex justify-end gap-4 pt-6">
			<button type="button" class="border border-[#E0E0E0] bg-white text-black px-8 py-3 rounded-full font-sans uppercase text-sm tracking-wide hover:border-black transition-colors">Save Draft</button>
			<button type="button" class="bg-black text-white px-8 py-3 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors">Continue to Authors →</button>
		</div>
	</form>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
