# UiTMarketplace

A location-based online marketplace for the UiTM Shah Alam community. Students list items for sale, buyers discover what is near them, chat with the seller in real time, and pay online. Built as a final year project.

## Features

**Buying**
- Browse by category, search by keyword, and sort by price, newest or nearest
- Location filter: pick a point on a map and a radius, and see only items within that distance of you (Haversine distance, calculated in SQL)
- Product pages, wishlist, cart grouped by seller, and "Buy now" checkout
- Online payment through the ToyyibPay gateway; items from several sellers are paid with a single bill
- Order tracking (to pay, to ship, to receive, completed), courier and tracking number from the seller, and a "mark as received" confirmation
- Retry or cancel unpaid orders

**Selling** (UiTM email accounts only)
- Seller centre to create, edit, hide/show and delete listings with photos
- Drop a pin on a Leaflet map when listing an item so buyers can see how far away it is
- Seller order view split into to ship, shipped and completed, with courier and tracking updates

**Chat**
- One conversation per product between buyer and seller
- Real-time delivery over Pusher using Laravel Echo, with read receipts and unread counts
- Falls back to polling automatically when Pusher is not configured

**Accounts and admin**
- Registration, login, email verification and password reset (Laravel Breeze)
- `@uitm.edu.my` and `@student.uitm.edu.my` addresses become UiTM members who can sell; other accounts can buy
- Suspended accounts are blocked at login and by an `active` middleware
- Admin area to suspend or reactivate users, review all transactions, and resolve complaints submitted through the help page

## Tech stack

| Layer | Tools |
|---|---|
| Backend | PHP 8.2+, Laravel 12, Laravel Breeze |
| Frontend | Blade, Bootstrap 5, Bootstrap Icons, Vite |
| Database | MySQL in production (SQLite by default for local development and tests) |
| Maps | Leaflet with OpenStreetMap (buyer location and seller location picker) |
| Real-time | Pusher, Laravel Echo |
| Payments | ToyyibPay |
| Deployment | DigitalOcean droplet with Nginx |

## How the main flows work

**Distance filtering.** Each product has a row in `locations` (latitude and longitude). The buyer's chosen point and radius are stored in the session, and listing queries compute the Haversine distance in SQL, filter by the radius and order by nearest first.

**Payment.** Checkout creates one order per seller and a single ToyyibPay bill covering all of them, with the delivery details saved on each order. When ToyyibPay calls `POST /toyyibpay/callback` (a public route), or when the buyer is redirected back to `/payment/return`, the app asks ToyyibPay's `getBillTransactions` API for the real status instead of trusting the request. Settlement is idempotent and locked per bill: it checks that the paid amount matches the order totals, marks the orders as paid, records the payments, sets the products to `sold` and removes them from every cart. A failed payment marks the orders `failed`.

## Getting started

### Requirements
- PHP 8.2 or newer and Composer
- Node.js and npm
- A database (SQLite works out of the box; MySQL for production)
- Optional for full functionality: a Pusher app and a ToyyibPay account

### Setup

```bash
git clone https://github.com/syzwanzaidi/uitmarketplace.git
cd uitmarketplace

composer install
cp .env.example .env
php artisan key:generate

# SQLite: create the database file (skip if you use MySQL)
touch database/database.sqlite

php artisan migrate --seed
php artisan storage:link      # makes uploaded product photos reachable

npm install
```

### Run in development

```bash
composer dev
```

This starts the PHP server, the queue listener, the log viewer and Vite together. Open http://localhost:8000.

To run the pieces yourself instead:

```bash
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```

### Environment variables

`.env.example` lists every setting, including these:

```env
# Real-time chat (optional; chat falls back to polling without it)
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Payments (use https://dev.toyyibpay.com for the sandbox)
TOYYIBPAY_SECRET=
TOYYIBPAY_CATEGORY=
TOYYIBPAY_URL=
```

Notes:
- With `BROADCAST_CONNECTION=log`, chat still works but updates by polling every few seconds instead of live.
- Registration requires email verification. With `MAIL_MAILER=log`, the verification email is written to `storage/logs/laravel.log` instead of being sent.
- Payments can be tested locally without a tunnel: the return page verifies and settles the bill even when ToyyibPay cannot reach your callback URL.
- For MySQL, set `DB_CONNECTION=mysql` and the `DB_*` values.

### Seed data

`php artisan migrate --seed` creates categories, sample products around UiTM Shah Alam and these accounts (password `password` for all):

| Role | Email |
|---|---|
| Admin | `admin@uitmarketplace.test` |
| Seller (UiTM) | `aisyah@student.uitm.edu.my`, `hakim@student.uitm.edu.my` |
| Buyer | `buyer@example.com` |

### Production build

```bash
npm run build
php artisan migrate --force
php artisan config:cache
```

Serve `public/` with Nginx and PHP-FPM, and run the queue worker under a process manager such as Supervisor.

## Project structure

```
app/
  Events/MessageSent.php        broadcast event for chat
  Http/Controllers/             Category, Product, Cart, Checkout, Order, Payment, Chat, Listings, Wishlist, ...
  Http/Controllers/Admin/       users, transactions, complaints
  Http/Middleware/              admin and active-account checks
  Models/                       User, Product, Location, Order, Payment, Conversation, Message, ...
  Services/                     ToyyibPay client and payment settlement
database/migrations/            schema
resources/css/app.css           design system on top of Bootstrap
resources/js/                   maps, cart, chat and listing form behaviour
resources/views/                layouts, components, user, seller, admin and auth pages (Blade)
routes/web.php                  application routes
routes/channels.php             broadcast channel authorisation
```

## Tests

```bash
composer test
```

Feature tests cover access control (seller-only and admin-only areas, hidden listings, suspended accounts), listing ownership, chat privacy, cart rules, multi-seller checkout and payment verification (forged callbacks, amount mismatches, duplicate callbacks).

## Author

Muhammad Syazwan Syahmi Bin Mat Zaidi. [GitHub](https://github.com/syzwanzaidi) | [Portfolio](https://syzwanzaidi-portfolio.vercel.app/)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
