<x-layouts.kukai title="Users">
<div class="section-header">
    <h2>Users</h2>
    <p>Faculty and researcher accounts enrolled in KU</p>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    {{-- FACULTY --}}
    <div>
        <h3 style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:700;margin-bottom:1rem;color:var(--text-secondary);">
            👨‍🏫 Faculty ({{ $faculty->count() }})
        </h3>
        <div style="display:grid;gap:0.75rem;">
            @foreach($faculty as $user)
            <div class="card card-body" style="padding:1rem 1.25rem;display:flex;align-items:center;gap:1rem;">
                <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#0ea5e9);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;flex-shrink:0;">
                    {{ $user->avatar_initials ?? substr($user->name,0,2) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $user->department }}</div>
                </div>
                <div style="flex-shrink:0;text-align:right;">
                    @if($user->shield)
                    <div style="font-size:0.72rem;color:{{ $user->shield->is_active ? '#34d399' : 'var(--text-muted)' }};">
                        🛡️ {{ $user->shield->is_active ? 'Shield Active' : 'Shield Off' }}
                    </div>
                    <div style="font-size:0.7rem;color:var(--text-muted);">{{ $user->shield->messages_queued_total }} msgs queued total</div>
                    @else
                    <div style="font-size:0.72rem;color:var(--text-muted);">No shield configured</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- RESEARCHERS --}}
    <div>
        <h3 style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:700;margin-bottom:1rem;color:var(--text-secondary);">
            🔭 Researchers ({{ $researchers->count() }})
        </h3>
        <div style="display:grid;gap:0.75rem;">
            @foreach($researchers as $user)
            <div class="card card-body" style="padding:1rem 1.25rem;display:flex;align-items:center;gap:1rem;">
                <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#a78bfa,#6366f1);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;flex-shrink:0;">
                    {{ $user->avatar_initials ?? substr($user->name,0,2) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:0.9rem;">{{ $user->name }}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $user->department }}</div>
                    <div style="font-size:0.72rem;color:var(--text-muted);">{{ $user->position }}</div>
                </div>
                <span class="pill pill-indigo">Research Access</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
</x-layouts.kukai>
