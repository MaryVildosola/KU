<x-layouts.kukai title="Accommodation Requests">
<div class="section-header">
    <h2>Accommodation Requests</h2>
    <p>Student window modification requests submitted via the KU mobile app</p>
</div>

{{-- STATUS SUMMARY --}}
@php
$pending  = $requests->where('status','pending')->count();
$approved = $requests->where('status','approved')->count();
$denied   = $requests->where('status','denied')->count();
$review   = $requests->where('status','under_review')->count();
@endphp
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="card kpi-card"><div class="kpi-label">Pending</div><div class="kpi-value" style="color:#fbbf24;font-size:1.8rem;">{{ $pending }}</div></div>
    <div class="card kpi-card"><div class="kpi-label">Under Review</div><div class="kpi-value" style="color:#60a5fa;font-size:1.8rem;">{{ $review }}</div></div>
    <div class="card kpi-card"><div class="kpi-label">Approved</div><div class="kpi-value" style="color:#34d399;font-size:1.8rem;">{{ $approved }}</div></div>
    <div class="card kpi-card"><div class="kpi-label">Denied</div><div class="kpi-value" style="color:#f87171;font-size:1.8rem;">{{ $denied }}</div></div>
</div>

<div style="display:grid;gap:1rem;">
    @forelse($requests as $req)
    <div class="card card-body">
        <div style="display:flex;gap:1.5rem;align-items:flex-start;">
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:6px;">
                    <span style="font-weight:700;font-size:0.95rem;">{{ $req->student_ref_id }}</span>
                    <span class="pill pill-{{ match($req->category){'Employment'=>'indigo','Medical'=>'sky','Disability'=>'emerald','Family'=>'amber',default=>'slate'} }}">
                        {{ $req->category }}
                    </span>
                    <span class="{{ $req->status_badge }}" style="padding:2px 10px;border-radius:50px;font-size:0.68rem;font-weight:700;text-transform:uppercase;">
                        {{ $req->status }}
                    </span>
                </div>
                <div style="font-size:0.82rem;color:var(--text-secondary);margin-bottom:6px;">{{ $req->department }}</div>
                <div style="font-size:0.85rem;background:rgba(255,255,255,0.03);border-left:2px solid rgba(99,102,241,0.3);padding:0.75rem;border-radius:0 8px 8px 0;margin-bottom:8px;">
                    {{ $req->notes }}
                </div>
                @if($req->modified_schedule)
                <div style="font-size:0.78rem;color:#34d399;">📅 Requested: {{ $req->modified_schedule }}</div>
                @endif
                @if($req->admin_notes)
                <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">🗒️ Admin note: {{ $req->admin_notes }}</div>
                @endif
            </div>
            {{-- Review Form --}}
            <div style="flex-shrink:0;min-width:240px;">
                <form method="POST" action="{{ route('admin.accommodations.update', $req) }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" style="font-size:0.82rem;">
                            @foreach(['pending','under_review','approved','denied'] as $s)
                                <option value="{{ $s }}" {{ $req->status===$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="2" style="font-size:0.82rem;resize:none;" placeholder="Optional note…">{{ $req->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">Update</button>
                </form>
                @if($req->reviewed_at)
                <div style="font-size:0.72rem;color:var(--text-muted);text-align:center;margin-top:6px;">Reviewed {{ $req->reviewed_at->diffForHumans() }}</div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="card card-body" style="text-align:center;padding:3rem;">
        <div style="font-size:3rem;margin-bottom:1rem;">📝</div>
        <div style="color:var(--text-muted);">No accommodation requests submitted yet.</div>
    </div>
    @endforelse
</div>
</x-layouts.kukai>
