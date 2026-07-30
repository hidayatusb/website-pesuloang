@props([
    'model',
    'value' => '',
    'label' => null,
    'placeholder' => '',
    'minHeight' => '220px',
])

<div {{ $attributes->class(['kt-form-item']) }}>
    @if ($label)
        <label class="kt-form-label">{{ $label }}</label>
    @endif

    <div
        wire:ignore
        wire:key="rich-editor-{{ $model }}"
        class="rich-editor-container"
        style="--rich-editor-min-height: {{ $minHeight }}"
    >
        <textarea
            data-rich-editor
            data-model="{{ $model }}"
            placeholder="{{ $placeholder }}"
            class="kt-textarea w-full"
        >{{ $value }}</textarea>
    </div>
</div>
