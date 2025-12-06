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
              <button id="newChatBtn" class="btn btn-sm btn-outline-primary">New</button>
            </div>

            {{-- Search --}}
            <div class="p-3 border-bottom">
              <div class="input-group">
                <input id="chatSearch" type="search" class="form-control" placeholder="Search contacts..." />
                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
              </div>
            </div>

            {{-- Contacts (still dummy for now — step 7 later) --}}
            <div class="list-group list-group-flush overflow-auto" id="contactsList" style="flex:1 1 auto;">
                <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3 contact-item active">
                  <img src="{{ asset('assets/niceshop/img/person/person-f-1.webp') }}" class="rounded-circle" width="48">
                  <div class="d-flex w-100 justify-content-between">
                    <div>
                      <strong>Sample Chat</strong>
                      <div class="small text-muted text-truncate">This will be dynamic later</div>
                    </div>
                  </div>
                </a>
            </div>

          </div>
        </div>

        {{-- RIGHT: Chat window --}}
        <div class="col-lg-8">
          <div class="card" style="height:78vh; display:flex; flex-direction:column;">

            {{-- HEADER: show real chat partner --}}
            @php $partner = $otherUser; @endphp

            <div class="card-header d-flex align-items-center gap-3">
              <img id="activeAvatar"
                   src="{{ $partner?->profile_photo ? asset('storage/' . $partner->profile_photo) : asset('favicon.ico') }}"
                   class="rounded-circle" width="48">

              <div class="flex-grow-1">
                <strong id="activeName">{{ $partner?->name ?? 'Chat' }}</strong>
                <div id="activeStatus" class="small text-muted">online</div>
              </div>
            </div>

            {{-- PRODUCT PREVIEW --}}
            @if($product)
              <div class="p-3 border-bottom d-flex gap-3 align-items-center" style="background:#f8f9fa;">
                  <img src="{{ $product->image }}" width="70" height="70" class="rounded" />
                  <div class="flex-grow-1">
                      <strong>{{ $product->name }}</strong>
                      <div class="text-muted small">RM {{ number_format($product->price,2) }}</div>
                  </div>
                  <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                      View
                  </a>
              </div>
            @endif

            {{-- MESSAGES AREA --}}
            <div id="messagesArea" class="overflow-auto p-4"
                 style="flex:1 1 auto; background:rgba(0,0,0,0.03);">

                @if($messages->isEmpty())
                    <div class="text-center text-muted" style="padding:40px;">
                        No messages yet — start the conversation.
                    </div>
                @else
                    @foreach($messages as $msg)
                        @php $isMe = $msg->sender_id === auth()->id(); @endphp

                        <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : '' }}">
                            <div>
                                <div class="p-2 rounded"
                                     style="background:{{ $isMe ? 'var(--accent-color); color:white;' : '#f1f3f5;' }}; max-width:70%;">
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

            {{-- INPUT BOX --}}
            <div class="card-footer py-3">
              <div class="d-flex gap-2 align-items-center">
                <input id="messageInput" type="text" class="form-control" placeholder="Type a message..." />
                <button id="sendBtn" class="btn btn-primary">Send</button>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
</main>

<script>
  document.getElementById("sendBtn").addEventListener("click", sendMessage);

  function sendMessage() {
      const text = messageInput.value.trim();
      if (!text) return;

      axios.post("{{ route('chat.send') }}", {
          conversation_id: "{{ $conversation->id ?? '' }}",
          message: text
      });

      // UI append (temporary)
      appendMyMessage(text);

      messageInput.value = '';
  }

  function appendMyMessage(text) {
      messagesArea.innerHTML += `
          <div class="d-flex mb-3 justify-content-end">
              <div>
                  <div class="p-2 rounded" style="background:var(--accent-color); color:white; max-width:70%;">
                      ${text}
                  </div>
              </div>
          </div>
      `;
      messagesArea.scrollTop = messagesArea.scrollHeight;
  }
</script>

@endsection



