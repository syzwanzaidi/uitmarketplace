


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
              <button id="newChatBtn" class="btn btn-sm btn-outline-primary">New</button>
            </div>

            
            <div class="p-3 border-bottom">
              <div class="input-group">
                <input id="chatSearch" type="search" class="form-control" placeholder="Search contacts..." />
                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
              </div>
            </div>

            
            <div class="list-group list-group-flush overflow-auto" id="contactsList" style="flex:1 1 auto;">
              
              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3 contact-item active"
                 data-id="1" data-name="Aisyah" data-last="Hey, is this still available?" data-avatar="<?php echo e(asset('assets/niceshop/img/person/person-f-1.webp')); ?>">
                <img src="<?php echo e(asset('assets/niceshop/img/person/person-f-1.webp')); ?>" alt="avatar" class="rounded-circle" width="48" height="48">
                <div class="d-flex w-100 justify-content-between">
                  <div>
                    <strong>Aisyah</strong>
                    <div class="small text-muted text-truncate">Hey, is this still available?</div>
                  </div>
                  <div class="text-end">
                    <div class="small text-muted">2h</div>
                    <span class="badge bg-primary rounded-pill">1</span>
                  </div>
                </div>
              </a>

              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3 contact-item"
                 data-id="2" data-name="Farhan" data-last="Payment sent" data-avatar="<?php echo e(asset('assets/niceshop/img/person/person-m-1.webp')); ?>">
                <img src="<?php echo e(asset('assets/niceshop/img/person/person-m-1.webp')); ?>" alt="avatar" class="rounded-circle" width="48" height="48">
                <div class="d-flex w-100 justify-content-between">
                  <div>
                    <strong>Farhan</strong>
                    <div class="small text-muted text-truncate">Payment sent</div>
                  </div>
                  <div class="text-end">
                    <div class="small text-muted">1d</div>
                  </div>
                </div>
              </a>

              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3 contact-item"
                 data-id="3" data-name="Lim" data-last="Can you ship COD?" data-avatar="<?php echo e(asset('assets/niceshop/img/person/person-f-2.webp')); ?>">
                <img src="<?php echo e(asset('assets/niceshop/img/person/person-f-2.webp')); ?>" alt="avatar" class="rounded-circle" width="48" height="48">
                <div class="d-flex w-100 justify-content-between">
                  <div>
                    <strong>Lim</strong>
                    <div class="small text-muted text-truncate">Can you ship COD?</div>
                  </div>
                  <div class="text-end">
                    <div class="small text-muted">3d</div>
                  </div>
                </div>
              </a>

            </div>
          </div>
        </div>

        
        <div class="col-lg-8">
          <div class="card" style="height:78vh; display:flex; flex-direction:column;">
            
            <div class="card-header d-flex align-items-center gap-3">
              <img id="activeAvatar" src="<?php echo e(asset('assets/niceshop/img/person/person-f-1.webp')); ?>" alt="avatar" class="rounded-circle" width="48" height="48">
              <div class="flex-grow-1">
                <strong id="activeName">Aisyah</strong>
                <div id="activeStatus" class="small text-muted">last seen 2 hours ago</div>
              </div>
              <div>
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-telephone"></i></button>
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-info-circle"></i></button>
              </div>
            </div>

            
            <div id="messagesArea" class="overflow-auto p-4" style="flex:1 1 auto; background: color-mix(in srgb, var(--background-color), transparent 92%);">
              
              <div class="d-flex mb-3">
                <div class="me-2">
                  <div class="small text-muted">10:12</div>
                </div>
                <div>
                  <div class="p-2 rounded" style="background:#f1f3f5; max-width:70%;">Hi — is the blue jacket still available?</div>
                </div>
              </div>

              <div class="d-flex mb-3 justify-content-end">
                <div>
                  <div class="p-2 rounded" style="background:var(--accent-color); color:var(--contrast-color); max-width:70%;">Yes — it's still available. Would you like to see more pictures?</div>
                  <div class="small text-muted mt-1 text-end">10:14</div>
                </div>
              </div>
            </div>

            
            <div class="card-footer py-3">
              <div class="d-flex gap-2 align-items-center">
                <button id="attachBtn" class="btn btn-light"><i class="bi bi-paperclip"></i></button>
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


<?php $__env->startPush('scripts'); ?>
<script>
  // simple client-side logic — no backend
  (function(){
    const contacts = document.querySelectorAll('.contact-item');
    const messagesArea = document.getElementById('messagesArea');
    const activeName = document.getElementById('activeName');
    const activeAvatar = document.getElementById('activeAvatar');
    const activeStatus = document.getElementById('activeStatus');
    const sendBtn = document.getElementById('sendBtn');
    const messageInput = document.getElementById('messageInput');
    const chatSearch = document.getElementById('chatSearch');

    // switch conversation
    contacts.forEach(c => {
      c.addEventListener('click', function(e){
        e.preventDefault();
        contacts.forEach(x => x.classList.remove('active'));
        this.classList.add('active');

        const name = this.dataset.name;
        const avatar = this.dataset.avatar;
        const last = this.dataset.last;

        activeName.textContent = name;
        activeAvatar.src = avatar;
        activeStatus.textContent = last;

        // clear messages area and show placeholder (simulate loader)
        messagesArea.innerHTML = '';
        const placeholder = document.createElement('div');
        placeholder.className = 'text-center text-muted';
        placeholder.style.padding = '48px';
        placeholder.innerText = 'No messages yet — start the conversation';
        messagesArea.appendChild(placeholder);
      });
    });

    // send message (client-only)
    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keydown', function(e){
      if (e.key === 'Enter') sendMessage();
    });

    function sendMessage(){
      const text = messageInput.value.trim();
      if (!text) return;
      // append message to messagesArea (right side)
      const wrapper = document.createElement('div');
      wrapper.className = 'd-flex mb-3 justify-content-end';
      wrapper.innerHTML = `
        <div>
          <div class="p-2 rounded" style="background:var(--accent-color); color:var(--contrast-color); max-width:70%;">${escapeHtml(text)}</div>
          <div class="small text-muted mt-1 text-end">${timeNow()}</div>
        </div>
      `;
      // remove "no messages" placeholder if present
      const placeholder = messagesArea.querySelector('.text-center.text-muted');
      if (placeholder) messagesArea.innerHTML = '';
      messagesArea.appendChild(wrapper);
      messagesArea.scrollTop = messagesArea.scrollHeight;
      messageInput.value = '';
      messageInput.focus();
    }

    function timeNow(){
      const d = new Date();
      return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
    }

    function escapeHtml(unsafe) {
      return unsafe
           .replace(/&/g, "&amp;")
           .replace(/</g, "&lt;")
           .replace(/>/g, "&gt;")
           .replace(/"/g, "&quot;")
           .replace(/'/g, "&#039;");
    }

    // search filter
    chatSearch.addEventListener('input', function(){
      const q = this.value.toLowerCase();
      contacts.forEach(c => {
        const name = c.dataset.name.toLowerCase();
        const last = (c.dataset.last||'').toLowerCase();
        if (name.includes(q) || last.includes(q)) {
          c.style.display = '';
        } else {
          c.style.display = 'none';
        }
      });
    });

  })();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/chat.blade.php ENDPATH**/ ?>