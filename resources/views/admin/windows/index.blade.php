<x-layouts.kukai title="Synchronized Windows">
<div class="section-header" style="display:flex;justify-content:space-between;align-items:flex-start;">
    <div>
        <h2>Synchronized Windows</h2>
        <p>Schedule and manage institutional collective disconnection windows</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <a href="{{ route('admin.simulator') }}" class="btn btn-secondary">🧪 AI Simulator</a>
        <a href="{{ route('admin.windows.create') }}" class="btn btn-primary">+ Create Window</a>
    </div>
</div>

{{-- WINDOWS GRID --}}
<div style="display:grid;gap:1rem;">
    @forelse($windows as $window)
    @php $latest = $window->analytics->first(); @endphp
    <div class="card">
        <div class="card-body" style="display:flex;align-items:center;gap:1.5rem;">
            {{-- Day Badge --}}
            <div style="width:70px;text-align:center;flex-shrink:0;">
                <div style="font-family:'Outfit',sans-serif;font-size:1.6rem;font-weight:800;color:var(--color-primary);">
                    {{ substr($window->day_of_week,0,3) }}
                </div>
                <div style="font-size:0.65rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Day</div>
            </div>
            <div style="width:1px;background:var(--glass-border);align-self:stretch;"></div>
            {{-- Details --}}
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:4px;">
                    <span style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:700;">{{ $window->label }}</span>
                    @if($window->is_active)
                        <span class="pill pill-sky">● ACTIVE NOW</span>
                    @endif
                    @if($window->triggered_manually && $window->is_active)
                        <span class="pill pill-amber">MANUAL</span>
                    @endif
                    @if($window->is_recurring)
                        <span class="pill pill-indigo">RECURRING</span>
                    @endif
                </div>
                <div style="font-size:0.85rem;color:var(--text-secondary);">
                    🕐 {{ \Carbon\Carbon::parse($window->start_time)->format('g:i A') }}
                    → {{ \Carbon\Carbon::parse($window->end_time)->format('g:i A') }}
                    &nbsp;·&nbsp; {{ $window->duration_minutes }} min
                    &nbsp;·&nbsp; ~{{ number_format($window->estimated_participants) }} participants
                </div>
                @if($window->description)
                <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">{{ $window->description }}</div>
                @endif
            </div>
            {{-- Last Analytics --}}
            @if($latest)
            <div style="text-align:right;flex-shrink:0;min-width:140px;">
                <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Last Window</div>
                <div style="font-size:0.82rem;color:var(--text-secondary);">{{ $latest->participants ? number_format($latest->participants) . ' attended' : '—' }}</div>
                <div style="font-size:0.78rem;color:#34d399;">+{{ $latest->avg_cri_improvement }}% CRI</div>
            </div>
            @endif
            {{-- Actions --}}
            <div style="display:flex;gap:0.5rem;flex-direction:column;flex-shrink:0;">
                {{-- Trigger / End --}}
                <form method="POST" action="{{ route('admin.windows.trigger', $window) }}">
                    @csrf
                    @if($window->is_active)
                        <button class="btn btn-danger btn-sm" style="width:100%;">⏹ End Window</button>
                    @else
                        <button class="btn btn-success btn-sm" style="width:100%;">▶ Start Now</button>
                    @endif
                </form>
                <a href="{{ route('admin.windows.edit', $window) }}" class="btn btn-secondary btn-sm" style="text-align:center;">✏️ Edit</a>
                <form method="POST" action="{{ route('admin.windows.destroy', $window) }}" onsubmit="return confirm('Delete this window?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" style="width:100%;">🗑️ Delete</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card card-body" style="text-align:center;padding:3rem;">
        <div style="font-size:3rem;margin-bottom:1rem;">🌑</div>
        <div style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:600;margin-bottom:0.5rem;">No Windows Scheduled</div>
        <div style="color:var(--text-muted);font-size:0.85rem;margin-bottom:1.5rem;">Create your first synchronized disconnection window to begin.</div>
        <a href="{{ route('admin.windows.create') }}" class="btn btn-primary">+ Create First Window</a>
    </div>
    @endforelse
</div>
</x-layouts.kukai>
