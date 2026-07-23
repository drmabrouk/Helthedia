<?php
// Load custom header instead of get_header()
include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php';
?>
<?php while ( have_posts() ) : the_post();
	$doi = get_post_meta( get_the_ID(), '_healthedia_doi', true );
?>
<div class="healthedia-journal max-w-7xl mx-auto flex flex-col md:flex-row gap-8 py-12 px-4 md:px-8 bg-white text-[#111111]">

	<!-- Sidebar -->
	<aside class="w-full md:w-1/4 border-r border-[#E0E0E0] pr-6">
		<div class="sticky top-8">
			<h3 class="font-sans font-bold uppercase tracking-wider mb-4 text-sm border-b border-[#E0E0E0] pb-2">Journal Navigation</h3>
			<ul class="font-mono text-sm space-y-3">
				<li><a href="#" class="text-gray-500 hover:text-black">Current Issue</a></li>
				<li><a href="#" class="text-gray-500 hover:text-black">All Issues</a></li>
				<li><a href="#" class="text-gray-500 hover:text-black">Editorial Board</a></li>
				<li><a href="#" class="text-gray-500 hover:text-black">Submit Research</a></li>
			</ul>

			<div class="mt-12">
				<h3 class="font-sans font-bold uppercase tracking-wider mb-4 text-sm border-b border-[#E0E0E0] pb-2">Article Tools</h3>
				<div class="flex gap-2">
					<button id="btn-zoom-in" class="p-2 border border-[#E0E0E0] rounded hover:border-black text-xs font-mono">A+</button>
					<button id="btn-zoom-out" class="p-2 border border-[#E0E0E0] rounded hover:border-black text-xs font-mono">A-</button>
				</div>
			</div>
		</div>
	</aside>

	<!-- Main Content -->
	<main class="w-full md:w-3/4 article-content transition-all duration-300">
		<div class="flex gap-2 mb-4 font-mono text-xs">
			<span class="px-2 py-1 bg-[#111111] text-white rounded-full uppercase tracking-wider">Peer-Reviewed</span>
			<span class="px-2 py-1 border border-[#111111] rounded-full uppercase tracking-wider">Open Access</span>
		</div>

		<h1 class="text-4xl md:text-5xl font-sans font-bold leading-tight tracking-tighter mb-6"><?php the_title(); ?></h1>

		<div class="flex flex-col gap-2 mb-10 border-y border-[#E0E0E0] py-4">
			<div class="font-sans text-sm">
				<strong>Authors:</strong> <a href="#" class="underline hover:no-underline"><?php the_author(); ?></a>
			</div>
			<?php if ( $doi ) : ?>
			<div class="font-mono text-xs text-gray-500">
				<strong>DOI:</strong> <?php echo esc_html( $doi ); ?>
			</div>
			<?php endif; ?>
			<div class="font-mono text-xs text-gray-500">
				<strong>Published:</strong> <?php the_date(); ?>
			</div>
		</div>

		<div class="prose max-w-none font-sans text-lg leading-relaxed text-gray-800">
			<?php the_content(); ?>
		</div>
	</main>
</div>
<?php endwhile; ?>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
