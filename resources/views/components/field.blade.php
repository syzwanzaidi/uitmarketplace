{{-- Labelled input with validation feedback. Pass any extra input attributes through. --}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'hint' => null, 'bag' => 'default'])

@php
    $id = $attributes->get('id', 'f_' . $name);
    $error = $errors->getBag($bag)->first($name);
@endphp

<div {{ $attributes->only('class')->class('mb-3') }}>
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" {{ $attributes->except(['class', 'id'])->class(['form-control', 'is-invalid' => $error]) }}>{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $type === 'password' ? '' : old($name, $value) }}"
               {{ $attributes->except(['class', 'id'])->class(['form-control', 'is-invalid' => $error]) }}>
    @endif
    @if ($error)
        <div class="invalid-feedback">{{ $error }}</div>
    @elseif ($hint)
        <div class="form-text">{{ $hint }}</div>
    @endif
</div>
