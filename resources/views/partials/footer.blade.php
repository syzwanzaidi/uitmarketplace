<footer class="site-footer">
    <div class="container top">
        <div class="row gy-4">
            <div class="col-lg-5">
                <a href="{{ route('home') }}" class="brand mb-3">
                    <span class="brand-mark"><i class="bi bi-bag-heart-fill"></i></span>
                    <span>UiT<em>Marketplace</em></span>
                </a>
                <p class="mb-0" style="max-width: 38ch">
                    A safe place for UiTM students to buy and sell pre-loved items on campus — chat, meet up, and pay securely.
                </p>
            </div>
            <div class="col-6 col-lg-2">
                <h4>Shop</h4>
                <ul>
                    <li><a href="{{ route('browse') }}">Browse items</a></li>
                    <li><a href="{{ route('cart') }}">Cart</a></li>
                    <li><a href="{{ route('buyer.orders') }}">My orders</a></li>
                    <li><a href="{{ route('chat.show') }}">Messages</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h4>Help</h4>
                <ul>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('contact') }}">Report a problem</a></li>
                    <li><a href="{{ route('account') }}">Account settings</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h4>Contact</h4>
                <ul>
                    <li class="d-flex gap-2"><i class="bi bi-geo-alt"></i><span>Faculty of Computer &amp; Mathematical Sciences, UiTM Shah Alam</span></li>
                    <li class="d-flex gap-2"><i class="bi bi-envelope"></i><a href="mailto:syzwandev@gmail.com">syzwandev@gmail.com</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span>&copy; {{ date('Y') }} UiTMarketplace</span>
            <span>Built by Syazwan Zaidi · Final Year Project</span>
        </div>
    </div>
</footer>
