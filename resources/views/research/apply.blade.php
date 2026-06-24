<x-layouts.kukai title="Apply for Dataset Access">
<div style="max-width:680px;">
    <div class="section-header">
        <h2>Apply for Expanded Dataset Access</h2>
        <p>Request access to restricted behavioral datasets for peer-reviewed research purposes</p>
    </div>
    <div class="card card-body">
        <form method="POST" action="{{ route('research.apply.submit') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Research Title / Project Name</label>
                <input type="text" name="research_title" class="form-control" placeholder="e.g. Synchronized Rest and Cognitive Performance in University Students" required>
            </div>
            <div class="form-group">
                <label class="form-label">Institution / Department</label>
                <input type="text" name="institution" class="form-control" placeholder="e.g. University of the Philippines — Dept. of Psychology" required>
            </div>
            <div class="form-group">
                <label class="form-label">Research Purpose</label>
                <textarea name="research_purpose" class="form-control" rows="4" placeholder="Describe the research question and how KU data will support it…" required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Datasets Needed</label>
                <textarea name="datasets_needed" class="form-control" rows="3" placeholder="List the specific datasets or time periods you require access to…" required></textarea>
            </div>
            <div style="background:rgba(255,255,255,0.03);border-radius:10px;padding:1rem;margin-bottom:1.25rem;font-size:0.8rem;color:var(--text-muted);">
                📋 Applications are reviewed by the Institutional Research Ethics Board within 5 business days. All approved research must comply with KU's data anonymization policy. Published studies using KU data must cite the platform.
            </div>
            <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                <a href="{{ route('research.dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">📋 Submit Application</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.kukai>
