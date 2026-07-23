import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';

const Dashboard = () => {
	const [activeTab, setActiveTab] = useState('analytics');
	const [stats, setStats] = useState({ users: 0, articles: 0, total_views: 0 });
	const [users, setUsers] = useState([]);
	const [researchers, setResearchers] = useState([]);
	const [settings, setSettings] = useState({ site_name: '', site_desc: '', admin_email: '', mock_data_seeded: false, enable_registration: 'yes', auth_maintenance_mode: 'no' });
	const [loading, setLoading] = useState(true);
	const [sidebarOpen, setSidebarOpen] = useState(false);

	// Modals
	const [showUserModal, setShowUserModal] = useState(false);
	const [editingUser, setEditingUser] = useState(null);
	const [showResearcherModal, setShowResearcherModal] = useState(false);
	const [editingResearcher, setEditingResearcher] = useState(null);

	const apiFetch = async (endpoint, options = {}) => {
		const res = await fetch(`/wp-json/healthedia/v1/admin/${endpoint}`, {
			...options,
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': window.healthediaDashboardSettings.nonce,
				...options.headers
			}
		});
		if (!res.ok) throw new Error('API Error');
		return res.json();
	};

	const loadData = async () => {
		setLoading(true);
		try {
			if (activeTab === 'analytics') setStats(await apiFetch('stats'));
			else if (activeTab === 'users') setUsers(await apiFetch('users'));
			else if (activeTab === 'researchers') setResearchers(await apiFetch('researchers'));
			else if (activeTab === 'settings') setSettings(await apiFetch('settings'));
		} catch (err) {
			console.error(err);
		}
		setLoading(false);
	};

	useEffect(() => {
		loadData();
	}, [activeTab]);

	const saveSettings = async (e) => {
		e.preventDefault();
		try {
			await apiFetch('settings', { method: 'POST', body: JSON.stringify(settings) });
			alert('Settings saved.');
		} catch (e) {
			alert('Error saving settings.');
		}
	};

	const wipeMockData = async () => {
		if (window.confirm("Are you sure you want to permanently delete all mock data?")) {
			try {
				await apiFetch('wipe-mock-data', { method: 'POST' });
				alert('Mock data wiped.');
				setSettings({ ...settings, mock_data_seeded: false });
			} catch (e) {
				alert('Error wiping mock data.');
			}
		}
	};

	// Users CRUD
	const handleUserSubmit = async (e) => {
		e.preventDefault();
		const formData = new FormData(e.target);
		const data = Object.fromEntries(formData.entries());
		try {
			if (editingUser) {
				await apiFetch(`users/${editingUser.id}`, { method: 'PUT', body: JSON.stringify(data) });
			} else {
				await apiFetch('users', { method: 'POST', body: JSON.stringify(data) });
			}
			setShowUserModal(false);
			loadData();
		} catch(err) { alert('Error saving user.'); }
	};

	const deleteUser = async (id) => {
		if(window.confirm('Delete user?')) {
			try { await apiFetch(`users/${id}`, { method: 'DELETE' }); loadData(); } catch(e) { alert('Error deleting user'); }
		}
	};

	// Researchers CRUD
	const handleResearcherSubmit = async (e) => {
		e.preventDefault();
		const formData = new FormData(e.target);
		const data = Object.fromEntries(formData.entries());
		try {
			if (editingResearcher) {
				await apiFetch(`researchers/${editingResearcher.id}`, { method: 'PUT', body: JSON.stringify(data) });
			} else {
				await apiFetch('researchers', { method: 'POST', body: JSON.stringify(data) });
			}
			setShowResearcherModal(false);
			loadData();
		} catch(err) { alert('Error saving researcher.'); }
	};

	const deleteResearcher = async (id) => {
		if(window.confirm('Delete researcher?')) {
			try { await apiFetch(`researchers/${id}`, { method: 'DELETE' }); loadData(); } catch(e) { alert('Error deleting researcher'); }
		}
	};

	const closeSidebar = () => setSidebarOpen(false);

	return (
		<div className="min-h-screen bg-gray-50 text-[#111111] font-sans flex flex-col md:flex-row">
			<div className="md:hidden flex items-center justify-between bg-white border-b border-[#E0E0E0] px-4 py-3 sticky top-0 z-30">
				<h1 className="text-xl font-bold uppercase tracking-tight">Healthedia<span className="text-[10px] font-mono text-gray-400 uppercase tracking-widest ml-2">System</span></h1>
				<button onClick={() => setSidebarOpen(!sidebarOpen)} className="p-2 border border-[#E0E0E0] rounded-lg">
					<svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
				</button>
			</div>

			{sidebarOpen && <div className="fixed inset-0 bg-black/50 z-40 md:hidden" onClick={closeSidebar}></div>}

			<aside className={`fixed md:sticky top-0 left-0 h-screen md:h-screen w-64 bg-white border-r border-[#E0E0E0] p-6 flex-shrink-0 flex flex-col z-50 transform ${sidebarOpen ? 'translate-x-0' : '-translate-x-full'} md:translate-x-0 transition-transform duration-300 overflow-y-auto`}>
				<div className="flex justify-between items-center mb-12">
					<h1 className="text-2xl font-bold uppercase tracking-tight hidden md:block">Healthedia<br/><span className="text-[10px] font-mono text-gray-400 uppercase tracking-widest">Internal System</span></h1>
					<h1 className="text-xl font-bold uppercase tracking-tight md:hidden">Menu</h1>
					<button onClick={closeSidebar} className="md:hidden p-2 text-gray-500">
						<svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
					</button>
				</div>

				<nav className="space-y-2 font-mono text-xs uppercase tracking-widest flex-grow">
					<button onClick={() => { setActiveTab('analytics'); closeSidebar(); }} className={`w-full text-left px-4 py-4 md:py-3 rounded-xl transition-colors ${activeTab === 'analytics' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>System Analytics</button>
					<button onClick={() => { setActiveTab('users'); closeSidebar(); }} className={`w-full text-left px-4 py-4 md:py-3 rounded-xl transition-colors ${activeTab === 'users' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>System Users</button>
					<button onClick={() => { setActiveTab('researchers'); closeSidebar(); }} className={`w-full text-left px-4 py-4 md:py-3 rounded-xl transition-colors ${activeTab === 'researchers' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>Researchers Mgmt</button>
					<button onClick={() => { setActiveTab('settings'); closeSidebar(); }} className={`w-full text-left px-4 py-4 md:py-3 rounded-xl transition-colors ${activeTab === 'settings' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>Global Settings</button>
				</nav>

				<a href="/" className="block mt-8 px-4 py-4 border border-[#E0E0E0] text-gray-500 hover:text-black hover:border-black rounded-xl transition-colors text-center font-mono text-xs uppercase tracking-widest">← Back to Site</a>
			</aside>

			<main className="flex-grow p-4 md:p-12 overflow-y-auto w-full md:w-[calc(100%-16rem)]">
				{activeTab === 'analytics' && (
					<>
						<header className="mb-8 md:mb-12 border-b border-[#E0E0E0] pb-6">
							<h2 className="text-2xl md:text-3xl font-bold uppercase tracking-tight">System Analytics</h2>
						</header>
						<div className="grid grid-cols-1 md:grid-cols-3 gap-6">
							<div className="bg-white p-6 border border-[#E0E0E0] rounded-2xl shadow-sm">
								<div className="font-mono text-[10px] text-gray-500 uppercase tracking-widest mb-3">Total Users</div>
								<div className="text-4xl font-bold font-sans tracking-tighter break-words">{stats.users.toLocaleString()}</div>
							</div>
							<div className="bg-white p-6 border border-[#E0E0E0] rounded-2xl shadow-sm">
								<div className="font-mono text-[10px] text-gray-500 uppercase tracking-widest mb-3">Indexed Articles</div>
								<div className="text-4xl font-bold font-sans tracking-tighter break-words">{stats.articles.toLocaleString()}</div>
							</div>
							<div className="bg-white p-6 border border-[#E0E0E0] rounded-2xl shadow-sm">
								<div className="font-mono text-[10px] text-gray-500 uppercase tracking-widest mb-3">Global Views</div>
								<div className="text-4xl font-bold font-sans tracking-tighter break-words">{stats.total_views.toLocaleString()}</div>
							</div>
						</div>
					</>
				)}

				{activeTab === 'users' && (
					<>
						<header className="mb-6 md:mb-8 border-b border-[#E0E0E0] pb-6 flex justify-between items-end">
							<h2 className="text-2xl md:text-3xl font-bold uppercase tracking-tight">System Users Management</h2>
							<button onClick={() => { setEditingUser(null); setShowUserModal(true); }} className="bg-black text-white px-4 py-2 rounded-full font-mono text-[10px] uppercase tracking-widest hover:bg-gray-800 transition-colors">Add User</button>
						</header>
						<div className="bg-white border border-[#E0E0E0] rounded-2xl shadow-sm overflow-x-auto">
							<table className="w-full text-left border-collapse min-w-[600px]">
								<thead>
									<tr className="bg-gray-50 border-b border-[#E0E0E0] font-mono text-[10px] uppercase tracking-widest text-gray-500">
										<th className="py-4 px-6 font-normal">UID</th>
										<th className="py-4 px-6 font-normal">Name</th>
										<th className="py-4 px-6 font-normal">Email</th>
										<th className="py-4 px-6 font-normal">Roles</th>
										<th className="py-4 px-6 font-normal">Registered</th>
										<th className="py-4 px-6 font-normal text-right">Actions</th>
									</tr>
								</thead>
								<tbody className="font-sans text-sm divide-y divide-[#E0E0E0]">
									{users.map(u => (
										<tr key={u.id} className="hover:bg-gray-50 transition-colors">
											<td className="py-4 px-6 font-mono text-xs text-gray-500">{u.id}</td>
											<td className="py-4 px-6 font-bold whitespace-nowrap">{u.name}</td>
											<td className="py-4 px-6 font-mono text-xs">{u.email}</td>
											<td className="py-4 px-6 font-mono text-[10px] uppercase">{u.roles.join(', ')}</td>
											<td className="py-4 px-6 font-mono text-xs">{new Date(u.registered).toLocaleDateString()}</td>
											<td className="py-4 px-6 text-right space-x-2 whitespace-nowrap">
												<button onClick={() => { setEditingUser(u); setShowUserModal(true); }} className="border border-[#E0E0E0] px-3 py-1 rounded font-mono text-[10px] uppercase hover:border-black transition-colors">Edit</button>
												<button onClick={() => deleteUser(u.id)} className="border border-red-200 text-red-500 px-3 py-1 rounded font-mono text-[10px] uppercase hover:bg-red-50 transition-colors">Delete</button>
											</td>
										</tr>
									))}
								</tbody>
							</table>
						</div>
					</>
				)}

				{activeTab === 'researchers' && (
					<>
						<header className="mb-6 md:mb-8 border-b border-[#E0E0E0] pb-6 flex justify-between items-end">
							<h2 className="text-2xl md:text-3xl font-bold uppercase tracking-tight">Researchers Management</h2>
							<button onClick={() => { setEditingResearcher(null); setShowResearcherModal(true); }} className="bg-black text-white px-4 py-2 rounded-full font-mono text-[10px] uppercase tracking-widest hover:bg-gray-800 transition-colors">Add Researcher</button>
						</header>
						<div className="bg-white border border-[#E0E0E0] rounded-2xl shadow-sm overflow-x-auto">
							<table className="w-full text-left border-collapse min-w-[800px]">
								<thead>
									<tr className="bg-gray-50 border-b border-[#E0E0E0] font-mono text-[10px] uppercase tracking-widest text-gray-500">
										<th className="py-4 px-6 font-normal">UID</th>
										<th className="py-4 px-6 font-normal">Name</th>
										<th className="py-4 px-6 font-normal">Specialty</th>
										<th className="py-4 px-6 font-normal">Institution</th>
										<th className="py-4 px-6 font-normal">Type</th>
										<th className="py-4 px-6 font-normal text-right">Actions</th>
									</tr>
								</thead>
								<tbody className="font-sans text-sm divide-y divide-[#E0E0E0]">
									{researchers.map(r => (
										<tr key={r.id} className="hover:bg-gray-50 transition-colors">
											<td className="py-4 px-6 font-mono text-xs text-gray-500">{r.id}</td>
											<td className="py-4 px-6 font-bold whitespace-nowrap">{r.name}</td>
											<td className="py-4 px-6 font-mono text-xs truncate max-w-[200px]">{r.specialty}</td>
											<td className="py-4 px-6 font-mono text-xs truncate max-w-[200px]">{r.institution}</td>
											<td className="py-4 px-6">
												{r.is_mock ? <span className="bg-gray-200 text-black px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-widest">Mock Data</span> : <span className="bg-black text-white px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-widest">Real</span>}
											</td>
											<td className="py-4 px-6 text-right space-x-2 whitespace-nowrap">
												<button onClick={() => { setEditingResearcher(r); setShowResearcherModal(true); }} className="border border-[#E0E0E0] px-3 py-1 rounded font-mono text-[10px] uppercase hover:border-black transition-colors">Edit</button>
												<button onClick={() => deleteResearcher(r.id)} className="border border-red-200 text-red-500 px-3 py-1 rounded font-mono text-[10px] uppercase hover:bg-red-50 transition-colors">Del</button>
											</td>
										</tr>
									))}
								</tbody>
							</table>
						</div>
					</>
				)}

				{activeTab === 'settings' && (
					<>
						<header className="mb-6 md:mb-8 border-b border-[#E0E0E0] pb-6">
							<h2 className="text-2xl md:text-3xl font-bold uppercase tracking-tight">Global System Settings</h2>
						</header>
						<form onSubmit={saveSettings} className="space-y-6 max-w-2xl bg-white border border-[#E0E0E0] rounded-2xl p-8">
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Site Name</label>
								<input type="text" value={settings.site_name} onChange={e => setSettings({...settings, site_name: e.target.value})} className="w-full border border-[#E0E0E0] rounded-xl px-4 py-2 font-sans outline-none focus:border-black" />
							</div>
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Global Description</label>
								<textarea value={settings.site_desc} onChange={e => setSettings({...settings, site_desc: e.target.value})} className="w-full border border-[#E0E0E0] rounded-xl px-4 py-2 font-sans outline-none focus:border-black" rows="3"></textarea>
							</div>
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Admin Contact Email</label>
								<input type="email" value={settings.admin_email} onChange={e => setSettings({...settings, admin_email: e.target.value})} className="w-full border border-[#E0E0E0] rounded-xl px-4 py-2 font-sans outline-none focus:border-black" />
							</div>

							<div className="pt-4 border-t border-[#E0E0E0]">
								<h3 className="text-lg font-bold uppercase tracking-tight mb-4">Authentication Configuration</h3>
								<div className="space-y-4">
									<label className="flex items-center gap-3">
										<input type="checkbox" checked={settings.enable_registration === 'yes'} onChange={e => setSettings({...settings, enable_registration: e.target.checked ? 'yes' : 'no'})} className="w-4 h-4 text-black focus:ring-black border-gray-300 rounded" />
										<span className="font-mono text-sm text-gray-700">Enable New User Registration</span>
									</label>
									<label className="flex items-center gap-3">
										<input type="checkbox" checked={settings.auth_maintenance_mode === 'yes'} onChange={e => setSettings({...settings, auth_maintenance_mode: e.target.checked ? 'yes' : 'no'})} className="w-4 h-4 text-black focus:ring-black border-gray-300 rounded" />
										<span className="font-mono text-sm text-gray-700">Enable Auth Maintenance Mode (Disables Login & Registration)</span>
									</label>
								</div>
							</div>

							<div className="pt-4 flex justify-between items-center border-t border-[#E0E0E0]">
								<button type="submit" className="bg-black text-white px-6 py-2 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-gray-800 transition-colors">Save Settings</button>
								{settings.mock_data_seeded && (
									<button type="button" onClick={wipeMockData} className="border border-red-500 text-red-500 px-6 py-2 rounded-full font-sans uppercase text-sm tracking-wide hover:bg-red-50 transition-colors">Wipe Mock Data</button>
								)}
							</div>
						</form>
					</>
				)}
			</main>

			{/* Modals */}
			{showUserModal && (
				<div className="fixed inset-0 bg-black/50 z-[60] flex items-center justify-center p-4">
					<form onSubmit={handleUserSubmit} className="bg-white rounded-2xl p-6 md:p-8 w-full max-w-md">
						<h3 className="text-xl font-bold uppercase tracking-tight mb-6">{editingUser ? 'Edit User' : 'Add User'}</h3>
						<div className="space-y-4 mb-6">
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Name</label>
								<input type="text" name="name" defaultValue={editingUser?.name || ''} required className="w-full border border-[#E0E0E0] rounded-lg px-3 py-2 text-sm outline-none focus:border-black" />
							</div>
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Email</label>
								<input type="email" name="email" defaultValue={editingUser?.email || ''} required className="w-full border border-[#E0E0E0] rounded-lg px-3 py-2 text-sm outline-none focus:border-black" />
							</div>
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Role</label>
								<select name="role" defaultValue={editingUser?.roles?.[0] || 'subscriber'} className="w-full border border-[#E0E0E0] rounded-lg px-3 py-2 text-sm outline-none focus:border-black bg-white">
									<option value="subscriber">Subscriber</option>
									<option value="administrator">Administrator</option>
								</select>
							</div>
						</div>
						<div className="flex justify-end gap-3">
							<button type="button" onClick={() => setShowUserModal(false)} className="px-4 py-2 rounded-full font-mono text-xs uppercase tracking-widest text-gray-500 hover:bg-gray-100">Cancel</button>
							<button type="submit" className="bg-black text-white px-6 py-2 rounded-full font-mono text-xs uppercase tracking-widest hover:bg-gray-800">Save</button>
						</div>
					</form>
				</div>
			)}

			{showResearcherModal && (
				<div className="fixed inset-0 bg-black/50 z-[60] flex items-center justify-center p-4">
					<form onSubmit={handleResearcherSubmit} className="bg-white rounded-2xl p-6 md:p-8 w-full max-w-md">
						<h3 className="text-xl font-bold uppercase tracking-tight mb-6">{editingResearcher ? 'Edit Researcher' : 'Add Researcher'}</h3>
						<div className="space-y-4 mb-6">
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Name</label>
								<input type="text" name="name" defaultValue={editingResearcher?.name || ''} required className="w-full border border-[#E0E0E0] rounded-lg px-3 py-2 text-sm outline-none focus:border-black" />
							</div>
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Email</label>
								<input type="email" name="email" defaultValue={editingResearcher?.email || ''} required className="w-full border border-[#E0E0E0] rounded-lg px-3 py-2 text-sm outline-none focus:border-black" />
							</div>
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Specialty</label>
								<input type="text" name="specialty" defaultValue={editingResearcher?.specialty || ''} required className="w-full border border-[#E0E0E0] rounded-lg px-3 py-2 text-sm outline-none focus:border-black" />
							</div>
							<div>
								<label className="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Institution</label>
								<input type="text" name="institution" defaultValue={editingResearcher?.institution || ''} required className="w-full border border-[#E0E0E0] rounded-lg px-3 py-2 text-sm outline-none focus:border-black" />
							</div>
						</div>
						<div className="flex justify-end gap-3">
							<button type="button" onClick={() => setShowResearcherModal(false)} className="px-4 py-2 rounded-full font-mono text-xs uppercase tracking-widest text-gray-500 hover:bg-gray-100">Cancel</button>
							<button type="submit" className="bg-black text-white px-6 py-2 rounded-full font-mono text-xs uppercase tracking-widest hover:bg-gray-800">Save</button>
						</div>
					</form>
				</div>
			)}

		</div>
	);
};

const rootEl = document.getElementById('healthedia-dashboard-root');
if (rootEl) {
	const root = createRoot(rootEl);
	root.render(<Dashboard />);
}
