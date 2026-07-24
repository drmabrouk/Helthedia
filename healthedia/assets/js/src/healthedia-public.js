document.addEventListener('DOMContentLoaded', () => {

	const escapeHTML = (str) => {
		if (typeof str !== 'string') return str;
		return str.replace(/[&<>'"]/g,
			tag => ({
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				"'": '&#39;',
				'"': '&quot;'
			}[tag] || tag)
		);
	};

	// Submit Manuscript Form Logic
	const formSubmitMs = document.getElementById('form-submit-manuscript');
	if (formSubmitMs) {
		const fileInput = document.getElementById('ms-file');
		const fileNameDisplay = document.getElementById('ms-file-name');

		fileInput.addEventListener('change', (e) => {
			if (e.target.files.length > 0) {
				fileNameDisplay.innerText = 'Selected: ' + e.target.files[0].name;
				fileNameDisplay.classList.add('text-black', 'font-bold');
			}
		});

		formSubmitMs.addEventListener('submit', (e) => {
			e.preventDefault();
			const btn = document.getElementById('btn-submit-ms');
			const status = document.getElementById('ms-status');

			btn.innerText = 'Uploading...';
			btn.disabled = true;
			status.classList.add('hidden');

			const formData = new FormData();
			formData.append('title', document.getElementById('ms-title').value);
			formData.append('abstract', document.getElementById('ms-abstract').value);
			formData.append('specialty', document.getElementById('ms-specialty').value);
			formData.append('nct', document.getElementById('ms-nct').value);
			formData.append('manuscript', fileInput.files[0]);

			const nonce = window.healthediaPublicSettings?.nonce || '';

			fetch('/wp-json/healthedia/v1/manuscript/submit', {
				method: 'POST',
				headers: { 'X-WP-Nonce': nonce },
				body: formData
			})
			.then(res => res.json())
			.then(data => {
				btn.innerText = 'Submit to Editorial Board';
				btn.disabled = false;
				status.classList.remove('hidden', 'bg-red-50', 'text-red-600');

				if (data.success) {
					status.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
					status.innerText = data.message;
					formSubmitMs.reset();
					fileNameDisplay.innerText = 'Accepted formats: .PDF, .DOCX (Max 20MB)';
					fileNameDisplay.classList.remove('text-black', 'font-bold');
				} else {
					status.classList.add('bg-red-50', 'text-red-600', 'border', 'border-red-200');
					status.innerText = data.message || 'Submission failed.';
				}
			})
			.catch(() => {
				btn.innerText = 'Submit to Editorial Board';
				btn.disabled = false;
				status.classList.remove('hidden');
				status.classList.add('bg-red-50', 'text-red-600', 'border', 'border-red-200');
				status.innerText = 'Network error during upload.';
			});
		});
	}

	// Mobile Off-Canvas Menu
	const mobileMenuBtn = document.getElementById('mobile-menu-btn');
	const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
	let menuOpen = false;

	if (mobileMenuBtn && mobileMenuOverlay) {
		mobileMenuBtn.addEventListener('click', () => {
			menuOpen = !menuOpen;
			if (menuOpen) {
				mobileMenuOverlay.classList.remove('translate-x-full');
				document.body.style.overflow = 'hidden'; // Prevent background scrolling
				mobileMenuBtn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
			} else {
				mobileMenuOverlay.classList.add('translate-x-full');
				document.body.style.overflow = '';
				mobileMenuBtn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>';
			}
		});
	}


	// Accessibility Typography (Zoom)
	const articleContent = document.querySelector('.article-content');
	if (articleContent) {
		const btnIn = document.getElementById('btn-zoom-in');
		const btnOut = document.getElementById('btn-zoom-out');
		let currentZoom = 1;

		btnIn.addEventListener('click', () => {
			if (currentZoom < 1.5) { currentZoom += 0.1; articleContent.style.transform = `scale(${currentZoom})`; articleContent.style.transformOrigin = 'top left'; }
		});
		btnOut.addEventListener('click', () => {
			if (currentZoom > 0.8) { currentZoom -= 0.1; articleContent.style.transform = `scale(${currentZoom})`; articleContent.style.transformOrigin = 'top left'; }
		});
	}

	// Directory Grid Fetching
	const dirGrid = document.getElementById('directory-grid');
	const dirCount = document.getElementById('dir-count');
	if (dirGrid) {
		fetch('/wp-json/healthedia/v1/directories/researchers')
			.then(res => res.json())
			.then(response => {
				dirGrid.innerHTML = '';
				if (dirCount) dirCount.innerText = response.total || 0;
				if (!response.data || response.data.length === 0) {
					dirGrid.innerHTML = '<div class="col-span-full text-center py-12 font-mono text-sm text-gray-500">No researchers found.</div>';
					return;
				}

				response.data.forEach(user => {
					const card = document.createElement('div');
					card.className = 'border border-[#E0E0E0] rounded-2xl p-6 hover:border-black transition-colors bg-white shadow-sm flex flex-col items-center text-center';
					card.innerHTML = `
						<div class="w-16 h-16 bg-gray-100 rounded-full mb-4 flex items-center justify-center text-gray-400 font-sans font-bold text-xl border border-[#E0E0E0]">
							${escapeHTML(user.name).charAt(0)}
						</div>
						<div class="flex items-center justify-center gap-1.5 mb-1 w-full">
							<a href="${user.url}" class="font-sans font-bold text-xl hover:underline truncate">${escapeHTML(user.name)}</a>
							${user.verified ? '<svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' : ''}
						</div>

						<div class="bg-gray-50 border border-[#E0E0E0] rounded-xl p-3 w-full mt-4">
							<div class="font-mono text-[10px] text-gray-500 uppercase tracking-widest truncate mb-1">Spec: ${escapeHTML(user.specialty || 'Independent')}</div>
							<div class="font-mono text-[10px] text-gray-400 uppercase tracking-widest truncate">Views: <span class="text-black font-bold">${user.views}</span></div>
						</div>
					`;
					dirGrid.appendChild(card);
				});
			})
			.catch(() => {
				dirGrid.innerHTML = '<div class="col-span-full text-center py-12 text-red-500 font-mono text-sm">Error loading directory.</div>';
			});
	}

	// Dedicated Auth Page Logic (Redesigned Unified Layout)
	const loginContainer = document.getElementById('auth-login-container');
	const registerContainer = document.getElementById('auth-register-container');
	const otpContainer = document.getElementById('auth-otp-container');
	const alerts = document.getElementById('auth-alerts');

	if (loginContainer) {
		const tabLogin = document.getElementById('tab-login');
		const tabRegister = document.getElementById('tab-register');
		const verifyEmailInput = document.getElementById('verify-email-input');
		const verifyIsRegister = document.getElementById('verify-is-register');
		const btnCancelOtp = document.getElementById('btn-cancel-otp');

		const formLogin = document.getElementById('auth-form-login');
		const formRegister = document.getElementById('auth-form-register');
		const formOtpVerify = document.getElementById('auth-form-otp-verify');

		const forgotContainer = document.getElementById('auth-forgot-container');
		const resetContainer = document.getElementById('auth-reset-container');
		const formForgot = document.getElementById('auth-form-forgot');
		const formReset = document.getElementById('auth-form-reset');
		const btnForgotPassword = document.getElementById('btn-forgot-password');
		const btnBackToLogin = document.getElementById('btn-back-to-login');

		const showAlert = (msg, isError = true) => {
			alerts.classList.remove('hidden');
			if (isError) {
				alerts.className = 'mb-6 p-4 rounded font-mono text-xs text-center border border-red-200 bg-red-50 text-red-600';
			} else {
				alerts.className = 'mb-6 p-4 rounded font-mono text-xs text-center border border-green-200 bg-green-50 text-green-700';
			}
			alerts.innerText = msg;
		};

		if (tabLogin && tabRegister) {
			tabLogin.addEventListener('click', () => {
				tabLogin.classList.replace('text-gray-400', 'text-black');
				tabLogin.classList.replace('border-transparent', 'border-[#E0E0E0]');
				tabLogin.classList.add('bg-white', 'shadow-sm', 'font-bold');

				tabRegister.classList.replace('text-black', 'text-gray-400');
				tabRegister.classList.replace('border-[#E0E0E0]', 'border-transparent');
				tabRegister.classList.remove('bg-white', 'shadow-sm', 'font-bold');

				loginContainer.classList.remove('hidden');
				registerContainer.classList.add('hidden');
				alerts.classList.add('hidden');
			});

			tabRegister.addEventListener('click', () => {
				tabRegister.classList.replace('text-gray-400', 'text-black');
				tabRegister.classList.replace('border-transparent', 'border-[#E0E0E0]');
				tabRegister.classList.add('bg-white', 'shadow-sm', 'font-bold');

				tabLogin.classList.replace('text-black', 'text-gray-400');
				tabLogin.classList.replace('border-[#E0E0E0]', 'border-transparent');
				tabLogin.classList.remove('bg-white', 'shadow-sm', 'font-bold');

				registerContainer.classList.remove('hidden');
				loginContainer.classList.add('hidden');
				alerts.classList.add('hidden');
			});
		}

		const requestOtp = (form, isReg, isForgot = false) => {
			let btnId = 'btn-login-submit';
			if (isReg) btnId = 'btn-register-submit';
			if (isForgot) btnId = 'btn-forgot-submit';

			const btn = document.getElementById(btnId);
			const originalText = btn.innerHTML;
			btn.innerHTML = '<span class="animate-pulse">Processing...</span>';
			btn.disabled = true;
			alerts.classList.add('hidden');

			const formData = new FormData(form);
			const data = Object.fromEntries(formData.entries());

			fetch('/wp-json/healthedia/v1/auth/request-otp', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify(data)
			}).then(res => res.json()).then(resData => {
				btn.innerHTML = originalText;
				btn.disabled = false;
				if (resData.success) {
					verifyEmailInput.value = data.email;
					verifyIsRegister.value = isReg;
					if (isForgot) verifyIsRegister.value = 'forgot';

					otpContainer.classList.remove('hidden', 'translate-y-full');
					setTimeout(() => document.getElementById('auth-otp-page').focus(), 300);
				} else {
					showAlert(resData.message || 'Error requesting OTP.');
				}
			}).catch(() => {
				btn.innerHTML = originalText;
				btn.disabled = false;
				showAlert('Network error communicating with authentication server.');
			});
		};

		if (btnForgotPassword && btnBackToLogin) {
			btnForgotPassword.addEventListener('click', () => {
				loginContainer.classList.add('hidden');
				forgotContainer.classList.remove('hidden');
				alerts.classList.add('hidden');
			});
			btnBackToLogin.addEventListener('click', () => {
				forgotContainer.classList.add('hidden');
				loginContainer.classList.remove('hidden');
				alerts.classList.add('hidden');
			});
		}

		if (formLogin) {
			formLogin.addEventListener('submit', (e) => {
				e.preventDefault();
				const btn = document.getElementById('btn-login-submit');
				const originalText = btn.innerHTML;
				btn.innerHTML = '<span class="animate-pulse">Authenticating...</span>';
				btn.disabled = true;
				alerts.classList.add('hidden');

				const formData = new FormData(formLogin);
				const data = Object.fromEntries(formData.entries());

				fetch('/wp-json/healthedia/v1/auth/login-standard', {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify(data)
				}).then(res => res.json()).then(resData => {
					if (resData.success) {
						window.location.href = '/';
					} else {
						btn.innerHTML = originalText;
						btn.disabled = false;
						showAlert(resData.message || 'Invalid credentials.');
					}
				}).catch(() => {
					btn.innerHTML = originalText;
					btn.disabled = false;
					showAlert('Network error communicating with authentication server.');
				});
			});
		}

		if (formRegister) {
			formRegister.addEventListener('submit', (e) => {
				e.preventDefault();
				requestOtp(formRegister, true, false);
			});
		}

		if (formForgot) {
			formForgot.addEventListener('submit', (e) => {
				e.preventDefault();
				requestOtp(formForgot, false, true);
			});
		}

		if (formOtpVerify) {
			formOtpVerify.addEventListener('submit', (e) => {
				e.preventDefault();
				const btn = document.getElementById('btn-verify-submit');
				const originalText = btn.innerHTML;
				btn.innerHTML = '<span class="animate-pulse">Verifying...</span>';
				btn.disabled = true;
				alerts.classList.add('hidden');

				const formData = new FormData(formOtpVerify);
				const data = Object.fromEntries(formData.entries());

				fetch('/wp-json/healthedia/v1/auth/verify-otp', {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify(data)
				}).then(res => res.json()).then(resData => {
					btn.innerHTML = originalText;
					btn.disabled = false;

					if (resData.success) {
						if (data.is_register === 'forgot') {
							// Transition to reset password UI
							otpContainer.classList.add('hidden');
							forgotContainer.classList.add('hidden');
							resetContainer.classList.remove('hidden');
							document.getElementById('reset-email').value = data.email;
							document.getElementById('reset-otp').value = data.otp;
							document.getElementById('auth-otp-page').value = '';
							showAlert('Identity verified. Please set your new password.', false);
						} else {
							window.location.href = '/';
						}
					} else {
						document.getElementById('auth-otp-page').value = '';
						showAlert(resData.message || 'Invalid OTP.');
						otpContainer.classList.add('translate-y-full');
						setTimeout(() => otpContainer.classList.add('hidden'), 300);
					}
				}).catch(() => {
					btn.innerHTML = originalText;
					btn.disabled = false;
					showAlert('Network error verifying OTP.');
					otpContainer.classList.add('translate-y-full');
					setTimeout(() => otpContainer.classList.add('hidden'), 300);
				});
			});
		}

		if (formReset) {
			formReset.addEventListener('submit', (e) => {
				e.preventDefault();
				const btn = document.getElementById('btn-reset-submit');
				const originalText = btn.innerHTML;
				btn.innerHTML = '<span class="animate-pulse">Saving...</span>';
				btn.disabled = true;
				alerts.classList.add('hidden');

				const formData = new FormData(formReset);
				const data = Object.fromEntries(formData.entries());

				if (data.password !== data.password_confirm) {
					showAlert('Passwords do not match.');
					btn.innerHTML = originalText;
					btn.disabled = false;
					return;
				}

				fetch('/wp-json/healthedia/v1/auth/reset-password', {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify(data)
				}).then(res => res.json()).then(resData => {
					if (resData.success) {
						window.location.href = '/';
					} else {
						btn.innerHTML = originalText;
						btn.disabled = false;
						showAlert(resData.message || 'Error resetting password.');
					}
				}).catch(() => {
					btn.innerHTML = originalText;
					btn.disabled = false;
					showAlert('Network error.');
				});
			});
		}

		if (btnCancelOtp) {
			btnCancelOtp.addEventListener('click', () => {
				otpContainer.classList.add('translate-y-full');
				setTimeout(() => {
					otpContainer.classList.add('hidden');
					document.getElementById('auth-otp-page').value = '';
				}, 300);
			});
		}
	}

	// Password Toggle Logic
	document.querySelectorAll('.toggle-password').forEach(btn => {
		btn.addEventListener('click', function() {
			const targetId = this.getAttribute('data-target');
			const input = document.getElementById(targetId);
			if (input) {
				if (input.type === 'password') {
					input.type = 'text';
					this.innerHTML = '<svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a10.05 10.05 0 015.71-1.58c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>';
				} else {
					input.type = 'password';
					this.innerHTML = '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
				}
			}
		});
	});

	// Notifications Dropdown Logic
	const btnNotifications = document.getElementById('btn-notifications');
	const notificationsDropdown = document.getElementById('notifications-dropdown');
	const notificationsList = document.getElementById('notifications-list');
	const notificationBadge = document.getElementById('notification-badge');
	const btnMarkAllRead = document.getElementById('btn-mark-all-read');

	if (btnNotifications && notificationsDropdown) {
		let notificationsOpen = false;

		const fetchNotifications = async () => {
			if (!window.healthediaPublicSettings || !window.healthediaPublicSettings.nonce) return;
			try {
				const res = await fetch('/wp-json/healthedia/v1/notifications', {
					headers: { 'X-WP-Nonce': window.healthediaPublicSettings.nonce }
				});
				if (!res.ok) return;
				const data = await res.json();

				const unreadCount = data.filter(n => !n.is_read).length;
				if (unreadCount > 0) {
					notificationBadge.classList.remove('hidden');
				} else {
					notificationBadge.classList.add('hidden');
				}

				if (data.length === 0) {
					notificationsList.innerHTML = '<div class="p-4 text-center text-gray-500 font-mono text-xs">No notifications.</div>';
					return;
				}

				notificationsList.innerHTML = data.map(n => `
					<div class="p-4 flex flex-col gap-1 ${n.is_read ? 'opacity-60' : 'bg-blue-50/30'}">
						<div class="flex justify-between items-start gap-2">
							<a href="${n.link || '#'}" class="font-sans text-sm ${n.is_read ? 'text-gray-700' : 'text-black font-bold hover:underline'}">${escapeHTML(n.message)}</a>
							${!n.is_read ? '<span class="w-2 h-2 bg-blue-500 rounded-full shrink-0 mt-1.5"></span>' : ''}
						</div>
						<div class="font-mono text-[10px] text-gray-400 uppercase tracking-widest">${new Date(n.date).toLocaleString()}</div>
					</div>
				`).join('');

			} catch (e) {
				console.error(e);
			}
		};

		// Initial Fetch
		fetchNotifications();

		btnNotifications.addEventListener('click', (e) => {
			e.stopPropagation();
			notificationsOpen = !notificationsOpen;
			if (notificationsOpen) {
				notificationsDropdown.classList.remove('hidden');
				fetchNotifications(); // Refresh on open
			} else {
				notificationsDropdown.classList.add('hidden');
			}
		});

		document.addEventListener('click', (e) => {
			if (notificationsOpen && !notificationsDropdown.contains(e.target)) {
				notificationsDropdown.classList.add('hidden');
				notificationsOpen = false;
			}
		});

		if (btnMarkAllRead) {
			btnMarkAllRead.addEventListener('click', async (e) => {
				e.stopPropagation();
				e.preventDefault();
				btnMarkAllRead.innerText = 'Marking...';
				try {
					await fetch('/wp-json/healthedia/v1/notifications/mark-read', {
						method: 'POST',
						headers: {
							'X-WP-Nonce': window.healthediaPublicSettings.nonce,
							'Content-Type': 'application/json'
						}
					});
					btnMarkAllRead.innerText = 'Mark All Read';
					fetchNotifications();
				} catch (e) {
					btnMarkAllRead.innerText = 'Mark All Read';
				}
			});
		}
	}
});
