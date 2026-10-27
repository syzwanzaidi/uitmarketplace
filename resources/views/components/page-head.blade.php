@props(['title', 'subtitle' => null, 'crumbs' => []])

<div class="page-head">
    @if ($crumbs)
        <nav class="crumbs" aria-label="Breadcrumb">
            @foreach ($crumbs as $label => $url)
                @if ($url)
                    <a href="{{ $url }}">{{ $label }}</a>
                @else
                    <span aria-current="page">{{ $label }}</span>
                @endif
            @endforeach
        </nav>
    @endif
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div class="min-w-0">
            <h1>{{ $title }}</h1>
            @if ($subtitle)
                <p>{{ $subtitle }}</p>
            @endif
        </div>
        @if ($slot->isNotEmpty())
            <div class="d-flex flex-wrap gap-2">{{ $slot }}</div>
        @endif
    </div>
</div>
