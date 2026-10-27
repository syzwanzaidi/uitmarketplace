@props(['icon' => 'inbox', 'title'])

<div {{ $attributes->class('empty') }}>
    <div class="ico"><i class="bi bi-{{ $icon }}"></i></div>
    <h3>{{ $title }}</h3>
    @if ($slot->isNotEmpty())
        <p>{{ $slot }}</p>
    @endif
    {{ $actions ?? '' }}
</div>
