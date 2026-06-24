<section>
    <header class="card-header-bar">
        <div>
            <h2 class="card-title">{{ __('Update Password') }}</h2>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 4px;">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" style="margin-top: 1.5rem;">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" style="color: var(--color-danger); font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <div class="form-group">
            <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" style="color: var(--color-danger); font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" style="color: var(--color-danger); font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem;">
            <button class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    style="font-size: 0.85rem; color: var(--color-accent);"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
