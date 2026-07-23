	</div> <!-- End Main Content Wrapper -->

	<!-- Global Footer -->
	<footer class="border-t border-[#E0E0E0] bg-white py-12 mt-12">
		<div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
			<div class="md:col-span-2">
				<a href="<?php echo home_url(); ?>" class="font-sans font-bold text-2xl tracking-tighter uppercase block mb-4">Healthedia</a>
				<p class="font-mono text-xs text-gray-500 uppercase leading-relaxed max-w-sm">
					The authoritative indexed archive and encyclopedic network exclusively for healthcare professionals, clinical specialists, and academic institutions.
				</p>
			</div>
			<div>
				<h4 class="font-sans font-bold uppercase tracking-wider text-sm mb-4">Navigation</h4>
				<ul class="font-mono text-xs text-gray-500 space-y-2 uppercase">
					<li><a href="<?php echo home_url(); ?>" class="hover:text-black transition-colors">Home Gateway</a></li>
					<li><a href="<?php echo home_url('/journal'); ?>" class="hover:text-black transition-colors">Scientific Journal</a></li>
					<li><a href="<?php echo home_url('/directories'); ?>" class="hover:text-black transition-colors">Global Directory</a></li>
				</ul>
			</div>
			<div>
				<h4 class="font-sans font-bold uppercase tracking-wider text-sm mb-4">Legal</h4>
				<ul class="font-mono text-xs text-gray-500 space-y-2 uppercase">
					<li><a href="#" class="hover:text-black transition-colors">Privacy Policy</a></li>
					<li><a href="#" class="hover:text-black transition-colors">Terms of Service</a></li>
					<li><a href="#" class="hover:text-black transition-colors">Data Integrity</a></li>
				</ul>
			</div>
		</div>
		<div class="max-w-7xl mx-auto px-4 mt-12 pt-8 border-t border-[#E0E0E0] flex flex-col md:flex-row justify-between items-center gap-4">
			<div class="font-mono text-[10px] text-gray-400 uppercase tracking-widest">
				&copy; <?php echo date('Y'); ?> Healthedia Archive. All Rights Reserved.
			</div>
			<div class="font-mono text-[10px] text-gray-400 uppercase tracking-widest">
				System Version <?php echo HEALTHEDIA_VERSION; ?>
			</div>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>
