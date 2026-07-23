<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<?php
$query = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'articles';
?>
<div class="max-w-7xl mx-auto py-12 px-4 bg-white text-[#111111] min-h-[60vh]">

	<!-- Search Header -->
	<header class="mb-10 text-center max-w-3xl mx-auto">
		<h1 class="text-3xl font-sans font-bold uppercase tracking-tight mb-6">Archive Search</h1>
		<form action="<?php echo home_url('/archive-search'); ?>" method="GET" class="relative">
			<input type="text" name="q" value="<?php echo esc_attr($query); ?>" placeholder="Search articles, researchers, DOIs..." class="w-full border border-[#E0E0E0] rounded-full px-6 py-4 font-sans text-lg outline-none focus:border-black shadow-sm bg-white pr-32">
			<input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>">
			<button type="submit" class="absolute right-2 top-2 bottom-2 bg-black text-white px-6 rounded-full font-sans uppercase text-sm font-bold tracking-wide hover:bg-gray-800 transition-colors">Search</button>
		</form>
	</header>

	<!-- Tabs -->
	<div class="flex justify-center gap-8 border-b border-[#E0E0E0] mb-8 font-mono text-sm uppercase tracking-widest">
		<a href="<?php echo home_url('/archive-search?q=' . urlencode($query) . '&tab=articles'); ?>" class="pb-3 <?php echo $tab === 'articles' ? 'border-b-2 border-black font-bold text-black' : 'text-gray-400 hover:text-black'; ?>">
			Published Articles
		</a>
		<a href="<?php echo home_url('/archive-search?q=' . urlencode($query) . '&tab=researchers'); ?>" class="pb-3 <?php echo $tab === 'researchers' ? 'border-b-2 border-black font-bold text-black' : 'text-gray-400 hover:text-black'; ?>">
			Verified Researchers
		</a>
		<a href="<?php echo home_url('/archive-search?q=' . urlencode($query) . '&tab=institutions'); ?>" class="pb-3 <?php echo $tab === 'institutions' ? 'border-b-2 border-black font-bold text-black' : 'text-gray-400 hover:text-black'; ?>">
			Institutions
		</a>
	</div>

	<!-- Results Area -->
	<div id="search-results-container" class="max-w-5xl mx-auto">
		<?php if (empty($query)): ?>
			<div class="text-center py-12 text-gray-400 font-mono text-sm uppercase tracking-widest">Enter a query to search the global archive.</div>
		<?php else: ?>
			<div id="loading-spinner" class="hidden text-center py-12">
				<svg class="w-8 h-8 animate-spin mx-auto text-black" fill="none" viewBox="0 0 24 24">
					<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
					<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
				</svg>
			</div>
			<div id="results-list" class="space-y-6"></div>
		<?php endif; ?>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
	const query = '<?php echo esc_js($query); ?>';
	const tab = '<?php echo esc_js($tab); ?>';

	if (!query) return;

	const container = document.getElementById('results-list');
	const spinner = document.getElementById('loading-spinner');

	spinner.classList.remove('hidden');

	let fetchType = '';
	if (tab === 'articles') fetchType = 'healthedia_article';
	else if (tab === 'researchers') fetchType = 'user';
	else if (tab === 'institutions') fetchType = 'healthedia_inst';

	try {
		const res = await fetch(`/wp-json/healthedia/v1/search?q=${encodeURIComponent(query)}&type=${encodeURIComponent(fetchType)}`);
		const data = await res.json();

		spinner.classList.add('hidden');

		if (!data || data.length === 0) {
			container.innerHTML = '<div class="text-center py-12 text-gray-400 font-mono text-sm uppercase tracking-widest">No results found for your query.</div>';
			return;
		}

		let html = '';

		if (tab === 'articles') {
			const articles = data;
			if (articles.length === 0) {
				html = '<div class="text-center py-12 text-gray-400 font-mono text-sm uppercase tracking-widest">No articles found.</div>';
			} else {
				articles.forEach(item => {
					html += `
					<div class="border border-[#E0E0E0] rounded-2xl p-6 hover:border-black transition-colors">
						<a href="${item.url}" class="font-sans font-bold text-xl block hover:underline mb-2">${item.title}</a>
						<div class="font-mono text-xs text-gray-500 uppercase tracking-widest flex items-center gap-4">
							<span>DOI: ${item.meta.doi || 'N/A'}</span>
							${item.meta.open_access === 'yes' ? '<span class="bg-black text-white px-2 py-0.5 rounded">Open Access</span>' : ''}
						</div>
					</div>`;
				});
			}
		} else if (tab === 'researchers') {
			const users = data;
			if (users.length === 0) {
				html = '<div class="text-center py-12 text-gray-400 font-mono text-sm uppercase tracking-widest">No researchers found.</div>';
			} else {
				html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
				users.forEach(item => {
					html += `
					<div class="border border-[#E0E0E0] rounded-2xl p-6 flex items-start gap-4 hover:border-black transition-colors">
						<div class="w-12 h-12 bg-gray-200 rounded-full flex-shrink-0 flex items-center justify-center font-sans font-bold text-xl text-gray-400">${item.title.charAt(0)}</div>
						<div>
							<a href="${item.url}" class="font-sans font-bold text-lg block hover:underline">${item.title}</a>
							<div class="font-mono text-[10px] text-gray-500 uppercase tracking-widest mt-1">${item.meta.specialty || 'Independent Researcher'}</div>
						</div>
					</div>`;
				});
				html += '</div>';
			}
		} else if (tab === 'institutions') {
			const insts = data;
			if (insts.length === 0) {
				html = '<div class="text-center py-12 text-gray-400 font-mono text-sm uppercase tracking-widest">No institutions found.</div>';
			} else {
				insts.forEach(item => {
					html += `
					<div class="border border-[#E0E0E0] rounded-2xl p-6 hover:border-black transition-colors flex items-center gap-4">
						<div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center border border-[#E0E0E0] text-gray-400">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
						</div>
						<div>
							<a href="${item.url}" class="font-sans font-bold text-xl block hover:underline mb-1">${item.title}</a>
							<div class="font-mono text-[10px] text-gray-500 uppercase tracking-widest">Global verified institution</div>
						</div>
					</div>`;
				});
			}
		}

		container.innerHTML = html;

	} catch (e) {
		spinner.classList.add('hidden');
		container.innerHTML = '<div class="text-center py-12 text-red-500 font-mono text-sm uppercase tracking-widest">Error fetching results.</div>';
	}
});
</script>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
