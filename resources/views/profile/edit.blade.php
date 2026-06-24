<x-layouts.kukai title="Profile">
    <div class="section-header">
        <h2>Profile</h2>
        <p>Manage your account settings and preferences</p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 2rem; max-width: 800px;">
        <div class="card card-body">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card card-body">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-layouts.kukai>
