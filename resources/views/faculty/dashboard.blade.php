<x-layouts.kukai title="Faculty Dashboard">
<div class="section-header" style="display:flex;justify-content:space-between;align-items:flex-start;">
    <div>
        <h2>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}</h2>
        <p>{{ auth()->user()->position }} &nbsp;·&nbsp; {{ auth()->user()->department }}</p>
    </div>
    <span style="font-size:0.82rem;color:var(--text-muted);">{{ now()->format('l, F j, Y · g:i A') }}</span>
</div>

{{-- ACTIVE WINDOW BANNER --}}
@if($activeWindow)
<div style="background:linear-gradient(135deg,rgba(14,165,233,0.12),rgba(99,102,241,0.08));border:1px solid rgba(14,165,233,0.25);border-radius:14px;padding:1.5rem;margin-bottom:2rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="font-size:2.5rem;">🛡️</div>
        <div>
            <div style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:#38bdf8;">{{ $activeWindow->label }} — Active Now</div>
            <div style="font-size:0.85rem;color:var(--text-secondary);">Your Faculty Shield is active. Incoming messages are being queued and will be released when the window ends.</div>
        </div>
        <div style="margin-left:auto;text-align:right;">
            <div style="font-size:0.7rem;color:var(--text-muted);">Queued messages</div>
            <div style="font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:700;color:#38bdf8;">{{ $queuedCount }}</div>
        </div>
    </div>
</div>
@endif

{{-- STATS ROW --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="card kpi-card">
        <div class="kpi-label">Faculty Shield</div>
        <div style="font-family:'Outfit',sans-serif;font-size:1.4rem;font-weight:700;color:{{ $shield->is_active ? '#34d399' : 'var(--text-muted)' }};">
            {{ $shield->is_active ? '🛡️ Active' : '◯ Inactive' }}
        </div>
    </div>
    <div class="card kpi-card">
        <div class="kpi-label">Messages Queued (Total)</div>
        <div class="kpi-value" style="font-size:1.8rem;">{{ number_format($shield->messages_queued_total) }}</div>
    </div>
    <div class="card kpi-card">
        <div class="kpi-label">Burnout Index</div>
        <div class="kpi-value" style="font-size:1.8rem;color:#34d399;">{{ $burnoutData['current_index'] }}</div>
        <div class="kpi-trend good">↓ {{ $burnoutData['prev_index'] - $burnoutData['current_index'] }} pts from baseline</div>
    </div>
    <div class="card kpi-card">
        <div class="kpi-label">Wellness Compliance</div>
        <div class="kpi-value" style="font-size:1.8rem;color:#34d399;">{{ $burnoutData['compliance_score'] }}%</div>
        <div class="kpi-trend good">Accreditation ready</div>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    {{-- Shield Card --}}
    <div class="card card-body">
        <div class="card-header-bar">
            <div class="card-title">🛡️ Faculty Shield</div>
            <a href="{{ route('faculty.shield') }}" class="btn btn-secondary btn-sm">Configure →</a>
        </div>
        <div style="background:rgba(255,255,255,0.03);border-radius:10px;padding:1rem;margin-bottom:1rem;">
            <div style="font-size:0.72rem;color:var(--text-muted);margin-bottom:6px;">AUTO-REPLY MESSAGE PREVIEW</div>
            <p style="font-size:0.82rem;color:var(--text-secondary);font-style:italic;">{{ $shield->auto_reply_text ?? 'No auto-reply message set.' }}</p>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.82rem;color:var(--text-secondary);">Crisis bypass allowed:</span>
            <span style="font-size:0.82rem;color:{{ $shield->allow_student_crisis_bypass ? '#34d399' : '#f87171' }};font-weight:600;">
                {{ $shield->allow_student_crisis_bypass ? '✅ Yes' : '❌ No' }}
            </span>
        </div>
    </div>

    {{-- Next Window --}}
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1rem;">📅 Next Scheduled Window</div>
        @if($nextWindow)
        <div style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:var(--color-primary);margin-bottom:4px;">
            {{ $nextWindow['window']->label }}
        </div>
        <div style="font-size:0.85rem;color:var(--text-secondary);margin-bottom:1rem;">
            {{ $nextWindow['window']->day_of_week }}
            · {{ \Carbon\Carbon::parse($nextWindow['window']->start_time)->format('g:i A') }}
            – {{ \Carbon\Carbon::parse($nextWindow['window']->end_time)->format('g:i A') }}
        </div>
        <div id="fac-countdown" style="font-family:'Outfit',sans-serif;font-size:2rem;font-weight:800;color:var(--text-primary);"
             data-seconds="{{ $nextWindow['seconds_until'] }}">--:--:--</div>
        <div style="font-size:0.72rem;color:var(--text-muted);margin-top:4px;">until silence begins</div>
        @else
        <div style="color:var(--text-muted);">No upcoming windows scheduled.</div>
        @endif
    </div>

    {{-- Burnout Reduction --}}
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1rem;">📉 Burnout Index Reduction</div>
        <div style="display:flex;align-items:flex-end;gap:0.5rem;margin-bottom:1rem;">
            <div style="text-align:center;">
                <div style="font-size:0.7rem;color:var(--text-muted);">Before KU</div>
                <div style="font-family:'Outfit',sans-serif;font-size:2rem;font-weight:700;color:#f87171;">{{ $burnoutData['prev_index'] }}</div>
            </div>
            <div style="font-size:1.5rem;color:var(--text-muted);padding-bottom:0.5rem;">→</div>
            <div style="text-align:center;">
                <div style="font-size:0.7rem;color:var(--text-muted);">Current</div>
                <div style="font-family:'Outfit',sans-serif;font-size:2rem;font-weight:700;color:#34d399;">{{ $burnoutData['current_index'] }}</div>
            </div>
        </div>
        <div style="font-size:0.82rem;color:var(--text-secondary);">
            After-hours messages this week: <strong style="color:var(--text-primary);">{{ $burnoutData['after_hours_msgs'] }}</strong>
            <span style="color:#34d399;">(↓ 55% vs. baseline)</span>
        </div>
        <a href="{{ route('faculty.analytics') }}" class="btn btn-secondary btn-sm" style="margin-top:1rem;">Full Analytics →</a>
    </div>

    {{-- Quick Links --}}
    <div class="card card-body">
        <div class="card-title" style="margin-bottom:1rem;">⚡ Quick Actions</div>
        <div style="display:grid;gap:0.5rem;">
            <a href="{{ route('faculty.queue') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                📨 View Message Queue <span style="margin-left:auto;color:#fbbf24;">{{ $queuedCount }} pending</span>
            </a>
            <a href="{{ route('faculty.shield') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                🛡️ Configure Faculty Shield
            </a>
            <a href="{{ route('faculty.schedule') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                📅 View Window Schedule
            </a>
            <a href="{{ route('faculty.analytics') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                📉 My Burnout Analytics
            </a>
        </div>
    </div>
</div>

<script>
const el = document.getElementById('fac-countdown');
if (el) {
    let s = parseInt(el.dataset.seconds);
    (function tick() {
        const h=Math.floor(s/3600), m=Math.floor((s%3600)/60), sec=s%60;
        el.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`;
        if(s>0){s--;setTimeout(tick,1000);}
    })();
}
</script>
</x-layouts.kukai>
