@props(['order' => null, 'tone' => null, 'label' => null])

<span {{ $attributes->class(['status', 'status-' . ($tone ?? $order?->statusTone() ?? 'muted')]) }}>
    {{ $label ?? $order?->statusLabel() }}
</span>
