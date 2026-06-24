<x-layouts.kukai title="Create KU Window">
<div style="max-width:680px;">
    <div class="section-header">
        <h2>Create Synchronized Window</h2>
        <p>Configure a new institutional disconnection window for the semester schedule</p>
    </div>

    <div class="card card-body">
        <form method="POST" action="{{ route('admin.windows.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Window Label</label>
                <input type="text" name="label" class="form-control" placeholder="e.g. Evening Silence — Monday" value="{{ old('label') }}" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Day of Week</label>
                    <select name="day_of_week" class="form-control" required>
                        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                            <option value="{{ $day }}" {{ old('day_of_week')===$day?'selected':'' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Estimated Participants</label>
                    <input type="number" name="estimated_participants" class="form-control" value="{{ old('estimated_participants', 42000) }}">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time','20:00') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time','21:00') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description <span style="color:var(--text-muted)">(optional)</span></label>
                <textarea name="description" class="form-control" rows="3" placeholder="Describe the purpose or context of this window…">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.85rem;">
                    <input type="hidden" name="is_recurring" value="0">
                    <input type="checkbox" name="is_recurring" value="1" {{ old('is_recurring',1)?'checked':'' }} style="accent-color:var(--color-primary);width:16px;height:16px;">
                    <span style="font-weight:600;color:var(--text-secondary);">Recurring weekly window</span>
                </label>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                <a href="{{ route('admin.windows') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">🌿 Create Window</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.kukai>
