<x-layouts.kukai title="Research Datasets">
<div class="section-header" style="display:flex;justify-content:space-between;align-items:flex-start;">
    <div>
        <h2>Research Analytics Portal</h2>
        <p>Anonymized aggregate behavioral datasets · All data is de-identified at device level before transmission</p>
    </div>
    <a href="{{ route('research.apply') }}" class="btn btn-primary">📋 Apply for Expanded Access</a>
</div>

<div style="background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.15);border-radius:12px;padding:1rem 1.5rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:0.75rem;">
        <span style="font-size:1.25rem;">🔐</span>
        <div style="font-size:0.82rem;color:var(--text-secondary);">
            <strong style="color:var(--text-primary);">Privacy Notice:</strong>
            All datasets are fully anonymized and aggregated at institutional level. No individual user data is identifiable.
            Raw behavioral data never leaves user devices — only mathematical model gradients are transmitted.
            Access is governed by the Institutional Research Ethics Board.
        </div>
    </div>
</div>

{{-- PUBLIC DATASETS --}}
@if($publicDatasets->count() > 0)
<h3 style="font-family:'Outfit',sans-serif;font-size:0.9rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.75rem;">🌐 Public Datasets</h3>
<div style="display:grid;gap:0.75rem;margin-bottom:1.5rem;">
    @foreach($publicDatasets as $ds)
    <div class="card card-body" style="display:flex;gap:1.5rem;align-items:flex-start;">
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:4px;">
                <span style="font-weight:700;font-size:0.95rem;">{{ $ds->title }}</span>
                <span class="pill pill-emerald">PUBLIC</span>
            </div>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:6px;">{{ $ds->dataset_period }} &nbsp;·&nbsp; {{ number_format($ds->sample_size) }} participants</div>
            @if($ds->description)
            <p style="font-size:0.82rem;color:var(--text-secondary);">{{ $ds->description }}</p>
            @endif
        </div>
        <a href="{{ route('research.datasets.download', $ds) }}" class="btn btn-success btn-sm" style="flex-shrink:0;">⬇ CSV</a>
    </div>
    @endforeach
</div>
@endif

{{-- APPROVED-ONLY DATASETS --}}
<h3 style="font-family:'Outfit',sans-serif;font-size:0.9rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.75rem;">🔒 Approved Researcher Datasets</h3>
<div style="display:grid;gap:0.75rem;">
    @forelse($approvedDatasets as $ds)
    <div class="card card-body" style="display:flex;gap:1.5rem;align-items:flex-start;">
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:4px;">
                <span style="font-weight:700;font-size:0.95rem;">{{ $ds->title }}</span>
                <span class="pill pill-indigo">APPROVED ACCESS</span>
            </div>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:6px;">{{ $ds->dataset_period }} &nbsp;·&nbsp; {{ number_format($ds->sample_size) }} participants</div>
            @if($ds->description)
            <p style="font-size:0.82rem;color:var(--text-secondary);">{{ $ds->description }}</p>
            @endif
        </div>
        <a href="{{ route('research.datasets.download', $ds) }}" class="btn btn-primary btn-sm" style="flex-shrink:0;">⬇ Download CSV</a>
    </div>
    @empty
    <div class="card card-body" style="text-align:center;padding:2rem;color:var(--text-muted);">No approved datasets available yet.</div>
    @endforelse
</div>
</x-layouts.kukai>
