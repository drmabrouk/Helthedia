<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="healthedia-journal max-w-7xl mx-auto flex flex-col md:flex-row gap-8 py-12 px-4 bg-white text-[#111111]">
	<aside class="w-full md:w-1/4 border-r border-[#E0E0E0] pr-6">
		<div class="sticky top-8">
			<h3 class="font-sans font-bold uppercase tracking-wider mb-4 text-sm border-b border-[#E0E0E0] pb-2">Journal Navigation</h3>
			<ul class="font-mono text-sm space-y-3">
				<li><a href="#" class="text-black font-bold">Current Issue</a></li>
				<li><a href="#" class="text-gray-500 hover:text-black">All Issues</a></li>
				<li><a href="#" class="text-gray-500 hover:text-black">Editorial Board</a></li>
				<li><a href="/submit-manuscript" class="text-gray-500 hover:text-black">Submit Research</a></li>
			</ul>
		</div>
	</aside>
	<main class="w-full md:w-3/4">
		<h1 class="text-4xl md:text-5xl font-sans font-bold leading-tight tracking-tighter mb-8 border-b border-[#E0E0E0] pb-4">Scientific Journal Archive</h1>
		<div class="space-y-6">
			<?php
			$args = array('post_type' => 'healthedia_article', 'posts_per_page' => 10);
			$query = new WP_Query($args);
			if ($query->have_posts()): while ($query->have_posts()): $query->the_post();
				$doi = get_post_meta(get_the_ID(), '_healthedia_doi', true);
			?>
			<div class="p-6 border border-[#E0E0E0] rounded-xl hover:border-black transition-colors">
				<a href="<?php the_permalink(); ?>" class="font-sans font-bold text-xl block mb-2"><?php the_title(); ?></a>
				<div class="font-mono text-xs text-gray-500 mb-4">By <?php the_author(); ?> • <?php the_date(); ?> • <?php echo esc_html($doi); ?></div>
				<div class="font-sans text-gray-700 line-clamp-3 mb-4"><?php the_excerpt(); ?></div>
				<a href="<?php the_permalink(); ?>" class="font-mono text-xs uppercase tracking-wider border border-black px-4 py-1.5 rounded-full hover:bg-black hover:text-white transition-colors">Read Manuscript</a>
			</div>
			<?php endwhile; wp_reset_postdata(); else: ?>
			<p class="font-mono text-sm text-gray-500">No published articles available.</p>
			<?php endif; ?>
		</div>
	</main>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
