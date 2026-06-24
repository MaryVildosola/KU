<x-layouts.kukai title="Window Schedule">
<div class="section-header">
    <h2>Window Schedule</h2>
    <p>Semester synchronized window calendar — published to all participants</p>
</div>

@if($activeWindow)
<div style="background:linear-gradient(135deg,rgba(14,165,233,0.1),rgba(99,102,241,0.08));border:1px solid rgba(14,165,233,0.25);border-radius:12px;padding:1rem 1.5rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:1rem;">
    <span style="font-size:1.5rem;animation:pulse 1.5s infinite;">🌿</span>
    <div>
        <div style="font-weight:700;color:#38bdf8;">{{ $activeWindow->label }} — Active Now</div>
        <div style="font-size:0.82rem;color:var(--text-secondary);">{{ \Carbon\Carbon::parse($activeWindow->end_time)->format('g:i A') }} end time</div>
    </div>
</div>
@endif

@if($nextWindow)
<div style="background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.15);border-radius:12px;padding:1rem 1.5rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;">
    <div>
        <div style="font-size:0.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Next Window</div>
        <div style="font-weight:700;color:var(--text-primary);margin-top:2px;">{{ $nextWindow['window']->label }}</div>
        <div style="font-size:0.82rem;color:var(--text-secondary);">
            {{ $nextWindow['window']->day_of_week }}
            · {{ \Carbon\Carbon::parse($nextWindow['window']->start_time)->format('g:i A') }}
            – {{ \Carbon\Carbon::parse($nextWindow['window']->end_time)->format('g:i A') }}
        </div>
    </div>
    <div id="sched-countdown" data-seconds="{{ $nextWindow['seconds_until'] }}" style="font-family:'Outfit',sans-serif;font-size:1.6rem;font-weight:700;color:var(--color-primary);">--:--:--</div>
</div>
@endif

{{-- WEEKLY SCHEDULE GRID --}}
<div class="card card-body">
    <div class="card-title" style="margin-bottom:1.25rem;">📅 Weekly Recurring Windows</div>
    @php $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']; @endphp
    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:0.75rem;">
        @foreach($days as $day)
        @php $dayWindows = $windows->where('day_of_week', $day); @endphp
        <div>
            <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:0.5rem;text-align:center;">{{ substr($day,0,3) }}</div>
            @if($dayWindows->count() > 0)
                @foreach($dayWindows as $win)
                <div style="background:rgba(99,102,241,{{ $win->is_active ? '0.25' : '0.1' }});border:1px solid rgba(99,102,241,{{ $win->is_active ? '0.5' : '0.2' }});border-radius:10px;padding:0.75rem 0.5rem;text-align:center;margin-bottom:0.5rem;">
                    <div style="font-size:0.68rem;color:#818cf8;font-weight:700;margin-bottom:4px;">{{ \Carbon\Carbon::parse($win->start_time)->format('g:i A') }}</div>
                    <div style="font-size:0.62rem;color:var(--text-muted);">→ {{ \Carbon\Carbon::parse($win->end_time)->format('g:i A') }}</div>
                    <div style="font-size:0.6rem;color:var(--text-muted);margin-top:4px;">{{ $win->duration_minutes }}m</div>
                    @if($win->is_active)
                    <div style="font-size:0.6rem;color:#38bdf8;font-weight:700;margin-top:4px;">● LIVE</div>
                    @endif
                </div>
                @endforeach
            @else
            <div style="text-align:center;padding:1rem 0.5rem;border:1px dashed rgba(255,255,255,0.06);border-radius:10px;">
                <div style="font-size:0.65rem;color:var(--text-muted);">Rest Day</div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

<script>
const el = document.getElementById('sched-countdown');
if (el) {
    let s = parseInt(el.dataset.seconds);
    (function tick() {
        const h=Math.floor(s/3600), m=Math.floor((s%3600)/60), sec=s%60;
        el.textContent=`${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`;
        if(s>0){s--;setTimeout(tick,1000);}
    })();
}
</script>
</x-layouts.kukai>
