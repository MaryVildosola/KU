<x-layouts.kukai title="Admin Dashboard">
<div class="section-header" style="display:flex;justify-content:space-between;align-items:flex-start;">
    <div>
        <h2>Institutional Dashboard</h2>
        <p>KU — 空界 &nbsp;·&nbsp; {{ now()->format('l, F j, Y') }}</p>
    </div>
    @if($activeWindow)
        <form method="POST" action="{{ route('admin.windows.trigger', $activeWindow) }}">
            @csrf
            <button class="btn btn-danger">⏹ End Window Now</button>
        </form>
    @endif
</div>

{{-- WINDOW STATUS BANNER --}}
@if($activeWindow)
<div style="background:linear-gradient(135deg,rgba(14,165,233,0.12),rgba(99,102,241,0.08));border:1px solid rgba(14,165,233,0.25);border-radius:14px;padding:1.5rem 2rem;margin-bottom:2rem;display:flex;align-items:center;gap:1.5rem;">
    <div style="font-size:2.5rem;">🌿</div>
    <div style="flex:1;">
        <div style="font-family:'Outfit',sans-serif;font-size:1.25rem;font-weight:700;color:#38bdf8;">{{ $activeWindow->label }}</div>
        <div style="color:var(--text-secondary);font-size:0.85rem;margin-top:2px;">
            Active Window &nbsp;·&nbsp; Started {{ $activeWindow->updated_at->diffForHumans() }} &nbsp;·&nbsp; ~{{ number_format($activeWindow->estimated_participants) }} participants in silence
        </div>
    </div>
    <div style="text-align:right;">
        <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Window Status</div>
        <div style="display:flex;align-items:center;gap:6px;color:#0ea5e9;font-weight:700;">
            <span style="width:9px;height:9px;background:#0ea5e9;border-radius:50%;box-shadow:0 0 8px #0ea5e9;display:inline-block;animation:pulse 1.5s infinite;"></span>
            SILENCE ACTIVE
        </div>
    </div>
</div>
@else
<div style="background:rgba(15,23,42,0.5);border:1px solid var(--glass-border);border-radius:14px;padding:1.25rem 2rem;margin-bottom:2rem;display:flex;align-items:center;gap:1.5rem;">
    <div style="font-size:2rem;opacity:0.5;">🌑</div>
    <div style="flex:1;">
        <div style="font-family:'Outfit',sans-serif;font-weight:600;color:var(--text-secondary);">No Active Window</div>
        @if($nextWindow)
        <div style="font-size:0.82rem;color:var(--text-muted);">
            Next: <strong style="color:var(--text-primary);">{{ $nextWindow['window']->label }}</strong>
            &nbsp;·&nbsp; {{ $nextWindow['window']->day_of_week }} at {{ \Carbon\Carbon::parse($nextWindow['window']->start_time)->format('g:i A') }}
        </div>
        @endif
    </div>
    @if($nextWindow)
    <div id="countdown" style="font-family:'Outfit',sans-serif;font-size:1.4rem;font-weight:700;color:var(--text-muted);" data-seconds="{{ $nextWindow['seconds_until'] }}">
        --:--:--
    </div>
    @endif
</div>
@endif

{{-- KPI CARDS --}}
<div class="kpi-grid">
    <div class="card kpi-card">
        <div class="kpi-label">After-Hours LMS Notifications</div>
        <div class="kpi-value" style="color:#34d399;">↓ {{ $kpis['after_hours_lms_reduction'] }}%</div>
        <div class="kpi-trend good">Target reduction achieved</div>
    </div>
    <div class="card kpi-card">
        <div class="kpi-label">Focus Sessions (avg length)</div>
        <div class="kpi-value" style="color:#60a5fa;">↑ {{ $kpis['focus_session_increase'] }}%</div>
        <div class="kpi-trend good">vs. pre-KU baseline</div>
    </div>
    <div class="card kpi-card">
        <div class="kpi-label">Faculty After-Hours Comms</div>
        <div class="kpi-value" style="color:#34d399;">↓ {{ $kpis['faculty_comms_reduction'] }}%</div>
        <div class="kpi-trend good">Messages after 9 PM</div>
    </div>
    <div class="card kpi-card">
        <div class="kpi-label">Student Stress Levels</div>
        <div class="kpi-value" style="color:#a78bfa;">↓ {{ $kpis['stress_reduction'] }}%</div>
        <div class="kpi-trend good">Self-reported</div>
    </div>
    <div class="card kpi-card">
        <div class="kpi-label">Midnight LMS Activity</div>
        <div class="kpi-value" style="color:#34d399;">↓ {{ $kpis['midnight_lms_reduction'] }}%</div>
        <div class="kpi-trend good">12 AM – 2 AM logins</div>
    </div>
    <div class="card kpi-card">
        <div class="kpi-label">Faculty Retention Rate</div>
        <div class="kpi-value" style="color:#fbbf24;">↑ {{ $kpis['faculty_retention_increase'] }}%</div>
        <div class="kpi-trend good">Projected annual gain</div>
    </div>
