<section>
    <header class="card-header-bar">
        <div>
            <h2 class="card-title">{{ __('Profile Information') }}</h2>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 4px;">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" style="margin-top: 1.5rem;">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" style="color: var(--color-danger); font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" style="color: var(--color-danger); font-size: 0.8rem; margin-top: 4px;" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top: 1rem;">
                    <p style="font-size: 0.85rem; color: var(--color-warning);">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-secondary btn-sm" style="margin-left: 0.5rem;">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--color-accent); font-weight: 500;">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem;">
            <button class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
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
