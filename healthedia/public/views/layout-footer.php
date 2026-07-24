	</div> <!-- End Main Content Wrapper -->

	<!-- Global Footer -->
	<footer class="bg-white py-6 mt-12">
		<div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
			<div class="font-mono text-[10px] text-gray-400 uppercase tracking-widest">
				&copy; <?php echo date('Y'); ?> Healthedia Archive. All Rights Reserved.
			</div>
			<div class="flex items-center gap-6 font-mono text-[10px] text-gray-400 uppercase tracking-widest">
				<a href="<?php echo esc_url(get_option('healthedia_privacy_policy_url', '#')); ?>" class="hover:text-black transition-colors">Privacy Policy</a>
				<a href="<?php echo esc_url(get_option('healthedia_terms_url', '#')); ?>" class="hover:text-black transition-colors">Terms of Service</a>
				<a href="#" class="hover:text-black transition-colors">Data Integrity</a>
			</div>
			<div class="font-mono text-[10px] text-gray-400 uppercase tracking-widest">
				System Version <?php echo HEALTHEDIA_VERSION; ?>
			</div>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>
