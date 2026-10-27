@extends('layouts.app')

@section('title', 'Messages')

@section('content')
@php($me = auth()->user())

<div class="container py-4">
    <div class="surface chat-shell {{ $conversation ? 'has-active' : '' }}">

        <aside class="chat-inbox">
            <div class="head">
                <h1 class="h5 mb-0">Messages</h1>
            </div>
            <div class="chat-list">
                @forelse ($conversations as $conv)
                    @php($other = $conv->partnerOf($me))
                    <a href="{{ route('chat.show', $conv) }}" class="chat-item {{ $conversation?->is($conv) ? 'active' : '' }}">
                        <x-avatar :user="$other" />
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between gap-2">
                                <span class="who text-truncate">{{ $other?->name ?? 'Deleted user' }}</span>
                                <span class="when">{{ $conv->latestMessage?->created_at->shortAbsoluteDiffForHumans() }}</span>
                            </div>
                            <div class="d-flex justify-content-between gap-2 align-items-center">
                                <span class="preview">
                                    @if ($conv->latestMessage)
                                        {{ $conv->latestMessage->sender_id === $me->id ? 'You: ' : '' }}{{ $conv->latestMessage->message }}
                                    @else
                                        {{ $conv->product?->name ?? 'New conversation' }}
                                    @endif
                                </span>
                                @if ($conv->unread_count && ! $conversation?->is($conv))
                                    <span class="unread">{{ $conv->unread_count }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <x-empty-state icon="chat-dots" title="No conversations yet">
                        Tap “Chat with seller” on any listing to ask a question.
                    </x-empty-state>
                @endforelse
            </div>
        </aside>

        <section class="chat-thread">
            @if ($conversation)
                <div id="chatThread" class="d-flex flex-column h-100 min-w-0"
                     data-conversation="{{ $conversation->id }}"
                     data-me="{{ $me->id }}"
                     data-send-url="{{ route('chat.send', $conversation) }}"
                     data-poll-url="{{ route('chat.poll', $conversation) }}">

                    <div class="head">
                        <a href="{{ route('chat.show') }}" class="btn btn-ghost btn-icon btn-sm d-lg-none" aria-label="Back to inbox"><i class="bi bi-arrow-left"></i></a>
                        <x-avatar :user="$partner" />
                        <div class="min-w-0">
                            <div class="fw-bold text-truncate">{{ $partner?->name }}</div>
                            <div class="small text-muted">{{ $conversation->seller_id === $me->id ? 'Interested buyer' : 'Seller' }}</div>
                        </div>
                    </div>

                    @if ($conversation->product)
                        <div class="chat-product">
                            <img src="{{ $conversation->product->image_url }}" alt="" class="thumb-xs">
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold text-truncate">{{ $conversation->product->name }}</div>
                                <div class="text-muted">RM {{ number_format($conversation->product->price, 2) }}
                                    @if (! $conversation->product->isAvailable()) · <span class="text-danger">No longer available</span>@endif
                                </div>
                            </div>
                            <a href="{{ route('product.show', $conversation->product) }}" class="btn btn-light btn-sm">View</a>
                        </div>
                    @endif

                    <div class="chat-messages" data-messages>
                        @php($lastDay = null)
                        @forelse ($messages as $msg)
                            @php($day = $msg->created_at->isToday() ? 'Today' : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('d M Y')))
                            @if ($day !== $lastDay)
                                <div class="day-sep">{{ $day }}</div>
                                @php($lastDay = $day)
                            @endif
                            <div class="bubble-row {{ $msg->sender_id === $me->id ? 'me' : 'them' }}" data-id="{{ $msg->id }}">
                                <div class="bubble">{{ $msg->message }}<time datetime="{{ $msg->created_at->toIso8601String() }}">{{ $msg->created_at->format('g:i A') }}</time></div>
                            </div>
                        @empty
                            <div class="text-center text-muted small my-auto" data-empty>
                                <i class="bi bi-emoji-smile fs-3 d-block mb-2"></i>
                                Say hi and ask about the item, or suggest a meetup spot.
                            </div>
                        @endforelse
                    </div>

                    <form class="chat-compose" data-compose>
                        <textarea class="form-control" rows="1" maxlength="2000" placeholder="Write a message…" aria-label="Message"></textarea>
                        <button type="submit" class="btn btn-primary btn-icon flex-shrink-0" aria-label="Send"><i class="bi bi-send-fill"></i></button>
                    </form>
                </div>
            @else
                <div class="h-100 d-grid" style="place-items:center">
                    <x-empty-state icon="chat-square-text" title="Select a conversation">
                        Choose a chat on the left to continue talking.
                    </x-empty-state>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
