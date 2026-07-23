<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php'; ?>
<div class="max-w-4xl mx-auto py-12 px-4 bg-white text-[#111111]">
	<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
	<h1 class="text-4xl font-sans font-bold uppercase tracking-tight mb-8 border-b border-[#E0E0E0] pb-4"><?php the_title(); ?></h1>
	<div class="prose max-w-none font-sans text-gray-800 leading-relaxed">
		<?php the_content(); ?>
	</div>
	<?php endwhile; endif; ?>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
