<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-12 py-12 px-4 bg-white text-[#111111]">
	<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-member-sidebar.php'; ?>

	<main class="flex-grow">
		<h1 class="text-3xl font-sans font-bold uppercase tracking-tight mb-8 border-b border-[#E0E0E0] pb-4">My Requests</h1>

		<div class="border border-[#E0E0E0] rounded-2xl overflow-hidden shadow-sm">
			<table class="w-full text-left border-collapse">
				<thead>
					<tr class="bg-gray-50 border-b border-[#E0E0E0] font-mono text-[10px] uppercase tracking-widest text-gray-500">
						<th class="py-4 px-6 font-normal">ID</th>
						<th class="py-4 px-6 font-normal">Request Type</th>
						<th class="py-4 px-6 font-normal">Date Submitted</th>
						<th class="py-4 px-6 font-normal">Status</th>
					</tr>
				</thead>
				<tbody class="font-sans text-sm divide-y divide-[#E0E0E0]">
					<tr class="hover:bg-gray-50 transition-colors">
						<td class="py-4 px-6 font-mono text-xs text-gray-500">REQ-8921</td>
						<td class="py-4 px-6 font-bold">Manuscript Submission</td>
						<td class="py-4 px-6 font-mono text-xs text-gray-500">Oct 14, 2023</td>
						<td class="py-4 px-6"><span class="bg-yellow-100 text-yellow-800 border border-yellow-200 px-2 py-0.5 rounded-full font-mono text-[10px] uppercase tracking-widest">Pending Review</span></td>
					</tr>
					<tr class="hover:bg-gray-50 transition-colors">
						<td class="py-4 px-6 font-mono text-xs text-gray-500">REQ-7430</td>
						<td class="py-4 px-6 font-bold">Researcher Verification</td>
						<td class="py-4 px-6 font-mono text-xs text-gray-500">Sep 02, 2023</td>
						<td class="py-4 px-6"><span class="bg-green-100 text-green-800 border border-green-200 px-2 py-0.5 rounded-full font-mono text-[10px] uppercase tracking-widest">Approved</span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</main>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
