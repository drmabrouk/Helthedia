document.addEventListener('DOMContentLoaded', () => {

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
		fetch(`/wp-json/healthedia/v1/search?q=${encodeURIComponent(query)}&type=${encodeURIComponent(type)}`)
			.then(res => res.json())
			.then(data => {
				gatewaySearchList.innerHTML = '';
				if (data.results && data.results.length > 0) {
					data.results.forEach(result => {
						const li = document.createElement('li');
						li.className = 'px-6 py-3 hover:bg-gray-50 border-b border-[#E0E0E0] last:border-0';
						li.innerHTML = `
							<a href="${result.url}" class="block">
								<div class="font-bold text-black">${result.title}</div>
								<div class="font-mono text-xs text-gray-500 uppercase">${result.object_type}</div>
							</a>
						`;
						gatewaySearchList.appendChild(li);
					});
					gatewaySearchResults.classList.remove('hidden');
				} else {
					gatewaySearchList.innerHTML = '<li class="px-6 py-4 text-gray-500 font-mono text-sm text-center">No results found</li>';
					gatewaySearchResults.classList.remove('hidden');
				}
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
				// Reset all tags
				searchTags.forEach(t => t.classList.remove('bg-black', 'text-white'));
				searchTags.forEach(t => t.classList.add('text-gray-500'));

				// Set active tag
				e.target.classList.remove('text-gray-500');
				e.target.classList.add('bg-black', 'text-white');

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
	if (dirGrid) {
		fetch('/wp-json/healthedia/v1/directories/researchers')
			.then(res => res.json())
			.then(response => {
				dirGrid.innerHTML = '';
				if (!response.data || response.data.length === 0) {
					dirGrid.innerHTML = '<div class="col-span-full text-center py-12 font-mono text-sm text-gray-500">No researchers found.</div>';
					return;
				}

				response.data.forEach(user => {
					const card = document.createElement('div');
					card.className = 'border border-[#E0E0E0] rounded-xl p-6 hover:border-black transition-colors bg-white';
					card.innerHTML = `
						<div class="flex items-center gap-2 mb-2">
							<a href="${user.url}" class="font-sans font-bold text-lg hover:underline truncate">${user.name}</a>
							${user.verified ? '<span class="bg-black text-white px-1.5 py-0.5 rounded text-[10px] font-mono uppercase tracking-widest">Verified</span>' : ''}
						</div>
						<div class="font-mono text-xs text-gray-500 uppercase truncate mb-4">${user.specialty || 'Independent'}</div>
						<div class="flex gap-4 font-mono text-xs text-gray-400">
							<div><span class="text-black font-bold">${user.views}</span> views</div>
						</div>
					`;
					dirGrid.appendChild(card);
				});
			})
			.catch(() => {
				dirGrid.innerHTML = '<div class="col-span-full text-center py-12 text-red-500 font-mono text-sm">Error loading directory.</div>';
			});
	}

	// Auth Modal Logic
	const authFormEmail = document.getElementById('auth-form-email');
	const authFormOtp = document.getElementById('auth-form-otp');
	const authEmailInput = document.getElementById('auth-email');
	const authModal = document.getElementById('healthedia-auth-modal');
	const authClose = document.getElementById('auth-close');

	if (authClose && authModal) {
		authClose.addEventListener('click', () => {
			authModal.classList.add('hidden');
		});
	}

	if (authFormEmail) {
		authFormEmail.addEventListener('submit', (e) => {
			e.preventDefault();
			const email = authEmailInput.value;
			fetch('/wp-json/healthedia/v1/auth/request-otp', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ email })
			}).then(res => res.json()).then(data => {
				if (data.success) {
					authFormEmail.classList.add('hidden');
					authFormOtp.classList.remove('hidden');
				} else {
					alert(data.message || 'Error requesting OTP.');
				}
			});
		});

		authFormOtp.addEventListener('submit', (e) => {
			e.preventDefault();
			const email = authEmailInput.value;
			const otp = document.getElementById('auth-otp').value;
			fetch('/wp-json/healthedia/v1/auth/verify-otp', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ email, otp })
			}).then(res => res.json()).then(data => {
				if (data.success) {
					window.location.reload();
				} else {
					alert(data.message || 'Invalid OTP.');
				}
			});
		});
	}
});
