<x-layouts.kukai title="Message Queue">
<div class="section-header">
    <h2>Message Queue</h2>
    <p>Messages held during KU windows — released simultaneously to all recipients when windows end</p>
</div>

@if($held->count() > 0)
<div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:12px;padding:1rem 1.5rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:1rem;">
    <span style="font-size:1.5rem;">⏸️</span>
    <div>
        <div style="font-weight:700;color:#fbbf24;">{{ $held->count() }} messages currently held</div>
        <div style="font-size:0.82rem;color:var(--text-secondary);">These will be released simultaneously at window end.</div>
    </div>
</div>
@endif

{{-- HELD (active) --}}
@if($held->count() > 0)
<h3 style="font-family:'Outfit',sans-serif;font-size:0.9rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.75rem;">⏸️ Currently Held</h3>
<div style="display:grid;gap:0.5rem;margin-bottom:1.5rem;">
    @foreach($held as $msg)
    <div class="card card-body" style="padding:1rem;display:flex;gap:1rem;align-items:flex-start;border-color:rgba(245,158,11,0.15);">
        <span style="font-size:1.2rem;flex-shrink:0;">{{ match($msg->app_type){'lms'=>'📚','email'=>'📧','social'=>'💬',default=>'🔔'} }}</span>
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:4px;">
                <span style="font-weight:600;font-size:0.85rem;">{{ $msg->sender }}</span>
                <span class="pill pill-amber" style="font-size:0.65rem;">{{ strtoupper($msg->app_type) }}</span>
                <span style="color:var(--text-muted);font-size:0.75rem;">{{ $msg->held_at->format('g:i A') }}</span>
            </div>
            <div style="font-size:0.82rem;color:var(--text-secondary);">{{ $msg->message }}</div>
            <div style="font-size:0.72rem;color:var(--text-muted);margin-top:4px;">{{ number_format($msg->recipient_count) }} recipients</div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- RELEASED (recent) --}}
<h3 style="font-family:'Outfit',sans-serif;font-size:0.9rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.75rem;">✅ Recently Released</h3>
@if($released->count() > 0)
<div class="card">
    <table class="ku-table">
        <thead>
            <tr>
                <th>Type</th><th>Sender</th><th>Message</th><th>Held At</th><th>Released At</th><th>Recipients</th>
            </tr>
        </thead>
        <tbody>
            @foreach($released as $msg)
            <tr>
                <td><span class="pill pill-{{ match($msg->app_type){'lms'=>'sky','email'=>'indigo','social'=>'emerald',default=>'slate'} }}">{{ strtoupper($msg->app_type) }}</span></td>
                <td style="font-weight:600;">{{ $msg->sender }}</td>
                <td style="color:var(--text-secondary);max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $msg->message }}</td>
                <td style="color:var(--text-muted);">{{ $msg->held_at->format('M j, g:i A') }}</td>
                <td style="color:#34d399;">{{ $msg->released_at?->format('M j, g:i A') ?? '—' }}</td>
                <td>{{ number_format($msg->recipient_count) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="card card-body" style="text-align:center;padding:2rem;color:var(--text-muted);">No released messages yet.</div>
@endif
</x-layouts.kukai>
