<x-layouts.kukai title="My Analytics">
<div class="section-header">
    <h2>My Analytics</h2>
    <p>Personal burnout index and wellness trends</p>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="card kpi-card"><div class="kpi-label">Burnout Index (Current)</div><div class="kpi-value" style="color:#34d399;font-size:1.8rem;">31</div><div class="kpi-trend good">↓ 40 pts vs. pre-KU</div></div>
    <div class="card kpi-card"><div class="kpi-label">After-Hours Messages / Wk</div><div class="kpi-value" style="color:#60a5fa;font-size:1.8rem;">187</div><div class="kpi-trend good">↓ 55% vs. baseline</div></div>
    <div class="card kpi-card"><div class="kpi-label">Window Participation</div><div class="kpi-value" style="color:#a78bfa;font-size:1.8rem;">96%</div><div class="kpi-trend good">Last 8 windows</div></div>
    <div class="card kpi-card"><div class="kpi-label">Wellness Compliance</div><div class="kpi-value" style="color:#34d399;font-size:1.8rem;">98%</div><div class="kpi-trend good">Accreditation ready</div></div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1.25rem;">📉 Burnout Index — 8-Week Trend</div>
        <div style="position:relative;height:220px;"><canvas id="burnoutChart"></canvas></div>
    </div>
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1.25rem;">💬 After-Hours Messages — Weekly</div>
        <div style="position:relative;height:220px;"><canvas id="msgsChart"></canvas></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const weeks = ['Wk1','Wk2','Wk3','Wk4','Wk5','Wk6','Wk7','Wk8'];
const burnout = [71, 68, 64, 59, 52, 44, 37, 31];
const msgs    = [420, 398, 371, 340, 291, 248, 201, 187];

const opts = {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b' } },
        y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b' } },
    }
};

new Chart(document.getElementById('burnoutChart'), {
    type: 'line',
    data: { labels: weeks, datasets: [{ data: burnout, borderColor: '#f87171', backgroundColor: 'rgba(248,113,113,0.08)', fill: true, tension: 0.4, pointRadius: 5, pointBackgroundColor: '#f87171' }] },
    options: opts
});

new Chart(document.getElementById('msgsChart'), {
    type: 'bar',
    data: { labels: weeks, datasets: [{ data: msgs, backgroundColor: 'rgba(99,102,241,0.5)', borderColor: '#6366f1', borderWidth: 2, borderRadius: 6 }] },
    options: opts
});
</script>
</x-layouts.kukai>
