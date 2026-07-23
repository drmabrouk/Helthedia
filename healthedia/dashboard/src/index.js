import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';

const Dashboard = () => {
	const [stats, setStats] = useState({ users: 0, articles: 0, total_views: 0 });
	const [loading, setLoading] = useState(true);

	useEffect(() => {
		fetch('/wp-json/healthedia/v1/admin/stats', { headers: { 'X-WP-Nonce': window.healthediaDashboardSettings.nonce } })
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
		<div className="min-h-screen bg-gray-50 text-[#111111] font-sans">
			{/* Sidebar */}
			<aside className="fixed inset-y-0 left-0 w-64 bg-white border-r border-[#E0E0E0] p-6 z-10">
				<h1 className="text-2xl font-bold uppercase tracking-tight mb-12">Healthedia<br/><span className="text-xs font-mono text-gray-500">Internal Dashboard</span></h1>

				<nav className="space-y-4 font-mono text-sm uppercase tracking-wider">
					<a href="#" className="block py-2 text-black font-bold">Overview</a>
					<a href="#" className="block py-2 text-gray-500 hover:text-black transition-colors">Members</a>
					<a href="#" className="block py-2 text-gray-500 hover:text-black transition-colors">Data Import</a>
					<a href="/" className="block py-2 text-gray-500 hover:text-black transition-colors mt-12 pt-4 border-t border-[#E0E0E0]">← Back to Site</a>
				</nav>
			</aside>

			{/* Main Content */}
			<main className="ml-64 p-12">
				<header className="mb-12">
					<h2 className="text-3xl font-bold uppercase tracking-tight">Platform Analytics</h2>
					<p className="font-mono text-sm text-gray-500 uppercase mt-2">Live metrics across the Healthedia network</p>
				</header>

				{loading ? (
					<div className="font-mono text-gray-500">Loading data...</div>
				) : (
					<div className="grid grid-cols-1 md:grid-cols-3 gap-6">
						<div className="bg-white p-6 border border-[#E0E0E0] rounded-xl shadow-sm">
							<div className="font-mono text-xs text-gray-500 uppercase tracking-widest mb-2">Total Researchers</div>
							<div className="text-4xl font-bold font-sans">{stats.users.toLocaleString()}</div>
						</div>
						<div className="bg-white p-6 border border-[#E0E0E0] rounded-xl shadow-sm">
							<div className="font-mono text-xs text-gray-500 uppercase tracking-widest mb-2">Indexed Articles</div>
							<div className="text-4xl font-bold font-sans">{stats.articles.toLocaleString()}</div>
						</div>
						<div className="bg-white p-6 border border-[#E0E0E0] rounded-xl shadow-sm">
							<div className="font-mono text-xs text-gray-500 uppercase tracking-widest mb-2">Global Views</div>
							<div className="text-4xl font-bold font-sans">{stats.total_views.toLocaleString()}</div>
						</div>
					</div>
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
