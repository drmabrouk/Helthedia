<?php
// Load custom header instead of get_header()
include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-header.php';

$maintenance_mode = get_option('healthedia_auth_maintenance', 'no');
$registration_enabled = get_option('healthedia_enable_registration', 'yes');

if ($maintenance_mode === 'yes') {
	echo '<div class="h-[calc(100vh-64px)] flex items-center justify-center bg-gray-50 text-[#111111] px-4">';
	echo '  <div class="text-center">';
	echo '    <h1 class="text-3xl font-sans font-bold uppercase tracking-tight mb-2">System Maintenance</h1>';
	echo '    <p class="font-mono text-sm text-gray-500 mb-6">Authentication services are currently offline for development.</p>';
	echo '    <p class="font-mono text-[10px] text-gray-400 uppercase tracking-widest">Redirecting to homepage...</p>';
	echo '  </div>';
	echo '  <script>setTimeout(function(){ window.location.href = "'.esc_url(home_url('/')).'"; }, 3000);</script>';
	echo '</div>';
	include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php';
	return;
}
?>

<div class="healthedia-auth min-h-[calc(100vh-64px)] flex items-center justify-center bg-gray-50 text-[#111111] py-12 px-4 relative">
	<div class="bg-white border border-[#E0E0E0] rounded-2xl p-6 md:p-10 max-w-lg w-full shadow-sm relative overflow-hidden">

		<div id="auth-alerts" class="hidden mb-6 p-4 rounded font-mono text-xs text-center border"></div>

		<?php if ($registration_enabled === 'yes'): ?>
		<!-- Tabs -->
		<div class="flex bg-gray-50 rounded-xl p-1 mb-10 border border-[#E0E0E0]">
			<button id="tab-login" class="flex-1 py-2 rounded-lg font-mono text-[10px] uppercase tracking-widest text-black bg-white border border-[#E0E0E0] shadow-sm font-bold transition-all">Login</button>
			<button id="tab-register" class="flex-1 py-2 rounded-lg font-mono text-[10px] uppercase tracking-widest text-gray-400 border border-transparent hover:text-black transition-all">Create Account</button>
		</div>
		<?php else: ?>
		<div class="mb-10 text-center font-mono text-xs text-red-500 uppercase tracking-widest bg-red-50 py-2 rounded border border-red-200">
			New Registrations Disabled
		</div>
		<?php endif; ?>

		<!-- Login Form Container -->
		<div id="auth-login-container">
			<div class="text-center mb-8">
				<h2 class="text-2xl font-sans font-bold uppercase tracking-tight mb-2">Login To Archive</h2>
				<p class="font-mono text-[10px] text-gray-500 uppercase tracking-widest leading-relaxed">Access global health, physiology, and sports biomechanics indices.</p>
			</div>

			<form id="auth-form-login" class="space-y-6">
				<input type="hidden" name="is_register" value="false">
				<div>
					<input type="email" name="email" id="login-email" required placeholder="Institutional Email Address" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-3 font-sans text-sm outline-none focus:border-black transition-colors bg-white">
				</div>
				<div class="text-right">
					<button type="submit" id="btn-login-submit" class="w-full bg-black text-white px-6 py-4 rounded-xl font-mono uppercase text-[10px] font-bold tracking-widest hover:bg-gray-800 transition-colors flex justify-center items-center gap-2">
						<span>Sign In To Archive</span>
					</button>
				</div>
			</form>
		</div>

		<?php if ($registration_enabled === 'yes'): ?>
		<!-- Registration Form Container (Multi-step) -->
		<div id="auth-register-container" class="hidden">
			<div class="text-center mb-8">
				<h2 class="text-2xl font-sans font-bold uppercase tracking-tight mb-2">Create Investigator Account</h2>
				<p class="font-mono text-[10px] text-gray-500 uppercase tracking-widest leading-relaxed">Join our verified directory of active researchers and clinicians.</p>
			</div>

			<form id="auth-form-register" class="space-y-4">
				<input type="hidden" name="is_register" value="true">

				<div class="flex gap-2">
					<select name="title" class="w-24 border border-[#E0E0E0] rounded-xl px-2 py-3 font-sans text-sm outline-none focus:border-black transition-colors bg-white">
						<option value="Dr.">Dr.</option>
						<option value="Prof.">Prof.</option>
						<option value="Mr.">Mr.</option>
						<option value="Ms.">Ms.</option>
					</select>
					<input type="text" name="name" required placeholder="Full Academic Name" class="flex-1 border border-[#E0E0E0] rounded-xl px-4 py-3 font-sans text-sm outline-none focus:border-black transition-colors bg-white">
				</div>

				<div>
					<input type="email" name="email" id="register-email" required placeholder="Institutional Email Address" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-3 font-sans text-sm outline-none focus:border-black transition-colors bg-white">
				</div>

				<div>
					<input type="text" name="specialty" required placeholder="Primary Specialty (e.g. Physiology)" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-3 font-sans text-sm outline-none focus:border-black transition-colors bg-white">
				</div>

				<div class="flex flex-col md:flex-row gap-4">
					<input type="text" name="institution" required placeholder="Institutional Affiliation" class="w-full md:w-1/2 border border-[#E0E0E0] rounded-xl px-4 py-3 font-sans text-sm outline-none focus:border-black transition-colors bg-white">
					<input type="text" name="country" required placeholder="Country of Origin" class="w-full md:w-1/2 border border-[#E0E0E0] rounded-xl px-4 py-3 font-sans text-sm outline-none focus:border-black transition-colors bg-white">
				</div>

				<div>
					<input type="text" name="orcid" placeholder="ORCID Identifier iD (e.g. 0000-xxxx-xxxx-xxxx)" class="w-full border border-[#E0E0E0] rounded-xl px-4 py-3 font-mono text-xs outline-none focus:border-black transition-colors bg-white">
				</div>

				<div class="pt-2 pb-2">
					<label class="flex items-start gap-3 cursor-pointer">
						<input type="checkbox" required class="mt-0.5 w-4 h-4 text-black border-gray-300 rounded focus:ring-black">
						<span class="font-mono text-[9px] text-gray-500 uppercase tracking-widest leading-relaxed">I hold an active clinical practice, university research post, or laboratory affiliation.</span>
					</label>
				</div>

				<div>
					<button type="submit" id="btn-register-submit" class="w-full bg-black text-white px-6 py-4 rounded-xl font-mono uppercase text-[10px] font-bold tracking-widest hover:bg-gray-800 transition-colors">
						Verify Email & Register
					</button>
				</div>
			</form>
		</div>
		<?php endif; ?>

		<!-- OTP Verification Overlay Container -->
		<div id="auth-otp-container" class="absolute inset-0 bg-white z-20 flex flex-col items-center justify-center p-8 hidden transform transition-transform duration-300 translate-y-full">
			<div class="text-center w-full max-w-sm">
				<h2 class="text-2xl font-sans font-bold uppercase tracking-tight mb-2">Verify Identity</h2>
				<p class="font-mono text-[10px] text-green-700 bg-green-50 uppercase tracking-widest p-3 rounded-lg border border-green-200 mb-8">6-digit OTP sent to your inbox. Valid for 15 minutes.</p>

				<form id="auth-form-otp-verify" class="space-y-6">
					<input type="hidden" name="email" id="verify-email-input" value="">
					<input type="hidden" name="is_register" id="verify-is-register" value="false">
					<div>
						<input type="text" name="otp" id="auth-otp-page" required placeholder="000000" maxlength="6" pattern="\d{6}" class="w-full border border-black rounded-xl px-4 py-4 font-mono text-center tracking-[1em] text-2xl outline-none shadow-sm focus:ring-2 focus:ring-black">
					</div>
					<button type="submit" id="btn-verify-submit" class="w-full bg-black text-white px-6 py-4 rounded-xl font-mono uppercase text-[10px] font-bold tracking-widest hover:bg-gray-800 transition-colors">
						Confirm & Access
					</button>
				</form>
				<button type="button" id="btn-cancel-otp" class="mt-8 font-mono text-[10px] uppercase tracking-widest text-gray-400 hover:text-black hover:underline">
					← Cancel & Go Back
				</button>
			</div>
		</div>

	</div>
</div>
<?php include HEALTHEDIA_PLUGIN_DIR . 'public/views/layout-footer.php'; ?>
