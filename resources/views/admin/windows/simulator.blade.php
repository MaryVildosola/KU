<x-layouts.kukai title="AI Window Simulator">
<div class="section-header">
    <h2>AI Window Simulator</h2>
    <p>Test a proposed window configuration against historical FOMO data to predict institutional impact before deployment</p>
</div>

<div style="display:grid;grid-template-columns:420px 1fr;gap:1.5rem;align-items:start;">

    {{-- CONFIG PANEL --}}
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1.25rem;">⚙️ Configure Test Window</div>
        <div class="form-group">
            <label class="form-label">Day of Week</label>
            <select id="sim-day" class="form-control">
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <option value="{{ $day }}" {{ $day==='Monday'?'selected':'' }}>{{ $day }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div class="form-group">
                <label class="form-label">Start Time</label>
                <input type="time" id="sim-start" class="form-control" value="20:00">
            </div>
            <div class="form-group">
                <label class="form-label">End Time</label>
                <input type="time" id="sim-end" class="form-control" value="21:00">
            </div>
        </div>
        <div style="background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.15);border-radius:10px;padding:1rem;margin-bottom:1.25rem;">
            <div style="font-size:0.75rem;color:#818cf8;font-weight:600;margin-bottom:6px;">💡 AI Insight</div>
            <div style="font-size:0.8rem;color:var(--text-secondary);">Evening windows (8–10 PM) typically yield the highest FOMO reduction scores, coinciding with peak after-hours notification activity on campus.</div>
        </div>
        <button id="sim-btn" class="btn btn-primary" style="width:100%;justify-content:center;">
            🧪 Run Simulation
        </button>
        <div id="sim-loading" style="display:none;text-align:center;padding:1rem;color:var(--text-muted);font-size:0.85rem;">
            ⏳ Analyzing window configuration…
        </div>
    </div>

    {{-- RESULTS --}}
    <div>
        <div id="sim-placeholder" class="card card-body" style="text-align:center;padding:3rem;opacity:0.6;">
            <div style="font-size:3rem;margin-bottom:1rem;">🔬</div>
            <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:600;">Configure and run simulation</div>
            <div style="color:var(--text-muted);font-size:0.82rem;margin-top:4px;">Predicted impact results will appear here</div>
        </div>
        <div id="sim-results" style="display:none;">
            {{-- Results are injected by JS --}}
        </div>
    </div>
</div>

<script>
document.getElementById('sim-btn').addEventListener('click', async function() {
    const day   = document.getElementById('sim-day').value;
    const start = document.getElementById('sim-start').value;
    const end   = document.getElementById('sim-end').value;

    document.getElementById('sim-loading').style.display = 'block';
    document.getElementById('sim-btn').style.display = 'none';

    try {
        const res = await fetch('{{ route('admin.simulator.run') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ day_of_week: day, start_time: start, end_time: end }),
        });
        const data = await res.json();
        renderResults(data, day, start, end);
    } catch(e) {
        alert('Simulation failed. Please try again.');
    } finally {
        document.getElementById('sim-loading').style.display = 'none';
        document.getElementById('sim-btn').style.display = 'flex';
    }
});

function renderResults(data, day, start, end) {
    document.getElementById('sim-placeholder').style.display = 'none';
    const score = data.fomo_reduction_score;
    const color = score >= 75 ? '#34d399' : score >= 55 ? '#fbbf24' : '#f87171';

    document.getElementById('sim-results').innerHTML = `
        <div class="card card-body" style="margin-bottom:1rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                <div>
                    <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Simulated: ${day} · ${start} – ${end}</div>
                    <div style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;margin-top:4px;">Predicted Impact Analysis</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">FOMO Reduction Score</div>
                    <div style="font-family:'Outfit',sans-serif;font-size:2.5rem;font-weight:800;color:${color};">${score}</div>
                </div>
            </div>
            <div style="background:rgba(${score>=75?'16,185,129':score>=55?'245,158,11':'239,68,68'},0.08);border:1px solid rgba(${score>=75?'16,185,129':score>=55?'245,158,11':'239,68,68'},0.2);border-radius:10px;padding:1rem;margin-bottom:1.25rem;">
                <div style="font-size:0.8rem;color:var(--text-secondary);">📊 ${data.recommendation}</div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                <div style="text-align:center;background:rgba(255,255,255,0.03);border-radius:10px;padding:1rem;">
                    <div style="font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:700;color:#a78bfa;">+${data.predicted_cri_improvement}%</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">Predicted CRI Improvement</div>
                </div>
                <div style="text-align:center;background:rgba(255,255,255,0.03);border-radius:10px;padding:1rem;">
                    <div style="font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:700;color:#38bdf8;">${data.predicted_notifications_held.toLocaleString()}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">Notifications Held</div>
                </div>
                <div style="text-align:center;background:rgba(255,255,255,0.03);border-radius:10px;padding:1rem;">
                    <div style="font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:700;color:#34d399;">${data.predicted_participation_rate}%</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">Predicted Participation</div>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.windows.create') }}" class="btn btn-primary" style="width:100%;justify-content:center;">
            + Deploy This Window Configuration
        </a>
    `;
    document.getElementById('sim-results').style.display = 'block';
}
</script>
</x-layouts.kukai>
