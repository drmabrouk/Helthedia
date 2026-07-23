const escapeHTML = (str) => {
	if (!str) return '';
	return String(str).replace(/[&<>'"]/g,
		tag => ({
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			"'": '&#39;',
			'"': '&quot;'
		}[tag] || tag)
	);
};

document.addEventListener('DOMContentLoaded', () => {

	// Manuscript Submission Logic
	const msForm = document.getElementById('form-submit-manuscript');
	if (msForm) {
		const msFile = document.getElementById('ms-file');
		const msFileName = document.getElementById('ms-file-name');

		msFile.addEventListener('change', (e) => {
			if(e.target.files.length > 0) {
				msFileName.innerText = 'Selected: ' + e.target.files[0].name;
				msFileName.classList.add('text-black', 'font-bold');
			}
		});

		msForm.addEventListener('submit', (e) => {
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
			formData.append('manuscript', msFile.files[0]);

			// We need the nonce for authenticated requests.
			// We can get it if we print it in the header for logged in users.
			const nonce = window.healthediaPublicSettings?.nonce || '';

			fetch('/wp-json/healthedia/v1/manuscript/submit', {
				method: 'POST',
				headers: {
					'X-WP-Nonce': nonce
				},
				body: formData
			})
			.then(res => res.json())
			.then(data => {
				btn.innerText = 'Submit to Editorial Board';
				btn.disabled = false;
				status.classList.remove('hidden', 'bg-red-50', 'text-red-600');

				if(data.success) {
					status.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
					status.innerText = data.message;
					msForm.reset();
					msFileName.innerText = 'Accepted formats: .PDF, .DOCX (Max 20MB)';
					msFileName.classList.remove('text-black', 'font-bold');
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

	// Gateway Search Logic
	const gatewaySearchInput = document.getElementById('gateway-search-input');
	const gatewaySearchResults = document.getElementById('gateway-search-results');
	const gatewaySearchList = document.getElementById('gateway-search-list');
	let searchTimeout = null;
	let currentTypeFilter = '';

	const performSearch = (query, type) => {
		gatewaySearchList.innerHTML = '<li class="px-6 py-4 text-gray-500 font-mono text-sm text-center animate-pulse">Searching archive...</li>';
		gatewaySearchResults.classList.remove('hidden');

		fetch(`/wp-json/healthedia/v1/search?q=${encodeURIComponent(query)}&type=${encodeURIComponent(type)}`)
			.then(res => res.json())
			.then(data => {
				gatewaySearchList.innerHTML = '';
				if (data.results && data.results.length > 0) {
					data.results.forEach(result => {
						const li = document.createElement('li');
						li.className = 'px-6 py-3 hover:bg-gray-50 border-b border-[#E0E0E0] last:border-0 transition-colors';
						li.innerHTML = `
							<a href="${result.url}" class="block">
								<div class="font-bold text-black font-sans">${escapeHTML(result.title)}</div>
								<div class="font-mono text-[10px] text-gray-500 uppercase tracking-widest mt-1">${result.object_type}</div>
							</a>
						`;
						gatewaySearchList.appendChild(li);
					});
				} else {
					gatewaySearchList.innerHTML = '<li class="px-6 py-4 text-gray-500 font-mono text-sm text-center">No results found in archive.</li>';
				}
			})
			.catch(() => {
				gatewaySearchList.innerHTML = '<li class="px-6 py-4 text-red-500 font-mono text-sm text-center">Error communicating with server.</li>';
			});
	};

	if (gatewaySearchInput) {
		gatewaySearchInput.addEventListener('input', (e) => {
			const query = e.target.value.trim();
			clearTimeout(searchTimeout);
			if (query.length > 2) {
				searchTimeout = setTimeout(() => {
					performSearch(query, currentTypeFilter);
				}, 300);
			} else {
				gatewaySearchResults.classList.add('hidden');
			}
		});

		document.addEventListener('click', (e) => {
			if (!gatewaySearchInput.contains(e.target) && !gatewaySearchResults.contains(e.target)) {
				gatewaySearchResults.classList.add('hidden');
			}
		});

		const searchTags = document.querySelectorAll('.search-tag');
		searchTags.forEach(tag => {
			tag.addEventListener('click', (e) => {
				searchTags.forEach(t => t.classList.remove('bg-black', 'text-white', 'border-black'));
				searchTags.forEach(t => t.classList.add('text-gray-500', 'border-[#E0E0E0]'));

				e.target.classList.remove('text-gray-500', 'border-[#E0E0E0]');
				e.target.classList.add('bg-black', 'text-white', 'border-black');

				currentTypeFilter = e.target.getAttribute('data-type');

				const query = gatewaySearchInput.value.trim();
				if (query.length > 2) {
					performSearch(query, currentTypeFilter);
				}
			});
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

	// Dedicated Auth Page Logic
	const authFormEmailPage = document.getElementById('auth-form-email-page');
	const authFormOtpPage = document.getElementById('auth-form-otp-page');
	const authEmailInputPage = document.getElementById('auth-email-page');

	if (authFormEmailPage) {
		const tabLogin = document.getElementById('tab-login');
		const tabRegister = document.getElementById('tab-register');

		const switchTab = (active, inactive) => {
			active.classList.replace('border-transparent', 'border-black');
			active.classList.replace('text-gray-400', 'text-black');
			active.classList.add('font-bold');

			inactive.classList.replace('border-black', 'border-transparent');
			inactive.classList.replace('text-black', 'text-gray-400');
			inactive.classList.remove('font-bold');
		};

		if(tabLogin && tabRegister) {
			tabLogin.addEventListener('click', () => switchTab(tabLogin, tabRegister));
			tabRegister.addEventListener('click', () => switchTab(tabRegister, tabLogin));
		}

		authFormEmailPage.addEventListener('submit', (e) => {
			e.preventDefault();
			const email = authEmailInputPage.value;
			const btn = document.getElementById('btn-request-otp');
			const originalText = btn.innerHTML;
			btn.innerHTML = '<span class="animate-pulse">Processing...</span>';
			btn.disabled = true;

			fetch('/wp-json/healthedia/v1/auth/request-otp', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ email })
			}).then(res => res.json()).then(data => {
				btn.innerHTML = originalText;
				btn.disabled = false;
				if (data.success) {
					authFormEmailPage.classList.add('hidden');
					authFormOtpPage.classList.remove('hidden');
				} else {
					alert(data.message || 'Error requesting OTP.');
				}
			}).catch(() => {
				btn.innerHTML = originalText;
				btn.disabled = false;
				alert('Network error requesting OTP.');
			});
		});

		authFormOtpPage.addEventListener('submit', (e) => {
			e.preventDefault();
			const email = authEmailInputPage.value;
			const otp = document.getElementById('auth-otp-page').value;
			fetch('/wp-json/healthedia/v1/auth/verify-otp', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ email, otp })
			}).then(res => res.json()).then(data => {
				if (data.success) {
					window.location.href = '/';
				} else {
					alert(data.message || 'Invalid OTP.');
				}
			}).catch(() => alert('Network error verifying OTP.'));
		});

		const btnResend = document.getElementById('btn-resend-otp');
		if (btnResend) {
			btnResend.addEventListener('click', () => {
				authFormOtpPage.classList.add('hidden');
				authFormEmailPage.classList.remove('hidden');
				document.getElementById('auth-otp-page').value = '';
			});
		}
	}
});
