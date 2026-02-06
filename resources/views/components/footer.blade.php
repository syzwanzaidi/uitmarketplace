  <footer id="footer" class="footer dark-background">
    <div class="footer-main">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6">
            <div class="footer-widget footer-about">
              <a href="index.html" class="logo">
                <span class="sitename">UiTMarketplace</span>
              </a>
              <p>This web application is developed to provide UiTM students with a secure platform for buying and selling items within the campus environment.</p>

            </div>
          </div>

          <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="footer-widget">
              <h4>Shop</h4>
              <ul class="footer-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('category') }}">Browse</a></li>
                <li><a href="{{ route('cart') }}">Cart</a></li>
                <li><a href="{{ route('chat') }}">Chat</a></li>
                <li><a href="{{ route('buyer.orders') }}">My Orders</a></li>
              </ul>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="footer-widget">
              <h4>Support</h4>
              <ul class="footer-links">
                <li><a href="{{ route('contact') }}">Contact Us</a></li>
              </ul>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="footer-widget">
              <h4>Contact Information</h4>
              <div class="footer-contact">
                <div class="contact-item">
                  <i class="bi bi-geo-alt"></i>
                  <span>Faculty of Computer Science and <br> Mathemathics, UiTM Shah Alam</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-telephone"></i>
                  <span>011 1101 0214</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-envelope"></i>
                  <span>syzwandev@gmail.com</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-clock"></i>
                  <span>Always Available</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">
        <div class="row gy-3 align-items-center">
          <div class="col-lg-6 col-md-12">
            <div class="copyright">
              <p>© <span>Copyright</span> <strong class="sitename">UiTMarketplace</strong>. All Rights Reserved.</p>
            </div>
            <div class="credits mt-1">
              <p>Designed by Syazwan Zaidi</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </footer>