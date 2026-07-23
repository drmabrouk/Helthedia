<?php
include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php';
$user_id = get_query_var('healthedia_profile');
$user = get_userdata($user_id);

if (!$user) {
	echo "<div class='text-center py-20 font-sans'>Researcher not found.</div>";
	include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php';
	return;
}

require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Profiles/class-profile-model.php';
require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Profiles/class-profile-verification.php';
$metrics = Healthedia_Profile_Model::get_metrics($user_id);
$is_verified = Healthedia_Profile_Verification::is_verified($user_id);
$specialty = get_user_meta($user_id, '_healthedia_specialty', true);
?>
<div class="healthedia-profile max-w-5xl mx-auto py-12 px-4 bg-white text-[#111111]">

	<!-- Header -->
	<header class="border-b border-[#E0E0E0] pb-8 mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
		<div>
			<div class="flex items-center gap-3 mb-2">
				<h1 class="text-4xl font-sans font-bold uppercase tracking-tight"><?php echo esc_html($user->display_name); ?></h1>
				<?php if ($is_verified): ?>
				<span class="bg-black text-white px-2 py-0.5 rounded text-xs font-mono uppercase tracking-widest">Verified</span>
				<?php endif; ?>
			</div>
			<p class="font-mono text-sm text-gray-500 uppercase"><?php echo esc_html($specialty ?: 'Independent Researcher'); ?></p>
		</div>
		<div class="flex gap-4 font-mono text-sm text-right">
			<div>
				<div class="text-2xl font-sans font-bold"><?php echo number_format_i18n($metrics->views); ?></div>
				<div class="text-gray-500 uppercase text-xs tracking-wider">Profile Views</div>
			</div>
			<div>
				<div class="text-2xl font-sans font-bold"><?php echo number_format_i18n($metrics->citations); ?></div>
				<div class="text-gray-500 uppercase text-xs tracking-wider">Citations</div>
			</div>
		</div>
	</header>

	<!-- Body -->
	<div class="grid grid-cols-1 md:grid-cols-3 gap-12">
		<div class="md:col-span-2">
			<h2 class="font-sans font-bold uppercase tracking-wider border-b border-[#E0E0E0] pb-2 mb-4 text-sm">Biography</h2>
			<div class="font-sans leading-relaxed text-gray-700">
				<?php echo wpautop(esc_html(get_user_meta($user_id, 'description', true))); ?>
			</div>

			<h2 class="font-sans font-bold uppercase tracking-wider border-b border-[#E0E0E0] pb-2 mb-4 mt-12 text-sm">Recent Publications</h2>
			<div class="space-y-4">
				<?php
				$args = array('post_type' => 'healthedia_article', 'author' => $user_id, 'posts_per_page' => 5);
				$query = new WP_Query($args);
				if ($query->have_posts()): while ($query->have_posts()): $query->the_post();
				?>
				<div class="p-4 border border-[#E0E0E0] rounded-lg hover:border-black transition-colors">
					<a href="<?php the_permalink(); ?>" class="font-sans font-bold text-lg mb-1 block"><?php the_title(); ?></a>
					<div class="font-mono text-xs text-gray-500"><?php the_date(); ?> • <?php echo get_post_meta(get_the_ID(), '_healthedia_doi', true); ?></div>
				</div>
				<?php endwhile; wp_reset_postdata(); else: ?>
				<p class="font-mono text-sm text-gray-500">No publications found.</p>
				<?php endif; ?>
			</div>
		</div>

		<aside>
			<div class="p-6 bg-gray-50 rounded-xl border border-[#E0E0E0]">
				<h3 class="font-sans font-bold uppercase tracking-wider mb-4 text-sm">Credentials</h3>
				<ul class="font-mono text-sm space-y-3 text-gray-700">
					<li><strong>Institution:</strong> <?php echo esc_html(get_user_meta($user_id, '_healthedia_institution', true) ?: 'N/A'); ?></li>
					<li><strong>Country:</strong> <?php echo esc_html(get_user_meta($user_id, '_healthedia_country', true) ?: 'N/A'); ?></li>
					<li><strong>ORCID:</strong> <?php echo esc_html(get_user_meta($user_id, '_healthedia_orcid', true) ?: 'N/A'); ?></li>
				</ul>
			</div>
		</aside>
	</div>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
