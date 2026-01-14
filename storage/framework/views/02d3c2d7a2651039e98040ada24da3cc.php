<?php $__env->startSection('content'); ?>
<main class="main">
  <section class="section" style="padding: 24px 0;">
    <div class="container" data-aos="fade-up">
      <div class="row g-4">

        
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

            
            <div class="list-group list-group-flush overflow-auto" style="flex:1 1 auto;">
              <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <?php
                  $partner = $conv->buyer_id === auth()->id()
                    ? $conv->seller
                    : $conv->buyer;
                ?>

                <a href="<?php echo e(route('chat', ['conversation_id' => $conv->id])); ?>"
                   class="list-group-item list-group-item-action d-flex gap-3 py-3
                   <?php echo e(optional($conversation)->id === $conv->id ? 'active' : ''); ?>">

                  <img src="<?php echo e(asset('favicon.ico')); ?>" class="rounded-circle" width="48">

                  <div class="w-100">
                    <strong><?php echo e($partner->name); ?></strong>
                    <div class="small text-muted text-truncate">
                      <?php echo e($conv->lastMessage()?->message ?? 'No messages yet'); ?>

                    </div>
                  </div>

                </a>

              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center text-muted p-4">
                  No conversations yet
                </div>
              <?php endif; ?>
            </div>

          </div>
        </div>

        
        <div class="col-lg-8">
          <div class="card" style="height:78vh; display:flex; flex-direction:column;">

            
            <div class="card-header d-flex align-items-center gap-3">
              <?php if($conversation): ?>
                <img src="<?php echo e($otherUser?->profile_photo
                    ? asset('storage/' . $otherUser->profile_photo)
                    : asset('favicon.ico')); ?>"
                     class="rounded-circle" width="48">

                <div class="flex-grow-1">
                  <strong><?php echo e($otherUser->name); ?></strong>
                  <div class="small text-muted">online</div>
                </div>
              <?php else: ?>
                <strong>Chat</strong>
              <?php endif; ?>
            </div>

            
            <?php if($product): ?>
              <div class="p-3 border-bottom d-flex gap-3 align-items-center" style="background:#f8f9fa;">
                <img src="<?php echo e($product->image); ?>" width="70" height="70" class="rounded" />
                <div class="flex-grow-1">
                  <strong><?php echo e($product->name); ?></strong>
                  <div class="text-muted small">
                    RM <?php echo e(number_format($product->price,2)); ?>

                  </div>
                </div>
                <a href="<?php echo e(route('product.show', $product->id)); ?>"
                   class="btn btn-sm btn-outline-primary">
                  View
                </a>
              </div>
            <?php endif; ?>

            
            <div id="messagesArea" class="overflow-auto p-4"
                 style="flex:1 1 auto; background:rgba(0,0,0,0.03);">

              <?php if(!$conversation): ?>
                <div class="text-center text-muted" style="padding:40px;">
                  Select a conversation to start chatting
                </div>

              <?php elseif($messages->isEmpty()): ?>
                <div class="text-center text-muted" style="padding:40px;">
                  No messages yet — start the conversation.
                </div>

              <?php else: ?>
                <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php $isMe = $msg->sender_id === auth()->id(); ?>
                  <div class="d-flex mb-3 <?php echo e($isMe ? 'justify-content-end' : ''); ?>">
                    <div>
                      <div class="p-2 rounded"
                           style="background:<?php echo e($isMe ? 'var(--accent-color); color:white;' : '#f1f3f5;'); ?>;
                                  max-width:70%;">
                        <?php echo e($msg->message); ?>

                      </div>
                      <div class="small text-muted mt-1 <?php echo e($isMe ? 'text-end' : ''); ?>">
                        <?php echo e($msg->created_at->format('H:i')); ?>

                      </div>
                    </div>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php endif; ?>

            </div>

            
            <?php if($conversation): ?>
              <div class="card-footer py-3">
                <div class="d-flex gap-2 align-items-center">
                  <input id="messageInput" type="text"
                         class="form-control" placeholder="Type a message..." />
                  <button id="sendBtn" class="btn btn-primary">Send</button>
                </div>
              </div>
            <?php endif; ?>

          </div>
        </div>

      </div>
    </div>
  </section>
</main>


<script>
  window.chatConfig = {
    conversationId: <?php echo e($conversation->id ?? 'null'); ?>,
    authUserId: <?php echo e(auth()->id()); ?>,
  };

  const messagesArea = document.getElementById("messagesArea");
  const messageInput = document.getElementById("messageInput");

  if (document.getElementById("sendBtn")) {
    document.getElementById("sendBtn").addEventListener("click", sendMessage);
  }

  function sendMessage() {
    const text = messageInput.value.trim();
    if (!text) return;

    axios.post("<?php echo e(route('chat.send')); ?>", {
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

  if (window.chatConfig.conversationId) {
    window.Echo
      .private(`chat.${window.chatConfig.conversationId}`)
      .listen('.message.sent', (e) => {
        if (e.message.sender_id === window.chatConfig.authUserId) return;
        appendIncomingMessage(e.message);
      });
  }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/chat.blade.php ENDPATH**/ ?>