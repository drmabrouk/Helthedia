<?php
// Since this plugin replaces the frontend heavily, this is an optional layout wrapper.
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title('|', true, 'right'); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class('bg-white text-[#111111] antialiased'); ?>>

	<!-- Global Header (Archival Minimalist) -->
	<header class="border-b border-[#E0E0E0] sticky top-0 bg-white/90 backdrop-blur-sm z-40">
		<div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
			<a href="<?php echo home_url(); ?>" class="font-sans font-bold text-xl tracking-tighter uppercase">Healthedia</a>

			<nav class="hidden md:flex gap-6 font-mono text-sm uppercase tracking-wider">
				<a href="<?php echo home_url('/journal'); ?>" class="text-gray-500 hover:text-black transition-colors">Journal</a>
				<a href="<?php echo home_url('/directories'); ?>" class="text-gray-500 hover:text-black transition-colors">Directory</a>
			</nav>

			<div class="flex items-center gap-4">
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo home_url('/profile/' . get_current_user_id()); ?>" class="font-mono text-xs uppercase tracking-wider border border-[#E0E0E0] px-3 py-1 rounded-full hover:border-black transition-colors">My Profile</a>
				<?php else: ?>
					<button onclick="document.getElementById('healthedia-auth-modal').classList.remove('hidden')" class="font-mono text-xs uppercase tracking-wider bg-black text-white px-4 py-1.5 rounded-full hover:bg-gray-800 transition-colors">Login / Register</button>
				<?php endif; ?>
			</div>
		</div>
	</header>
