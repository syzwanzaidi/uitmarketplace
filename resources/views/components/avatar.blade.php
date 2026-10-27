@props(['user'])

@if ($user?->avatar_url)
    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" {{ $attributes->class('avatar') }} loading="lazy">
@else
    <span {{ $attributes->class('avatar') }} aria-hidden="true">{{ $user?->initials ?? '?' }}</span>
@endif
