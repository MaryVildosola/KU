<section>
    <header class="card-header-bar">
        <div>
            <h2 class="card-title text-danger" style="color: var(--color-danger);">{{ __('Delete Account') }}</h2>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 4px;">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </p>
        </div>
    </header>

    <div style="margin-top: 1.5rem;">
        <button class="btn btn-danger"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >{{ __('Delete Account') }}</button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" style="padding: 1.5rem; background: var(--bg-secondary);">
            @csrf
            @method('delete')

            <h2 class="card-title" style="margin-bottom: 0.5rem; color: var(--text-primary);">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1.5rem;">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="form-group">
                <label for="password" class="form-label sr-only" style="display:none;">{{ __('Password') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control"
                    placeholder="{{ __('Password') }}"
                    style="width: 100%;"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" style="color: var(--color-danger); font-size: 0.8rem; margin-top: 4px;" />
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="btn btn-danger">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
