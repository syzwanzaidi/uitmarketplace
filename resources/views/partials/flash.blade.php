@php
    $messages = collect([
        'success' => session('success'),
        'error' => session('error'),
    ])->filter();
@endphp

<div class="toast-stack" aria-live="polite">
    @foreach ($messages as $type => $message)
        <div class="toast is-{{ $type }}" role="{{ $type === 'error' ? 'alert' : 'status' }}">
            <div class="toast-body">
                <i class="bi {{ $type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }}"></i>
                <div class="flex-grow-1">{{ $message }}</div>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endforeach
</div>
