@props([
    'title',
    'text' => '',
    'url' => null,
])

@php
    $shareUrl = $url ?? url()->current();
    $shareMessage = trim($text) !== '' ? trim($text).' '.$shareUrl : $title.' '.$shareUrl;
    $whatsappUrl = 'https://wa.me/?text='.urlencode($shareMessage);
    $facebookUrl = 'https://www.facebook.com/sharer/sharer.php?u='.urlencode($shareUrl);
    $twitterUrl = 'https://twitter.com/intent/tweet?text='.urlencode($title).'&url='.urlencode($shareUrl);
@endphp

<div {{ $attributes->class(['rounded-xl border border-gray-100 bg-gray-50 p-4']) }}>
    <p class="mb-3 text-sm font-semibold text-gray-700">Bagikan</p>
    <div class="flex flex-wrap gap-2">
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-700">
            <i class="ki-filled ki-whatsapp text-sm"></i>
            WhatsApp
        </a>
        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center gap-2 rounded-lg bg-[#1877F2] px-3 py-2 text-xs font-semibold text-white transition hover:opacity-90">
            <i class="ki-filled ki-facebook text-sm"></i>
            Facebook
        </a>
        <a href="{{ $twitterUrl }}" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-800">
            <i class="ki-filled ki-twitter text-sm"></i>
            X
        </a>
        <button type="button"
            class="share-copy-btn inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-desa-200 hover:text-desa-600"
            data-share-url="{{ $shareUrl }}">
            <i class="ki-filled ki-copy text-sm"></i>
            <span class="share-copy-label">Salin Link</span>
        </button>
        <button type="button"
            class="share-native-btn hidden items-center gap-2 rounded-lg border border-desa-200 bg-desa-50 px-3 py-2 text-xs font-semibold text-desa-700 transition hover:bg-desa-100"
            data-share-title="{{ $title }}"
            data-share-text="{{ $text ?: $title }}"
            data-share-url="{{ $shareUrl }}">
            <i class="ki-filled ki-share text-sm"></i>
            Bagikan
        </button>
    </div>
</div>

@once
    @push('scripts')
        <script>
            (function () {
                const initShareButtons = () => {
                    if (navigator.share) {
                        document.querySelectorAll('.share-native-btn').forEach((button) => {
                            button.classList.remove('hidden');
                            button.classList.add('inline-flex');
                        });
                    }

                    document.querySelectorAll('.share-copy-btn').forEach((button) => {
                        if (button.dataset.bound === 'true') {
                            return;
                        }

                        button.dataset.bound = 'true';
                        button.addEventListener('click', async () => {
                            const url = button.dataset.shareUrl;
                            const label = button.querySelector('.share-copy-label');

                            try {
                                await navigator.clipboard.writeText(url);
                                if (label) {
                                    const original = label.textContent;
                                    label.textContent = 'Tersalin!';
                                    setTimeout(() => {
                                        label.textContent = original;
                                    }, 2000);
                                }
                            } catch (error) {
                                window.prompt('Salin link berikut:', url);
                            }
                        });
                    });

                    document.querySelectorAll('.share-native-btn').forEach((button) => {
                        if (button.dataset.bound === 'true') {
                            return;
                        }

                        button.dataset.bound = 'true';
                        button.addEventListener('click', async () => {
                            try {
                                await navigator.share({
                                    title: button.dataset.shareTitle,
                                    text: button.dataset.shareText,
                                    url: button.dataset.shareUrl,
                                });
                            } catch (error) {
                                if (error?.name !== 'AbortError') {
                                    console.error(error);
                                }
                            }
                        });
                    });
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initShareButtons);
                } else {
                    initShareButtons();
                }
            })();
        </script>
    @endpush
@endonce
