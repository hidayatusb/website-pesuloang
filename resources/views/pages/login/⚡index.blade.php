<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

new #[Layout('layouts::login')] class extends Component {
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            session()->regenerate();
            session()->flash('success', 'Login berhasil! Selamat datang.');

            return redirect()->intended(route('dashboard.index'));
        }

        session()->flash('error', 'Email atau password salah.');
    }
};
?>

<div>
    <form wire:submit.prevent="login" class="kt-card-content flex flex-col gap-5 p-10" id="sign_in_form">
        <div class="mb-2.5 text-center">
            <h3 class="mb-2.5 text-lg font-medium leading-none text-mono">
                Masuk Admin
            </h3>
            <p class="text-sm text-secondary-foreground">
                Panel Admin Website Desa Pesuloang
            </p>
        </div>

        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="kt-form-item">
            <label class="kt-form-label" for="email">Email</label>
            <div class="kt-input">
                <input id="email" type="email" class="kt-input" placeholder="admin@desasukamaju.go.id"
                    wire:model="email" autocomplete="email" />
            </div>
            @error('email')
                <div class="kt-form-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="kt-form-item">
            <label class="kt-form-label" for="password">Password</label>
            <div class="kt-input" data-kt-toggle-password="true">
                <input id="password" type="password" class="kt-input" placeholder="Masukkan password"
                    wire:model="password" autocomplete="current-password" />
                <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5"
                    data-kt-toggle-password-trigger="true" type="button">
                    <span class="kt-toggle-password-active:hidden">
                        <i class="ki-filled ki-eye text-muted-foreground"></i>
                    </span>
                    <span class="hidden kt-toggle-password-active:block">
                        <i class="ki-filled ki-eye-slash text-muted-foreground"></i>
                    </span>
                </button>
            </div>
            @error('password')
                <div class="kt-form-message">{{ $message }}</div>
            @enderror
        </div>

        <label class="kt-label">
            <input class="kt-checkbox kt-checkbox-sm" type="checkbox" wire:model="remember" />
            <span class="kt-checkbox-label">Ingat saya</span>
        </label>

        <button type="submit" class="kt-btn kt-btn-primary flex grow justify-center" wire:loading.attr="disabled">
            <span wire:loading.remove>Masuk</span>
            <span wire:loading>Memproses...</span>
        </button>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-sm text-secondary-foreground transition hover:text-foreground"
                wire:navigate>
                &larr; Kembali ke website
            </a>
        </div>
    </form>
</div>
