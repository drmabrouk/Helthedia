<?php
// We bypass the standard WP login and use our own full page layout.
// Load custom header instead of get_header()
include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php';
?>
<div class="healthedia-auth h-[calc(100vh-64px)] flex items-center justify-center bg-gray-50 text-[#111111]">
	<div class="bg-white border border-[#E0E0E0] rounded-2xl p-10 max-w-md w-full shadow-sm">
		<div class="text-center mb-8">
			<h1 class="text-3xl font-sans font-bold uppercase tracking-tight mb-2">Access Healthedia</h1>
			<p class="font-mono text-sm text-gray-500">Academic & Clinical Portal</p>
		</div>

		<form id="auth-form-email-page" class="space-y-6">
			<div>
				<label for="auth-email-page" class="block font-mono text-xs uppercase text-gray-500 mb-2">Email Address</label>
				<input type="email" id="auth-email-page" required placeholder="name@university.edu" class="w-full border border-[#E0E0E0] rounded px-4 py-3 font-sans outline-none focus:border-black transition-colors">
			</div>
			<button type="submit" id="btn-request-otp" class="w-full bg-black text-white px-6 py-3 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors flex justify-center items-center gap-2">
				<span>Request OTP Link</span>
			</button>
		</form>

		<form id="auth-form-otp-page" class="space-y-6 hidden mt-4">
			<p class="font-mono text-xs text-green-600 text-center bg-green-50 p-2 rounded">OTP sent to your inbox. Check your spam folder.</p>
			<div>
				<label for="auth-otp-page" class="block font-mono text-xs uppercase text-gray-500 mb-2">One-Time Password (OTP)</label>
				<input type="text" id="auth-otp-page" required placeholder="000000" class="w-full border border-[#E0E0E0] rounded px-4 py-3 font-mono text-center tracking-widest text-xl outline-none focus:border-black transition-colors letter-spacing-widest">
			</div>
			<button type="submit" class="w-full bg-black text-white px-6 py-3 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors">Verify & Login</button>
			<div class="text-center mt-4">
				<button type="button" id="btn-resend-otp" class="font-mono text-xs text-gray-500 hover:text-black hover:underline">Resend Code</button>
			</div>
		</form>

		<div class="mt-8 border-t border-[#E0E0E0] pt-6 text-center">
			<p class="font-mono text-[10px] text-gray-400 uppercase leading-relaxed">
				By accessing this system, you agree to our strict scientific integrity guidelines and data policies. Unauthorized access is strictly prohibited.
			</p>
		</div>
	</div>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
