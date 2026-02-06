<?php $__env->startSection('content'); ?>
<main class="main">
  <section class="section" style="padding:24px 0;">
    <div class="container">
      <div class="row g-4">

        
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
              <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                  $partner = $conv->buyer_id === auth()->id()
                    ? $conv->seller
                    : $conv->buyer;
                ?>

                <a href="<?php echo e(route('chat', ['conversation_id' => $conv->id])); ?>"
                   id="conv-<?php echo e($conv->id); ?>"
                   class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3
                   <?php echo e(optional($conversation)->id === $conv->id ? 'active' : ''); ?>">

                  <div class="chat-avatar">
                    <img
                      src="<?php echo e($partner->profile_photo
                        ? asset('storage/'.$partner->profile_photo)
                        : asset('assets/img/person/person-f-1.webp')); ?>"
                      alt="Profile">
                  </div>

                  <div class="flex-grow-1 overflow-hidden">
                    <strong class="d-block text-truncate"><?php echo e($partner->name); ?></strong>
                    <div class="small text-muted text-truncate preview-text">
                      <?php echo e($conv->lastMessage()?->message ?? 'No messages yet'); ?>

                    </div>
                  </div>

                  <?php if($conv->unread_count > 0): ?>
                    <span class="badge bg-danger rounded-pill">
                      <?php echo e($conv->unread_count); ?>

                    </span>
                  <?php endif; ?>
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
          <div class="card chat-wrapper">

            <?php if($product): ?>
              <div class="chat-product-bar">
                <img src="<?php echo e($product->image); ?>" class="chat-product-image">
                <div class="chat-product-info">
                  <strong><?php echo e($product->name); ?></strong>
                  <div class="text-muted small">
                    RM <?php echo e(number_format($product->price, 2)); ?>

                  </div>
                </div>
                <a href="<?php echo e(route('product.show', $product->id)); ?>"
                   class="btn btn-sm btn-outline-primary">
                  View
                </a>
              </div>
            <?php endif; ?>

            <div class="card-header">
              <?php if($conversation): ?>
                <strong><?php echo e($otherUser->name); ?></strong>
              <?php else: ?>
                <strong>Select a conversation</strong>
              <?php endif; ?>
            </div>

            
            <div id="messagesArea" class="chat-messages">
              <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $isMe = $msg->sender_id === auth()->id(); ?>

                <div class="chat-line <?php echo e($isMe ? 'me' : 'other'); ?>">
                  <div class="chat-bubble-wrapper">
                    <div class="chat-bubble <?php echo e($isMe ? 'chat-bubble-me' : 'chat-bubble-other'); ?>">
                      <?php echo e($msg->message); ?>

                    </div>
                    <div class="chat-time <?php echo e($isMe ? 'text-end' : ''); ?>">
                      <?php echo e($msg->created_at->format('H:i')); ?>

                    </div>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($conversation): ?>
              <div class="card-footer">
                <div class="d-flex gap-2">
                  <input id="messageInput" class="form-control" placeholder="Type a message...">
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

    axios.post("<?php echo e(route('chat.send')); ?>", {
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/chat.blade.php ENDPATH**/ ?>