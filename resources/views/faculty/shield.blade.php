<x-layouts.kukai title="Faculty Shield">
<div style="max-width:700px;">
    <div class="section-header">
        <h2>Faculty Shield</h2>
        <p>Configure your personal disconnection shield and auto-reply message</p>
    </div>

    <div class="card card-body" style="margin-bottom:1.25rem;background:rgba(99,102,241,0.05);border-color:rgba(99,102,241,0.2);">
        <div style="display:flex;align-items:center;gap:1rem;">
            <div style="font-size:2rem;">🛡️</div>
            <div>
                <div style="font-weight:700;font-size:1rem;margin-bottom:2px;">
                    Shield Status: <span style="color:{{ $shield->is_active ? '#34d399' : 'var(--text-muted)' }};">{{ $shield->is_active ? 'ACTIVE' : 'INACTIVE' }}</span>
                </div>
                <div style="font-size:0.82rem;color:var(--text-secondary);">
                    When active, messages sent to you during windows receive an auto-reply and are queued for simultaneous release.
                    {{ $shield->messages_queued_total }} total messages queued across all past windows.
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <form method="POST" action="{{ route('faculty.shield.update') }}">
            @csrf
            <div style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 0;border-bottom:1px solid var(--glass-border);margin-bottom:1.25rem;">
                <div>
                    <div style="font-weight:600;font-size:0.95rem;">Enable Faculty Shield</div>
                    <div style="font-size:0.78rem;color:var(--text-muted);">Activates during all scheduled KU windows automatically</div>
                </div>
                <label class="toggle">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ $shield->is_active ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 0;border-bottom:1px solid var(--glass-border);margin-bottom:1.25rem;">
                <div>
                    <div style="font-weight:600;font-size:0.95rem;">Allow Crisis Bypass</div>
                    <div style="font-size:0.78rem;color:var(--text-muted);">Students flagged by the Crisis Detection Engine can reach you during windows</div>
                </div>
                <label class="toggle">
                    <input type="hidden" name="allow_student_crisis_bypass" value="0">
                    <input type="checkbox" name="allow_student_crisis_bypass" value="1" {{ $shield->allow_student_crisis_bypass ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="form-group">
                <label class="form-label">Auto-Reply Message</label>
                <textarea name="auto_reply_text" class="form-control" rows="5" maxlength="500" placeholder="Enter the message students will receive when they contact you during a window…">{{ $shield->auto_reply_text }}</textarea>
                <div style="font-size:0.72rem;color:var(--text-muted);margin-top:4px;">Max 500 characters · Sent to all senders during active windows</div>
            </div>

            <div style="background:rgba(255,255,255,0.03);border:1px solid var(--glass-border);border-radius:10px;padding:1rem;margin-bottom:1.25rem;">
                <div style="font-size:0.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">👁️ Student Preview — What They See</div>
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;">
                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#0ea5e9);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0;">
                        {{ auth()->user()->avatar_initials ?? substr(auth()->user()->name,0,2) }}
                    </div>
                    <div>
                        <div style="font-size:0.78rem;font-weight:600;">{{ auth()->user()->name }}</div>
                        <div style="font-size:0.68rem;color:#38bdf8;">🌿 In KU Window · Auto-reply active</div>
                    </div>
                </div>
                <div id="preview-text" style="font-size:0.8rem;color:var(--text-secondary);font-style:italic;padding-left:40px;">{{ $shield->auto_reply_text }}</div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                <a href="{{ route('faculty.dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">💾 Save Shield Settings</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('[name="auto_reply_text"]').addEventListener('input', function() {
    document.getElementById('preview-text').textContent = this.value || '(No message set)';
});
</script>
</x-layouts.kukai>
