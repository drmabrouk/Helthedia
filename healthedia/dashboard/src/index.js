import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';

const Dashboard = () => {
	const [stats, setStats] = useState({ users: 0, articles: 0, total_views: 0 });
	const [loading, setLoading] = useState(true);
	const [activeTab, setActiveTab] = useState('analytics');

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

	return (
		<div className="min-h-screen bg-gray-50 text-[#111111] font-sans flex">
			{/* Sidebar */}
			<aside className="w-64 bg-white border-r border-[#E0E0E0] p-6 flex-shrink-0 flex flex-col">
				<h1 className="text-2xl font-bold uppercase tracking-tight mb-12">Healthedia<br/><span className="text-[10px] font-mono text-gray-400 uppercase tracking-widest">Internal System</span></h1>

				<nav className="space-y-2 font-mono text-xs uppercase tracking-widest flex-grow">
					<button onClick={() => setActiveTab('analytics')} className={`w-full text-left px-4 py-3 rounded-xl transition-colors ${activeTab === 'analytics' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>System Analytics</button>
					<button onClick={() => setActiveTab('members')} className={`w-full text-left px-4 py-3 rounded-xl transition-colors ${activeTab === 'members' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>Members</button>
					<button onClick={() => setActiveTab('manuscripts')} className={`w-full text-left px-4 py-3 rounded-xl transition-colors ${activeTab === 'manuscripts' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>Manuscripts</button>
					<button onClick={() => setActiveTab('settings')} className={`w-full text-left px-4 py-3 rounded-xl transition-colors ${activeTab === 'settings' ? 'bg-black text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-black'}`}>Settings</button>
				</nav>

				<a href="/" className="block mt-auto px-4 py-3 border border-[#E0E0E0] text-gray-500 hover:text-black hover:border-black rounded-xl transition-colors text-center font-mono text-xs uppercase tracking-widest">← Back to Site</a>
			</aside>

			{/* Main Content */}
			<main className="flex-grow p-12 overflow-y-auto">
				{activeTab === 'analytics' && (
					<>
						<header className="mb-12 border-b border-[#E0E0E0] pb-6">
							<h2 className="text-3xl font-bold uppercase tracking-tight">System Analytics</h2>
							<p className="font-mono text-sm text-gray-500 uppercase mt-2">Live metrics across the Healthedia network</p>
						</header>

						{loading ? (
							<div className="font-mono text-gray-500 animate-pulse text-sm">Loading telemetry...</div>
						) : (
							<div className="grid grid-cols-1 md:grid-cols-3 gap-6">
								<div className="bg-white p-6 border border-[#E0E0E0] rounded-2xl shadow-sm">
									<div className="font-mono text-[10px] text-gray-500 uppercase tracking-widest mb-3">Total Researchers</div>
									<div className="text-5xl font-bold font-sans tracking-tighter">{stats.users.toLocaleString()}</div>
								</div>
								<div className="bg-white p-6 border border-[#E0E0E0] rounded-2xl shadow-sm">
									<div className="font-mono text-[10px] text-gray-500 uppercase tracking-widest mb-3">Indexed Articles</div>
									<div className="text-5xl font-bold font-sans tracking-tighter">{stats.articles.toLocaleString()}</div>
								</div>
								<div className="bg-white p-6 border border-[#E0E0E0] rounded-2xl shadow-sm">
									<div className="font-mono text-[10px] text-gray-500 uppercase tracking-widest mb-3">Global Views</div>
									<div className="text-5xl font-bold font-sans tracking-tighter">{stats.total_views.toLocaleString()}</div>
								</div>
							</div>
						)}
					</>
				)}

				{activeTab === 'members' && (
					<>
						<header className="mb-8 border-b border-[#E0E0E0] pb-6 flex justify-between items-end">
							<div>
								<h2 className="text-3xl font-bold uppercase tracking-tight">Member Directory</h2>
								<p className="font-mono text-sm text-gray-500 uppercase mt-2">Manage all registered accounts</p>
							</div>
						</header>
						<div className="bg-white border border-[#E0E0E0] rounded-2xl shadow-sm overflow-hidden">
							<table className="w-full text-left border-collapse">
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
										<td className="py-4 px-6 font-bold">Admin System</td>
										<td className="py-4 px-6 font-mono text-xs">admin@healthedia.com</td>
										<td className="py-4 px-6"><span className="bg-black text-white px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-widest">Verified</span></td>
										<td className="py-4 px-6 text-right"><button className="border border-[#E0E0E0] px-3 py-1 rounded font-mono text-[10px] uppercase hover:border-black transition-colors">Edit</button></td>
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
