<x-layouts.kukai title="Edit Window">
<div style="max-width:680px;">
    <div class="section-header">
        <h2>Edit Window</h2>
        <p>{{ $window->label }}</p>
    </div>
    <div class="card card-body">
        <form method="POST" action="{{ route('admin.windows.update', $window) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Window Label</label>
                <input type="text" name="label" class="form-control" value="{{ old('label', $window->label) }}" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Day of Week</label>
                    <select name="day_of_week" class="form-control" required>
                        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                            <option value="{{ $day }}" {{ $window->day_of_week===$day?'selected':'' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Estimated Participants</label>
                    <input type="number" name="estimated_participants" class="form-control" value="{{ $window->estimated_participants }}">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" value="{{ substr($window->start_time,0,5) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control" value="{{ substr($window->end_time,0,5) }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $window->description }}</textarea>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                <a href="{{ route('admin.windows') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.kukai>
