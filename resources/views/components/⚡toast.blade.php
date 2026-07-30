<?php

use Livewire\Component;

new class extends Component {
    //
};
?>
<div>
    <script>
        window.showKtToast = function(message, type = 'success') {
            if (typeof KTToast === 'undefined') return false;
            KTToast.show({ message, type });
            return true;
        };

        window.flushPendingSessionToast = function() {
            const stored = sessionStorage.getItem('ktPendingToast');
            if (stored) {
                try {
                    const data = JSON.parse(stored);
                    if (window.showKtToast(data.message, data.type || 'success')) {
                        sessionStorage.removeItem('ktPendingToast');
                        return;
                    }
                } catch (e) {}
            }

            const pending = window.__ktPendingSessionToast;
            if (!pending) return;
            if (window.showKtToast(pending.message, pending.type)) {
                window.__ktPendingSessionToast = null;
            }
        };

        if (!window.__ktToastListenersBound) {
            window.__ktToastListenersBound = true;
            document.addEventListener('DOMContentLoaded', window.flushPendingSessionToast);
            document.addEventListener('livewire:navigated', window.flushPendingSessionToast);

            document.addEventListener('livewire:init', () => {
                Livewire.on('show-toast', (params) => {
                    const data = Array.isArray(params) ? params[0] : params;
                    window.showKtToast(data?.message, data?.type || 'success');
                });
            });
        }

        @if (session()->has('success') || session()->has('error'))
            window.__ktPendingSessionToast = {
                message: @js(session('success') ?? session('error')),
                type: @js(session()->has('success') ? 'success' : 'danger'),
            };
        @endif
    </script>
</div>
