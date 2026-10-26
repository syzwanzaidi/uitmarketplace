# UiTMarketplace

A location-based online marketplace for the UiTM Shah Alam community. Students list items for sale, buyers discover what is near them, chat with the seller in real time, and pay online. Built as a final year project.

## Features

**Buying**
- Browse by category, search by keyword, and sort by price or newest
- Location filter: pick a radius and see only items within that distance of you (Haversine distance, calculated in SQL)
- Product pages, wishlist, cart, and "Buy now" checkout
- Online payment through the ToyyibPay gateway
- Order history, courier and tracking number from the seller, and a "mark as received" confirmation

**Selling**
- Seller dashboard to create, edit, activate/deactivate and delete listings with photos
- Drop a pin on a Leaflet map when listing an item so buyers can see how far away it is
- Seller order view with tracking number and courier updates, plus order history

**Chat**
- One conversation per product between buyer and seller
- Real-time delivery over Pusher using Laravel Echo, with read receipts

**Accounts and admin**
- Registration, login and email verification (Laravel Breeze)
- Suspended accounts are blocked by an `active` middleware
- Admin area to suspend or reactivate users, review all transactions, and resolve complaints submitted through the contact form

## Tech stack

| Layer | Tools |
|---|---|
| Backend | PHP 8.2+, Laravel 12, Laravel Breeze |
| Frontend | Blade, Tailwind CSS, Alpine.js, Vite |
| Database | MySQL in production (SQLite by default for local development) |
| Maps | Leaflet (seller location picker) |
| Real-time | Pusher, Laravel Echo |
| Payments | ToyyibPay |
| Deployment | DigitalOcean droplet with Nginx |

## How the main flows work

**Distance filtering.** Each product has a row in `locations` (latitude and longitude). When a buyer chooses a radius, the category query computes the Haversine distance in SQL, filters by the radius and orders by nearest first.

**Payment.** Checkout creates an order and a ToyyibPay bill. ToyyibPay then calls `POST /toyyibpay/callback`, which is a public route. The callback is idempotent (a repeated callback for a paid order is ignored), checks that the paid amount matches the order total, marks the order as paid, records the payment, and sets the purchased products to `sold`. A failed payment marks the order `failed`.

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

`.env.example` covers the standard Laravel settings. For the full app you also need the following in your `.env`.

```env
# Real-time chat
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Payments
TOYYIBPAY_SECRET=
TOYYIBPAY_CATEGORY=
TOYYIBPAY_URL=
```

Notes:
- With the default `BROADCAST_CONNECTION=log`, the app runs but chat messages are not pushed live.
- Registration requires email verification. With the default `MAIL_MAILER=log`, the verification email is written to `storage/logs/laravel.log` instead of being sent.
- ToyyibPay must be able to reach your callback URL, so use a public URL (for example a tunnel) when testing payments locally.
- For MySQL, set `DB_CONNECTION=mysql` and the `DB_*` values.

### Seed data

`php artisan migrate --seed` creates the categories, some sample products and a test seller account (`seller@example.com`). See `database/seeders/` for details.

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
database/migrations/            schema
resources/views/                user, seller, admin and auth pages (Blade)
routes/web.php                  application routes
routes/channels.php             broadcast channel authorisation
```

## Tests

```bash
composer test
```

## Author

Muhammad Syazwan Syahmi Bin Mat Zaidi. [GitHub](https://github.com/syzwanzaidi) | [Portfolio](https://syzwanzaidi-portfolio.vercel.app/)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