</div>

{{-- MID ROW: FOMO + QUICK STATS --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.25rem;margin-bottom:1.5rem;">

    {{-- FOMO Risk Index --}}
    <div class="card card-body">
        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:0.5rem;">FOMO Risk Index</div>
        @if($latestFomo)
            <div style="font-family:'Outfit',sans-serif;font-size:2.5rem;font-weight:800;{{ $latestFomo->risk_level === 'low' ? 'color:#34d399' : ($latestFomo->risk_level === 'moderate' ? 'color:#fbbf24' : 'color:#f87171') }};">
                {{ $latestFomo->composite_score }}
            </div>
            <div class="pill pill-{{ match($latestFomo->risk_level){ 'low'=>'emerald','moderate'=>'amber','high'=>'red','critical'=>'red',default=>'slate'} }}" style="margin:4px 0 8px;">
                {{ strtoupper($latestFomo->risk_level) }} RISK
            </div>
            <div style="font-size:0.75rem;color:var(--text-muted);">
                Computed {{ $latestFomo->computed_at->diffForHumans() }} &nbsp;·&nbsp; {{ number_format($latestFomo->total_users_analyzed) }} users
            </div>
        @else
            <div style="color:var(--text-muted);font-size:0.85rem;">No diagnostic run yet.</div>
        @endif
        <a href="{{ route('admin.fomo') }}" class="btn btn-secondary btn-sm" style="margin-top:1rem;">View Full Diagnostic →</a>
    </div>

    {{-- Active Windows Stat --}}
    <div class="card card-body">
        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:0.5rem;">Synchronized Windows</div>
        <div style="font-family:'Outfit',sans-serif;font-size:2.5rem;font-weight:800;color:var(--color-primary);">{{ $totalWindows }}</div>
        <div style="font-size:0.82rem;color:var(--text-secondary);margin:4px 0 8px;">Scheduled this semester</div>
        <div style="font-size:0.75rem;color:var(--text-muted);">{{ $recentAnalytics->count() }} analytics records available</div>
        <a href="{{ route('admin.windows') }}" class="btn btn-secondary btn-sm" style="margin-top:1rem;">Manage Windows →</a>
    </div>

    {{-- Pending Accommodations --}}
    <div class="card card-body">
        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:0.5rem;">Pending Accommodations</div>
        <div style="font-family:'Outfit',sans-serif;font-size:2.5rem;font-weight:800;color:{{ $pendingAccomm > 0 ? '#fbbf24' : '#34d399' }};">
            {{ $pendingAccomm }}
        </div>
        <div style="font-size:0.82rem;color:var(--text-secondary);margin:4px 0 8px;">Student requests awaiting review</div>
        <div style="font-size:0.75rem;color:var(--text-muted);">{{ $facultyCount }} faculty enrolled in KU</div>
        <a href="{{ route('admin.accommodations') }}" class="btn btn-secondary btn-sm" style="margin-top:1rem;">Review Requests →</a>
    </div>

</div>

{{-- RECENT WINDOW ANALYTICS --}}
<div class="card">
    <div class="card-body">
        <div class="card-header-bar">
            <div class="card-title">Recent Window Analytics</div>
            <a href="{{ route('admin.analytics') }}" class="btn btn-secondary btn-sm">Full Analytics →</a>
        </div>
        <table class="ku-table">
            <thead>
                <tr>
                    <th>Window</th>
                    <th>Date</th>
                    <th>Participants</th>
                    <th>Notifications Held</th>
                    <th>CRI Improvement</th>
                    <th>Faculty Rate</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAnalytics as $a)
                <tr>
                    <td><strong>{{ $a->window?->label ?? '—' }}</strong></td>
                    <td style="color:var(--text-muted);">{{ $a->window_started_at->format('D, M j') }}</td>
                    <td>{{ number_format($a->participants) }}</td>
                    <td><span class="pill pill-sky">{{ number_format($a->notifications_held) }}</span></td>
                    <td style="color:#34d399;">+{{ $a->avg_cri_improvement }}%</td>
                    <td>{{ $a->faculty_participation_rate }}%</td>
                </tr>
                @empty
                <tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:2rem;">No analytics data yet. Trigger a window to begin recording.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
// Countdown timer
const cdEl = document.getElementById('countdown');
if (cdEl) {
    let seconds = parseInt(cdEl.dataset.seconds);
    const tick = () => {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        cdEl.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        if (seconds > 0) { seconds--; setTimeout(tick, 1000); }
    };
    tick();
}
</script>
</x-layouts.kukai>
