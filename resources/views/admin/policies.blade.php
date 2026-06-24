<x-layouts.kukai title="Policies">
<div class="section-header">
    <h2>Institutional Policies</h2>
    <p>Configure system-wide KU behavioral controls and integrations</p>
</div>

@php
$policies = [
    ['id'=>'lms_deadline', 'label'=>'LMS Deadline Suspension', 'icon'=>'📚', 'description'=>'Pause deadline countdowns and suppress penalty accumulation for all enrolled users during active windows.', 'enabled'=>true, 'badge'=>'Canvas / Moodle'],
    ['id'=>'email_hold', 'label'=>'Email Queue Hold', 'icon'=>'📧', 'description'=>'Queue all university email during active windows via Microsoft Graph API (M365) or Gmail API. Released simultaneously to all users when the window ends.', 'enabled'=>true, 'badge'=>'M365 + Gmail'],
    ['id'=>'qos', 'label'=>'Campus WiFi QoS Deprioritization', 'icon'=>'📶', 'description'=>'Reduce bandwidth to social media domains during windows using Quality of Service rules on existing Cisco Meraki / Palo Alto network hardware. Friction-based only — no blocking.', 'enabled'=>true, 'badge'=>'Cisco Meraki'],
    ['id'=>'faculty_shield', 'label'=>'Faculty Auto-Reply Shield (Global)', 'icon'=>'🛡️', 'description'=>'Automatically enable Faculty Shield for all faculty accounts during windows. Individual faculty may customize their auto-reply message in their personal portal.', 'enabled'=>true, 'badge'=>'All Faculty'],
    ['id'=>'collective_status', 'label'=>'Collective Status Signal Broadcast', 'icon'=>'📡', 'description'=>'Broadcast real-time silence status to all KU mobile app instances on campus during active windows. Students see their entire cohort offline simultaneously.', 'enabled'=>true, 'badge'=>'Socket.io'],
    ['id'=>'federated_learning', 'label'=>'Federated Learning Gradient Sync', 'icon'=>'🧠', 'description'=>'Allow the on-device AI to transmit only mathematical model gradients (never raw data) during connected hours to improve Cognitive Recovery Index accuracy across users.', 'enabled'=>true, 'badge'=>'TF Federated'],
    ['id'=>'research_mode', 'label'=>'Institutional Research Analytics Mode', 'icon'=>'🔭', 'description'=>'Enable the Research Analytics Engine to generate anonymized, aggregated behavioral datasets for authorized internal researchers. No individual user data is identifiable.', 'enabled'=>true, 'badge'=>'Research Portal'],
];
@endphp

<div style="display:grid;gap:1rem;">
    @foreach($policies as $policy)
    <div class="card card-body" style="display:flex;align-items:flex-start;gap:1.5rem;">
        <div style="font-size:1.75rem;flex-shrink:0;margin-top:2px;">{{ $policy['icon'] }}</div>
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:4px;">
                <span style="font-weight:600;font-size:0.95rem;">{{ $policy['label'] }}</span>
                <span class="pill pill-sky">{{ $policy['badge'] }}</span>
            </div>
            <p style="font-size:0.82rem;color:var(--text-secondary);max-width:600px;">{{ $policy['description'] }}</p>
        </div>
        <div style="flex-shrink:0;display:flex;flex-direction:column;align-items:center;gap:4px;">
            <label class="toggle">
                <input type="checkbox" {{ $policy['enabled'] ? 'checked' : '' }} onchange="togglePolicy('{{ $policy['id'] }}', this.checked)">
                <span class="toggle-slider"></span>
            </label>
            <span style="font-size:0.68rem;color:{{ $policy['enabled'] ? 'var(--color-accent)' : 'var(--text-muted)' }};" id="label-{{ $policy['id'] }}">
                {{ $policy['enabled'] ? 'ACTIVE' : 'OFF' }}
            </span>
        </div>
    </div>
    @endforeach
</div>

<div class="card card-body" style="margin-top:1.5rem;background:rgba(239,68,68,0.04);border-color:rgba(239,68,68,0.15);">
    <div style="display:flex;align-items:flex-start;gap:1rem;">
        <span style="font-size:1.5rem;">🚨</span>
        <div>
            <div style="font-weight:700;color:#f87171;margin-bottom:4px;">Crisis Detection Engine</div>
            <p style="font-size:0.82rem;color:var(--text-secondary);">The on-device Crisis Detection Engine operates independently on each student's KU mobile app. It monitors CRI signals for self-harm indicators and automatically overrides the window for affected individuals, directing them to campus support. <strong style="color:var(--text-primary);">This engine is never accessible from the institutional dashboard.</strong> Student privacy is structural — no admin visibility, by design.</p>
        </div>
        <div style="flex-shrink:0;">
            <span class="pill" style="background:rgba(239,68,68,0.1);color:#f87171;font-size:0.7rem;padding:4px 10px;">ALWAYS ON · PRIVATE</span>
        </div>
    </div>
</div>

<script>
function togglePolicy(id, enabled) {
    const label = document.getElementById('label-' + id);
    label.textContent = enabled ? 'ACTIVE' : 'OFF';
    label.style.color = enabled ? 'var(--color-accent)' : 'var(--text-muted)';
    // In production: AJAX call to persist policy state
}
</script>
</x-layouts.kukai>
