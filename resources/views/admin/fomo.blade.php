<x-layouts.kukai title="FOMO Risk Index">
<div class="section-header" style="display:flex;justify-content:space-between;align-items:flex-start;">
    <div>
        <h2>FOMO Risk Index</h2>
        <p>Institutional structural disconnection pressure diagnostic</p>
    </div>
    <form method="POST" action="{{ route('admin.fomo.run') }}">
        @csrf
        <button class="btn btn-primary">🔬 Recompute Now</button>
    </form>
</div>

@if($latest)
{{-- LATEST SCORE HERO --}}
<div class="card card-body" style="margin-bottom:1.5rem;background:linear-gradient(135deg,rgba(99,102,241,0.08),rgba(14,165,233,0.05));">
    <div style="display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:2rem;">
        <div style="text-align:center;min-width:120px;">
            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px;">Composite Score</div>
            <div style="font-family:'Outfit',sans-serif;font-size:4rem;font-weight:800;
                {{ $latest->risk_level==='low'?'color:#34d399':($latest->risk_level==='moderate'?'color:#fbbf24':($latest->risk_level==='high'?'color:#fb923c':'color:#f87171')) }};">
                {{ $latest->composite_score }}
            </div>
            <span class="badge {{ $latest->risk_level==='low'?'bg-emerald-500/20 text-emerald-400':($latest->risk_level==='moderate'?'pill-amber':($latest->risk_level==='high'?'pill-red':'pill-red')) }}"
                style="background:rgba({{$latest->risk_level==='low'?'16,185,129':($latest->risk_level==='moderate'?'245,158,11':'239,68,68')}},0.15);
                       color:{{$latest->risk_level==='low'?'#34d399':($latest->risk_level==='moderate'?'#fbbf24':'#f87171')}};
                       padding:4px 12px;border-radius:50px;font-size:0.7rem;font-weight:700;letter-spacing:1px;">
                {{ strtoupper($latest->risk_level) }} RISK
            </span>
        </div>
        <div>
            <div style="margin-bottom:0.75rem;">
                <div style="display:flex;justify-content:space-between;font-size:0.78rem;color:var(--text-secondary);margin-bottom:4px;">
                    <span>Notification Pressure</span><span style="font-weight:600;color:var(--text-primary);">{{ $latest->notification_pressure }}</span>
                </div>
                <div style="background:var(--bg-tertiary);border-radius:4px;height:6px;">
                    <div style="background:linear-gradient(90deg,#6366f1,#0ea5e9);height:100%;border-radius:4px;width:{{ $latest->notification_pressure }}%;transition:width 0.5s;"></div>
                </div>
            </div>
            <div style="margin-bottom:0.75rem;">
                <div style="display:flex;justify-content:space-between;font-size:0.78rem;color:var(--text-secondary);margin-bottom:4px;">
                    <span>Deadline Clustering</span><span style="font-weight:600;color:var(--text-primary);">{{ $latest->deadline_clustering }}</span>
                </div>
                <div style="background:var(--bg-tertiary);border-radius:4px;height:6px;">
                    <div style="background:linear-gradient(90deg,#a78bfa,#6366f1);height:100%;border-radius:4px;width:{{ $latest->deadline_clustering }}%;"></div>
                </div>
            </div>
            <div style="margin-bottom:0.75rem;">
                <div style="display:flex;justify-content:space-between;font-size:0.78rem;color:var(--text-secondary);margin-bottom:4px;">
                    <span>Midnight LMS Activity</span><span style="font-weight:600;color:var(--text-primary);">{{ $latest->midnight_activity }}</span>
                </div>
                <div style="background:var(--bg-tertiary);border-radius:4px;height:6px;">
                    <div style="background:linear-gradient(90deg,#f59e0b,#ef4444);height:100%;border-radius:4px;width:{{ $latest->midnight_activity }}%;"></div>
                </div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:0.78rem;color:var(--text-secondary);margin-bottom:4px;">
                    <span>After-Hours Faculty Messages</span><span style="font-weight:600;color:var(--text-primary);">{{ $latest->after_hours_faculty }}</span>
                </div>
                <div style="background:var(--bg-tertiary);border-radius:4px;height:6px;">
                    <div style="background:linear-gradient(90deg,#10b981,#0ea5e9);height:100%;border-radius:4px;width:{{ $latest->after_hours_faculty }}%;"></div>
                </div>
            </div>
        </div>
        <div style="text-align:right;min-width:140px;">
            <div style="font-size:0.72rem;color:var(--text-muted);">Last Computed</div>
            <div style="font-size:0.85rem;font-weight:600;margin-top:2px;">{{ $latest->computed_at->diffForHumans() }}</div>
            <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">{{ number_format($latest->total_users_analyzed) }} users analyzed</div>
        </div>
    </div>
</div>

{{-- TREND CHART --}}
<div class="card card-body">
    <div class="card-title" style="margin-bottom:1.25rem;">📈 FOMO Risk Score Trend (8 Weeks)</div>
    <div style="position:relative;height:200px;">
        <canvas id="fomoChart"></canvas>
    </div>
</div>

@else
<div class="card card-body" style="text-align:center;padding:3rem;">
    <div style="font-size:3rem;margin-bottom:1rem;">🔬</div>
    <p style="color:var(--text-muted);">No diagnostic data yet. Click "Recompute Now" to run the first analysis.</p>
</div>
@endif

@if($latest)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = @json($scores->pluck('computed_at')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M j')));
const vals   = @json($scores->pluck('composite_score'));

new Chart(document.getElementById('fomoChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'FOMO Risk Score',
            data: vals,
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#6366f1',
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b' } },
            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b' }, min: 0, max: 100 }
        }
    }
});
</script>
@endif
</x-layouts.kukai>
