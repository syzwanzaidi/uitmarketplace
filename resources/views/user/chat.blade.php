@extends('layouts.app')

@section('content')
<main class="main">
  <section class="section" style="padding: 24px 0;">
    <div class="container" data-aos="fade-up">
      <div class="row g-4">

        {{-- LEFT: Chats list --}}
        <div class="col-lg-4">
          <div class="card" style="height: 78vh; display:flex; flex-direction:column;">

            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0">Messages</h5>
                <small class="text-muted">Marketplace</small>
              </div>
            </div>

            <div class="p-3 border-bottom">
              <div class="input-group">
                <input id="chatSearch" type="search" class="form-control" placeholder="Search contacts..." />
                <button class="btn btn-outline-secondary" type="button">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>

            {{-- Conversation list --}}
            <div class="list-group list-group-flush overflow-auto" style="flex:1 1 auto;">
              @forelse($conversations as $conv)

                @php
                  $partner = $conv->buyer_id === auth()->id()
                    ? $conv->seller
                    : $conv->buyer;
                @endphp

                <a href="{{ route('chat', ['conversation_id' => $conv->id]) }}"
                   class="list-group-item list-group-item-action d-flex gap-3 py-3
                   {{ optional($conversation)->id === $conv->id ? 'active' : '' }}">

                  <img src="{{ asset('favicon.ico') }}" class="rounded-circle" width="48">

                  <div class="w-100">
                    <strong>{{ $partner->name }}</strong>
                    <div class="small text-muted text-truncate">
                      {{ $conv->lastMessage()?->message ?? 'No messages yet' }}
                    </div>
                  </div>

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
          <div class="card" style="height:78vh; display:flex; flex-direction:column;">

            {{-- Header --}}
            <div class="card-header d-flex align-items-center gap-3">
              @if($conversation)
                <img src="{{ $otherUser?->profile_photo
                    ? asset('storage/' . $otherUser->profile_photo)
                    : asset('favicon.ico') }}"
                     class="rounded-circle" width="48">

                <div class="flex-grow-1">
                  <strong>{{ $otherUser->name }}</strong>
                  <div class="small text-muted">online</div>
                </div>
              @else
                <strong>Chat</strong>
              @endif
            </div>

            {{-- Product preview --}}
            @if($product)
              <div class="p-3 border-bottom d-flex gap-3 align-items-center" style="background:#f8f9fa;">
                <img src="{{ $product->image }}" width="70" height="70" class="rounded" />
                <div class="flex-grow-1">
                  <strong>{{ $product->name }}</strong>
                  <div class="text-muted small">
                    RM {{ number_format($product->price,2) }}
                  </div>
                </div>
                <a href="{{ route('product.show', $product->id) }}"
                   class="btn btn-sm btn-outline-primary">
                  View
                </a>
              </div>
            @endif

            {{-- Messages --}}
            <div id="messagesArea" class="overflow-auto p-4"
                 style="flex:1 1 auto; background:rgba(0,0,0,0.03);">

              @if(!$conversation)
                <div class="text-center text-muted" style="padding:40px;">
                  Select a conversation to start chatting
                </div>

              @elseif($messages->isEmpty())
                <div class="text-center text-muted" style="padding:40px;">
                  No messages yet — start the conversation.
                </div>

              @else
                @foreach($messages as $msg)
                  @php $isMe = $msg->sender_id === auth()->id(); @endphp
                  <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : '' }}">
                    <div>
                      <div class="p-2 rounded"
                           style="background:{{ $isMe ? 'var(--accent-color); color:white;' : '#f1f3f5;' }};
                                  max-width:70%;">
                        {{ $msg->message }}
                      </div>
                      <div class="small text-muted mt-1 {{ $isMe ? 'text-end' : '' }}">
                        {{ $msg->created_at->format('H:i') }}
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif

            </div>

            {{-- Input --}}
            @if($conversation)
              <div class="card-footer py-3">
                <div class="d-flex gap-2 align-items-center">
                  <input id="messageInput" type="text"
                         class="form-control" placeholder="Type a message..." />
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

{{-- STEP 6: REAL-TIME --}}
<script>
  window.chatConfig = {
    conversationId: {{ $conversation->id ?? 'null' }},
    authUserId: {{ auth()->id() }},
  };

  const messagesArea = document.getElementById("messagesArea");
  const messageInput = document.getElementById("messageInput");

  if (document.getElementById("sendBtn")) {
    document.getElementById("sendBtn").addEventListener("click", sendMessage);
  }

  function sendMessage() {
    const text = messageInput.value.trim();
    if (!text) return;

    axios.post("{{ route('chat.send') }}", {
      conversation_id: window.chatConfig.conversationId,
      message: text
    });

    appendMyMessage(text);
    messageInput.value = '';
  }

  function appendMyMessage(text) {
    messagesArea.innerHTML += `
      <div class="d-flex mb-3 justify-content-end">
        <div>
          <div class="p-2 rounded"
               style="background:var(--accent-color); color:white; max-width:70%;">
            ${text}
          </div>
        </div>
      </div>
    `;
    messagesArea.scrollTop = messagesArea.scrollHeight;
  }

  function appendIncomingMessage(message) {
    messagesArea.innerHTML += `
      <div class="d-flex mb-3">
        <div>
          <div class="p-2 rounded" style="background:#f1f3f5; max-width:70%;">
            ${message.message}
          </div>
        </div>
      </div>
    `;
    messagesArea.scrollTop = messagesArea.scrollHeight;
  }

  function waitForEchoAndSubscribe() {
    if (!window.Echo || !window.chatConfig.conversationId) {
      setTimeout(waitForEchoAndSubscribe, 100);
      return;
    }

    window.Echo
      .private(`chat.${window.chatConfig.conversationId}`)
      .listen('.message.sent', (e) => {
        if (e.message.sender_id === window.chatConfig.authUserId) return;
        appendIncomingMessage(e.message);
      });

    console.log('Echo subscribed to chat channel');
  }

  waitForEchoAndSubscribe();

</script>
@endsection
