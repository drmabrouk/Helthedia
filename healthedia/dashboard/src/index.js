import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';

const Dashboard = () => {
	const [stats, setStats] = useState({ users: 0, articles: 0, total_views: 0 });
	const [loading, setLoading] = useState(true);
	const [activeTab, setActiveTab] = useState('analytics');
	const [sidebarOpen, setSidebarOpen] = useState(false);

	useEffect(() => {
		fetch('/wp-json/healthedia/v1/admin/stats', {
			headers: { 'X-WP-Nonce': window.healthediaDashboardSettings.nonce }
		})
			.then(res => {
				if (!res.ok) throw new Error('Unauthorized or Error');
				return res.json();
			})
			.then(data => {
				setStats(data);
				setLoading(false);
			})
			.catch(err => {
				console.error(err);
				setLoading(false);
			});
	}, []);

	const closeSidebar = () => setSidebarOpen(false);

	return (
		<div className="min-h-screen bg-gray-50 text-[#111111] font-sans flex flex-col md:flex-row">

			{/* Mobile Header (Visible only on small screens) */}
			<div className="md:hidden flex items-center justify-between bg-white border-b border-[#E0E0E0] px-4 py-3 sticky top-0 z-30">
				<h1 className="text-xl font-bold uppercase tracking-tight">Healthedia<span className="text-[10px] font-mono text-gray-400 uppercase tracking-widest ml-2">System</span></h1>
				<button onClick={() => setSidebarOpen(!sidebarOpen)} className="p-2 border border-[#E0E0E0] rounded-lg">
					<svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
				</button>
			</div>

			{/* Sidebar Overlay (Mobile) */}
			{sidebarOpen && (
				<div className="fixed inset-0 bg-black/50 z-40 md:hidden" onClick={closeSidebar}></div>
			)}

			{/* Sidebar (Desktop static, Mobile absolute/off-canvas) */}
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
					<button onClick={() => { setActiveTab('members'); closeSidebar(); }} className={`w-full text-left px-4 py-4 md:py-3 rounded-xl transition-colors ${activeTab === 'members' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>Members Directory</button>
					<button onClick={() => { setActiveTab('manuscripts'); closeSidebar(); }} className={`w-full text-left px-4 py-4 md:py-3 rounded-xl transition-colors ${activeTab === 'manuscripts' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>Manuscript Reviews</button>
					<button onClick={() => { setActiveTab('settings'); closeSidebar(); }} className={`w-full text-left px-4 py-4 md:py-3 rounded-xl transition-colors ${activeTab === 'settings' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>Global Settings</button>
				</nav>

				<a href="/" className="block mt-8 px-4 py-4 border border-[#E0E0E0] text-gray-500 hover:text-black hover:border-black rounded-xl transition-colors text-center font-mono text-xs uppercase tracking-widest">← Back to Site</a>
			</aside>

			{/* Main Content */}
			<main className="flex-grow p-4 md:p-12 overflow-y-auto w-full md:w-[calc(100%-16rem)]">
				{activeTab === 'analytics' && (
					<>
						<header className="mb-8 md:mb-12 border-b border-[#E0E0E0] pb-6">
							<h2 className="text-2xl md:text-3xl font-bold uppercase tracking-tight">System Analytics</h2>
							<p className="font-mono text-xs md:text-sm text-gray-500 uppercase mt-2">Live metrics across the Healthedia network</p>
						</header>

						{loading ? (
							<div className="grid grid-cols-1 md:grid-cols-3 gap-6">
								{[1,2,3].map(i => (
									<div key={i} className="bg-white p-6 border border-[#E0E0E0] rounded-2xl shadow-sm animate-pulse">
										<div className="h-2 bg-gray-200 rounded w-1/3 mb-6"></div>
										<div className="h-10 bg-gray-200 rounded w-1/2"></div>
									</div>
								))}
							</div>
						) : (
							<div className="grid grid-cols-1 md:grid-cols-3 gap-6">
								<div className="bg-white p-6 md:p-8 border border-[#E0E0E0] rounded-2xl shadow-sm">
									<div className="font-mono text-[10px] text-gray-500 uppercase tracking-widest mb-3">Total Researchers</div>
									<div className="text-4xl md:text-5xl font-bold font-sans tracking-tighter break-words">{stats.users.toLocaleString()}</div>
								</div>
								<div className="bg-white p-6 md:p-8 border border-[#E0E0E0] rounded-2xl shadow-sm">
									<div className="font-mono text-[10px] text-gray-500 uppercase tracking-widest mb-3">Indexed Articles</div>
									<div className="text-4xl md:text-5xl font-bold font-sans tracking-tighter break-words">{stats.articles.toLocaleString()}</div>
								</div>
								<div className="bg-white p-6 md:p-8 border border-[#E0E0E0] rounded-2xl shadow-sm">
									<div className="font-mono text-[10px] text-gray-500 uppercase tracking-widest mb-3">Global Views</div>
									<div className="text-4xl md:text-5xl font-bold font-sans tracking-tighter break-words">{stats.total_views.toLocaleString()}</div>
								</div>
							</div>
						)}
					</>
				)}

				{activeTab === 'members' && (
					<>
						<header className="mb-6 md:mb-8 border-b border-[#E0E0E0] pb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
							<div>
								<h2 className="text-2xl md:text-3xl font-bold uppercase tracking-tight">Member Directory</h2>
								<p className="font-mono text-xs md:text-sm text-gray-500 uppercase mt-2">Manage all registered accounts</p>
							</div>
						</header>
						<div className="bg-white border border-[#E0E0E0] rounded-2xl shadow-sm overflow-x-auto">
							<table className="w-full text-left border-collapse min-w-[600px]">
								<thead>
									<tr className="bg-gray-50 border-b border-[#E0E0E0] font-mono text-[10px] uppercase tracking-widest text-gray-500">
										<th className="py-4 px-6 font-normal">UID</th>
										<th className="py-4 px-6 font-normal">Name</th>
										<th className="py-4 px-6 font-normal">Email</th>
										<th className="py-4 px-6 font-normal">Status</th>
										<th className="py-4 px-6 font-normal text-right">Actions</th>
									</tr>
								</thead>
								<tbody className="font-sans text-sm divide-y divide-[#E0E0E0]">
									<tr className="hover:bg-gray-50 transition-colors">
										<td className="py-4 px-6 font-mono text-xs text-gray-500">USR-001</td>
										<td className="py-4 px-6 font-bold whitespace-nowrap">Admin System</td>
										<td className="py-4 px-6 font-mono text-xs truncate max-w-[150px]">admin@healthedia.com</td>
										<td className="py-4 px-6"><span className="bg-black text-white px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-widest">Verified</span></td>
										<td className="py-4 px-6 text-right"><button className="border border-[#E0E0E0] px-4 py-2 rounded font-mono text-[10px] uppercase hover:border-black transition-colors min-h-[44px]">Edit</button></td>
									</tr>
								</tbody>
							</table>
						</div>
					</>
				)}

				{activeTab === 'manuscripts' && (
					<>
						<header className="mb-6 md:mb-8 border-b border-[#E0E0E0] pb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
							<div>
								<h2 className="text-2xl md:text-3xl font-bold uppercase tracking-tight">Manuscript Reviews</h2>
								<p className="font-mono text-xs md:text-sm text-gray-500 uppercase mt-2">Pending editorial approvals</p>
							</div>
						</header>
						<div className="bg-white border border-[#E0E0E0] rounded-2xl shadow-sm overflow-x-auto">
							<table className="w-full text-left border-collapse min-w-[700px]">
								<thead>
									<tr className="bg-gray-50 border-b border-[#E0E0E0] font-mono text-[10px] uppercase tracking-widest text-gray-500">
										<th className="py-4 px-6 font-normal">Sub ID</th>
										<th className="py-4 px-6 font-normal">Title</th>
										<th className="py-4 px-6 font-normal">Author</th>
										<th className="py-4 px-6 font-normal">Date</th>
										<th className="py-4 px-6 font-normal text-right">Review</th>
									</tr>
								</thead>
								<tbody className="font-sans text-sm divide-y divide-[#E0E0E0]">
									<tr className="hover:bg-gray-50 transition-colors">
										<td className="py-4 px-6 font-mono text-xs text-gray-500">SUB-902</td>
										<td className="py-4 px-6 font-bold line-clamp-1 max-w-[200px]">Efficacy of Novel Therapeutics in Oncology</td>
										<td className="py-4 px-6 text-gray-700 whitespace-nowrap">Dr. John Doe</td>
										<td className="py-4 px-6 font-mono text-[10px] text-gray-500 whitespace-nowrap">Oct 14, 2023</td>
										<td className="py-4 px-6 text-right"><button className="bg-black text-white px-4 py-2 rounded font-mono text-[10px] uppercase hover:bg-gray-800 transition-colors whitespace-nowrap min-h-[44px]">Open File</button></td>
									</tr>
								</tbody>
							</table>
						</div>
					</>
				)}
			</main>
		</div>
	);
};

const rootEl = document.getElementById('healthedia-dashboard-root');
if (rootEl) {
	const root = createRoot(rootEl);
	root.render(<Dashboard />);
}
