@extends('layouts.app')

@section('content')
<main class="main">
  <section class="section" style="padding:24px 0;">
    <div class="container">
      <div class="row g-4">

        {{-- LEFT: Conversation list --}}
        <div class="col-lg-4">
          <div class="card chat-sidebar">

            <div class="card-header">
              <h5 class="mb-0">Messages</h5>
              <small class="text-muted">Marketplace</small>
            </div>

            <div class="p-3 border-bottom">
              <input id="chatSearch" class="form-control" placeholder="Search contacts...">
            </div>

            <div class="list-group list-group-flush chat-list">
              @forelse($conversations as $conv)
                @php
                  $partner = $conv->buyer_id === auth()->id()
                    ? $conv->seller
                    : $conv->buyer;
                @endphp

                <a href="{{ route('chat', ['conversation_id' => $conv->id]) }}"
                   id="conv-{{ $conv->id }}"
                   class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3
                   {{ optional($conversation)->id === $conv->id ? 'active' : '' }}">

                  <div class="chat-avatar">
                    <img
                      src="{{ $partner->profile_photo
                        ? asset('storage/'.$partner->profile_photo)
                        : asset('assets/img/person/person-f-1.webp') }}"
                      alt="Profile">
                  </div>

                  <div class="flex-grow-1 overflow-hidden">
                    <strong class="d-block text-truncate">{{ $partner->name }}</strong>
                    <div class="small text-muted text-truncate preview-text">
                      {{ $conv->lastMessage()?->message ?? 'No messages yet' }}
                    </div>
                  </div>

                  @if($conv->unread_count > 0)
                    <span class="badge bg-danger rounded-pill">
                      {{ $conv->unread_count }}
                    </span>
                  @endif
                </a>
              @empty
                <div class="text-center text-muted p-4">
                  No conversations yet
                </div>
              @endforelse
            </div>

          </div>
        </div>

        {{-- RIGHT: Chat window --}}
        <div class="col-lg-8">
          <div class="card chat-wrapper">

            @if($product)
              <div class="chat-product-bar">
                <img src="{{ $product->image }}" class="chat-product-image">
                <div class="chat-product-info">
                  <strong>{{ $product->name }}</strong>
                  <div class="text-muted small">
                    RM {{ number_format($product->price, 2) }}
                  </div>
                </div>
                <a href="{{ route('product.show', $product->id) }}"
                   class="btn btn-sm btn-outline-primary">
                  View
                </a>
              </div>
            @endif

            <div class="card-header">
              @if($conversation)
                <strong>{{ $otherUser->name }}</strong>
              @else
                <strong>Select a conversation</strong>
              @endif
            </div>

            {{-- MESSAGES --}}
            <div id="messagesArea" class="chat-messages">
              @foreach($messages as $msg)
                @php $isMe = $msg->sender_id === auth()->id(); @endphp

                <div class="chat-line {{ $isMe ? 'me' : 'other' }}">
                  <div class="chat-bubble-wrapper">
                    <div class="chat-bubble {{ $isMe ? 'chat-bubble-me' : 'chat-bubble-other' }}">
                      {{ $msg->message }}
                    </div>
                    <div class="chat-time {{ $isMe ? 'text-end' : '' }}">
                      {{ $msg->created_at->format('H:i') }}
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            @if($conversation)
              <div class="card-footer">
                <div class="d-flex gap-2">
                  <input id="messageInput" class="form-control" placeholder="Type a message...">
                  <button id="sendBtn" class="btn btn-primary">Send</button>
                </div>
              </div>
            @endif

          </div>
        </div>

      </div>
    </div>
  </section>
</main>

{{-- REAL-TIME SCRIPT (UNCHANGED LOGIC) --}}
<script>
  window.chatConfig = {
    conversationId: {{ $conversation->id ?? 'null' }},
    authUserId: {{ auth()->id() }},
  };

  const messagesArea = document.getElementById('messagesArea');
  const messageInput = document.getElementById('messageInput');
  const sendBtn = document.getElementById('sendBtn');
  const chatSearch = document.getElementById('chatSearch');

  function scrollToBottom() {
    messagesArea.scrollTop = messagesArea.scrollHeight;
  }
  scrollToBottom();

  if (sendBtn) sendBtn.addEventListener('click', sendMessage);

  function sendMessage() {
    const text = messageInput.value.trim();
    if (!text) return;

    axios.post("{{ route('chat.send') }}", {
      conversation_id: window.chatConfig.conversationId,
      message: text
    }).then(res => {
      appendMyMessage(res.data.message);
      messageInput.value = '';
    });
  }

  function appendMyMessage(msg) {
    messagesArea.insertAdjacentHTML('beforeend', `
      <div class="chat-line me">
        <div class="chat-bubble-wrapper">
          <div class="chat-bubble chat-bubble-me">${msg.message}</div>
          <div class="chat-time text-end">${formatTime(msg.created_at)}</div>
        </div>
      </div>
    `);
    scrollToBottom();
  }

  function appendIncomingMessage(msg) {
    messagesArea.insertAdjacentHTML('beforeend', `
      <div class="chat-line other">
        <div class="chat-bubble-wrapper">
          <div class="chat-bubble chat-bubble-other">${msg.message}</div>
          <div class="chat-time">${formatTime(msg.created_at)}</div>
        </div>
      </div>
    `);
    scrollToBottom();
  }

  function formatTime(datetime) {
    const d = new Date(datetime);
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }

  function waitForEcho() {
    if (!window.Echo || !window.chatConfig.conversationId) {
      setTimeout(waitForEcho, 100);
      return;
    }

    window.Echo.private(`chat.${window.chatConfig.conversationId}`)
      .listen('.message.sent', (e) => {
        if (e.message.sender_id === window.chatConfig.authUserId) return;
        appendIncomingMessage(e.message);
      });
  }

  waitForEcho();
</script>
@endsection
