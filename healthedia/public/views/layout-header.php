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
	<?php if(is_user_logged_in()): ?>
	<script>window.healthediaPublicSettings = { nonce: "<?php echo wp_create_nonce("wp_rest"); ?>" };</script>
	<?php endif; ?>
</head>
<body <?php body_class('bg-white text-[#111111] antialiased min-h-screen flex flex-col'); ?>>

	<!-- Global Header (Archival Minimalist) -->
	<header class="border-b border-[#E0E0E0] sticky top-0 bg-white/90 backdrop-blur-sm z-40">
		<div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">

			<a href="<?php echo home_url(); ?>" class="font-sans font-bold text-xl tracking-tighter uppercase z-50">Healthedia</a>

			<!-- Search Form -->
			<div class="hidden md:block flex-grow max-w-sm mx-8 relative">
				<form action="<?php echo home_url('/archive-search'); ?>" method="GET" class="w-full">
					<input type="text" name="q" placeholder="Search archive..." class="w-full bg-gray-50 border border-[#E0E0E0] rounded-full py-1.5 px-4 pr-10 font-sans text-sm outline-none focus:border-black focus:bg-white transition-colors">
					<button type="submit" class="absolute right-3 top-1.5 text-gray-400 hover:text-black">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
					</button>
				</form>
			</div>

			<!-- Desktop Nav -->
			<nav class="hidden md:flex items-center gap-6 font-mono text-sm uppercase tracking-wider ml-auto mr-6">
				<a href="<?php echo home_url('/journal'); ?>" class="text-gray-500 hover:text-black transition-colors">Journal</a>
				<a href="<?php echo home_url('/directory'); ?>" class="text-gray-500 hover:text-black transition-colors">Directory</a>
			</nav>

			<!-- Desktop Actions -->
			<div class="hidden md:flex items-center gap-4">
				<?php if ( is_user_logged_in() ) : ?>
					<?php if ( current_user_can( 'manage_options' ) ) : ?>
						<a href="<?php echo home_url('/healthedia-admin'); ?>" class="font-mono text-xs uppercase tracking-wider bg-blue-900 text-white px-3 py-1.5 rounded-full hover:bg-blue-800 transition-colors flex items-center gap-1">
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20v-4"></path></svg>
							Dashboard
						</a>
					<?php endif; ?>
					<a href="<?php echo home_url('/account-settings'); ?>" class="font-mono text-xs uppercase tracking-wider border border-[#E0E0E0] px-4 py-1.5 rounded-full hover:border-black transition-colors">Portal</a>
				<?php else: ?>
					<a href="<?php echo home_url('/login'); ?>" class="font-mono text-xs uppercase tracking-wider bg-black text-white px-5 py-2 rounded-full hover:bg-gray-800 transition-colors flex items-center justify-center">Login / Register</a>
				<?php endif; ?>
			</div>

			<!-- Mobile Menu Button -->
			<button id="mobile-menu-btn" class="md:hidden flex items-center justify-center w-11 h-11 text-black z-50 focus:outline-none" aria-label="Toggle Menu">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
			</button>
		</div>
	</header>

	<!-- Mobile Off-Canvas Menu -->
	<div id="mobile-menu-overlay" class="fixed inset-0 bg-white z-40 transform translate-x-full transition-transform duration-300 md:hidden flex flex-col pt-24 px-6 pb-8 overflow-y-auto">
		<nav class="flex flex-col gap-6 font-mono text-xl uppercase tracking-wider mb-8 border-b border-[#E0E0E0] pb-8">
			<a href="<?php echo home_url('/journal'); ?>" class="text-black hover:text-gray-500 transition-colors block">Scientific Journal</a>
			<a href="<?php echo home_url('/directory'); ?>" class="text-black hover:text-gray-500 transition-colors block">Global Directory</a>
			<a href="<?php echo home_url('/academies'); ?>" class="text-black hover:text-gray-500 transition-colors block">Academies</a>
		</nav>

		<div class="flex flex-col gap-4 mt-auto">
			<?php if ( is_user_logged_in() ) : ?>
				<?php if ( current_user_can( 'manage_options' ) ) : ?>
					<a href="<?php echo home_url('/healthedia-admin'); ?>" class="font-mono text-sm uppercase tracking-wider bg-blue-900 text-white w-full py-4 rounded-xl flex items-center justify-center gap-2">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20v-4"></path></svg>
						System Dashboard
					</a>
				<?php endif; ?>
				<a href="<?php echo home_url('/account-settings'); ?>" class="font-mono text-sm uppercase tracking-wider border border-[#E0E0E0] w-full py-4 rounded-xl text-center">Member Portal</a>
				<a href="<?php echo wp_logout_url(home_url()); ?>" class="font-mono text-sm uppercase tracking-wider bg-gray-100 text-gray-500 w-full py-4 rounded-xl text-center mt-2">Logout</a>
			<?php else: ?>
				<a href="<?php echo home_url('/login'); ?>" class="font-mono text-sm uppercase tracking-wider bg-black text-white w-full py-4 rounded-xl flex items-center justify-center">Login / Register</a>
			<?php endif; ?>
		</div>
	</div>

	<!-- Main Content Wrapper -->
	<div class="flex-grow">
