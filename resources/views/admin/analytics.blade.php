<x-layouts.kukai title="Analytics">
<div class="section-header">
    <h2>Analytics</h2>
    <p>Institution-wide window performance and behavioral trends</p>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
    {{-- Notifications Chart --}}
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1.25rem;">📨 Notifications Held Per Window</div>
        <div style="position:relative;height:220px;">
            <canvas id="notifChart"></canvas>
        </div>
    </div>
    {{-- Participation Chart --}}
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1.25rem;">👥 Participation Rate Trend</div>
        <div style="position:relative;height:220px;">
            <canvas id="partChart"></canvas>
        </div>
    </div>
</div>

{{-- FOMO Trend + CRI Improvement --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1.25rem;">📉 FOMO Risk Score: 8-Week Trend</div>
        <div style="position:relative;height:200px;">
            <canvas id="fomoTrend"></canvas>
        </div>
    </div>
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1.25rem;">🧠 Avg CRI Improvement Per Window</div>
        <div style="position:relative;height:200px;">
            <canvas id="criChart"></canvas>
        </div>
    </div>
</div>

{{-- BEFORE vs DURING KU PANEL --}}
<div class="card card-body">
    <div class="card-header-bar">
        <div class="card-title">⚡ Before KU vs During KU — 11:45 PM Snapshot</div>
        <span class="pill pill-sky">Visual Reference</span>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:0.5rem;">
        {{-- Before --}}
        <div style="background:rgba(239,68,68,0.05);border:1px solid rgba(239,68,68,0.15);border-radius:12px;padding:1.5rem;">
            <div style="font-family:'Outfit',sans-serif;font-weight:700;color:#f87171;margin-bottom:1rem;font-size:1rem;">🔴 BEFORE KU — 11:45 PM</div>
            @foreach([
                ['app'=>'Canvas LMS','msg'=>'Assignment due in 15 minutes: Final Essay Draft','color'=>'#f87171','icon'=>'📚'],
                ['app'=>'Gmail','msg'=>'Prof. Reyes (11:30 PM): "Reminder — submission tonight"','color'=>'#fb923c','icon'=>'📧'],
                ['app'=>'Discord','msg'=>'Study group: 24 new messages — "is anyone awake??"','color'=>'#a78bfa','icon'=>'💬'],
                ['app'=>'WhatsApp','msg'=>'Group pinned: "Submit by 11:59 or FAIL"','color'=>'#34d399','icon'=>'📱'],
                ['app'=>'University Portal','msg'=>'ALERT: Grades released — check now','color'=>'#fbbf24','icon'=>'🔔'],
            ] as $n)
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0;border-bottom:1px solid rgba(255,255,255,0.04);">
                <span style="font-size:1.1rem;">{{ $n['icon'] }}</span>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:0.72rem;color:{{ $n['color'] }};font-weight:700;margin-bottom:2px;">{{ $n['app'] }}</div>
                    <div style="font-size:0.78rem;color:var(--text-secondary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $n['msg'] }}</div>
                </div>
            </div>
            @endforeach
            <div style="margin-top:0.75rem;font-size:0.75rem;color:#f87171;font-style:italic;">The phone screen is never dark. There is no defined end to the institutional day.</div>
        </div>
        {{-- During --}}
        <div style="background:rgba(14,165,233,0.05);border:1px solid rgba(14,165,233,0.15);border-radius:12px;padding:1.5rem;">
            <div style="font-family:'Outfit',sans-serif;font-weight:700;color:#38bdf8;margin-bottom:1rem;font-size:1rem;">🔵 DURING KU WINDOW — Same Night</div>
            @foreach([
                ['label'=>'LMS Notifications','value'=>'Paused','icon'=>'⏸️'],
                ['label'=>'Deadline Clock','value'=>'Suspended — no penalty','icon'=>'⏰'],
                ['label'=>'University Email','value'=>'Queued — batch release at 9 PM','icon'=>'📭'],
                ['label'=>'Campus WiFi (Social)','value'=>'QoS deprioritized','icon'=>'📶'],
                ['label'=>'KU Status','value'=>'42,000 users in silence simultaneously','icon'=>'🌿'],
            ] as $s)
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0;border-bottom:1px solid rgba(255,255,255,0.04);">
                <span style="font-size:1.1rem;">{{ $s['icon'] }}</span>
                <div style="flex:1;">
                    <div style="font-size:0.72rem;color:var(--text-muted);font-weight:600;margin-bottom:2px;">{{ $s['label'] }}</div>
                    <div style="font-size:0.82rem;color:#38bdf8;font-weight:600;">{{ $s['value'] }}</div>
                </div>
            </div>
            @endforeach
            <div style="margin-top:0.75rem;font-size:0.75rem;color:#38bdf8;font-style:italic;">There is nothing to miss. Everyone is in the same silence at the same time.</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b', font: { size: 11 } } },
        y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b', font: { size: 11 } } },
    }
};

const analyticsLabels = @json($analytics->map(fn($a) => optional($a->window)->label ? substr(optional($a->window)->label,0,15) : 'Window'));
const notifData = @json($analytics->pluck('notifications_held'));
const partFaculty = @json($analytics->pluck('faculty_participation_rate'));
const partStudent = @json($analytics->pluck('student_participation_rate'));
const criData = @json($analytics->pluck('avg_cri_improvement'));
const fomoLabels = @json($fomoScores->map(fn($f) => \Carbon\Carbon::parse($f->computed_at)->format('M j')));
const fomoVals = @json($fomoScores->pluck('composite_score'));

new Chart(document.getElementById('notifChart'), {
    type: 'bar',
    data: { labels: analyticsLabels, datasets: [{ data: notifData, backgroundColor: 'rgba(99,102,241,0.5)', borderColor: '#6366f1', borderWidth: 2, borderRadius: 6 }] },
    options: { ...chartDefaults, scales: { ...chartDefaults.scales, y: { ...chartDefaults.scales.y, beginAtZero: true } } }
});

new Chart(document.getElementById('partChart'), {
    type: 'line',
    data: { labels: analyticsLabels, datasets: [
        { label: 'Faculty', data: partFaculty, borderColor: '#6366f1', tension: 0.4, fill: false, pointRadius: 4 },
        { label: 'Student', data: partStudent, borderColor: '#0ea5e9', tension: 0.4, fill: false, pointRadius: 4 }
    ]},
    options: { ...chartDefaults, plugins: { legend: { display: true, labels: { color: '#94a3b8', font: { size: 11 } } } } }
});

new Chart(document.getElementById('fomoTrend'), {
    type: 'line',
    data: { labels: fomoLabels, datasets: [{ data: fomoVals, borderColor: '#a78bfa', backgroundColor: 'rgba(167,139,250,0.08)', fill: true, tension: 0.4, pointRadius: 4 }] },
    options: { ...chartDefaults, scales: { ...chartDefaults.scales, y: { ...chartDefaults.scales.y, min: 0, max: 100 } } }
});

new Chart(document.getElementById('criChart'), {
    type: 'bar',
    data: { labels: analyticsLabels, datasets: [{ data: criData, backgroundColor: 'rgba(16,185,129,0.4)', borderColor: '#10b981', borderWidth: 2, borderRadius: 6 }] },
    options: { ...chartDefaults, scales: { ...chartDefaults.scales, y: { ...chartDefaults.scales.y, beginAtZero: true } } }
});
</script>
</x-layouts.kukai>
