<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
};
?>

<div>
    <button wire:click="logout" type="button" class="kt-btn kt-btn-outline w-full justify-center">
        Keluar
    </button>
</div>
