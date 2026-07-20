# Juicebox

RESTful API dibangun pakai Laravel — fitur Posts, Users (auth pakai Sanctum), dan Weather (integrasi eksternal API + caching + scheduled job).

## Tech Stack

- Laravel 13.8
- PHP ^8.3
- MySQL
- Laravel Sanctum (API authentication)
- Queue (welcome email job)
- Weather API: [WeatherAPI.com](https://www.weatherapi.com/)
- Mail testing: [Mailtrap](https://mailtrap.io/)

## Fitur

- Auth (register, login, logout) pakai Laravel Sanctum
- CRUD Posts (relasi `Post belongsTo User`)
- List & detail Users
- Weather endpoint (data cuaca kota Perth, Australia), dengan caching 15 menit
- Background job update data weather tiap jam (via scheduler)
- Queued job kirim welcome email saat user register
- Artisan command buat manual dispatch welcome email job
- Request validation seragam (known & unknown error) + HTTP status code sesuai
- Testing (PHPUnit) untuk Posts, Users, dan Weather (pakai `Http::fake`)

## Setup

### 1. Clone & Install

```bash
git clone https://github.com/fiandimas/juicebox.git
cd juicebox
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Konfigurasi `.env`

Sesuaikan variable berikut di file `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=xxx
DB_PORT=xxx
DB_DATABASE=xxx
DB_USERNAME=xxx
DB_PASSWORD=xxx

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=xxx
MAIL_PORT=xxx
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
MAIL_FROM_ADDRESS=xxx
MAIL_FROM_NAME=xxx

WEATHER_API_KEY=xxx
```

### 3. Migrate Database

```bash
php artisan migrate
```

### 4. Jalankan Server

```bash
php artisan serve
```

## Setup Weather API (WeatherAPI.com)

Endpoint weather di project ini mengambil data cuaca kota **Perth, Australia** dari [WeatherAPI.com](https://www.weatherapi.com/), dengan caching selama 15 menit untuk minimalisir API call.

Cara dapetin API key:

1. Register akun pakai email di [weatherapi.com](https://www.weatherapi.com/)
2. Verifikasi email
3. Copy `API Key` yang muncul di dashboard
4. Paste ke `.env`:

```dotenv
WEATHER_API_KEY=
```

Setelah itu langsung bisa dipakai, tidak perlu konfigurasi tambahan.

## Setup Email (Mailtrap)

Untuk testing pengiriman welcome email, project ini pakai [Mailtrap](https://mailtrap.io/) (sandbox SMTP, gratis).

Cara setup:

1. Register akun pakai Gmail/GitHub di [mailtrap.io](https://mailtrap.io/)
2. Buka sidebar → menu **Sandboxes**
3. Buat sandbox baru
4. Copy credential SMTP yang disediakan (host, port, username, password)
5. Paste ke bagian `MAIL_*` di `.env`

> **Catatan:** karena pakai Mailtrap **Sandbox**, email tidak benar-benar terkirim ke inbox asli penerima. Semua email yang dikirim akan ketampung di inbox sandbox Mailtrap (menu **Sandboxes** → project sandbox yang dibuat), jadi aman dipakai buat testing tanpa nyampah ke email asli user.

## Queue Worker

Welcome email dikirim lewat queued job. Jalankan queue worker dengan:

```bash
php artisan queue:work
```

## Weather Update Job (Scheduler)

Data weather di-refresh otomatis setiap jam lewat Laravel Scheduler. Supaya jalan, tambahkan cron entry berikut di server:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Manual Dispatch Welcome Email (Artisan Command)

Untuk testing/dispatch manual welcome email job:

```bash
php artisan app:welcome-email {email}
```

> Email yang dipakai harus sudah terdaftar (ada) di database, kalau tidak ketemu command akan menampilkan pesan `User is not found`.

## API Endpoints

### Auth

| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/api/register` | Register user baru |
| POST | `/api/login` | Login user |
| POST | `/api/logout` | Logout user (auth required) |

### Posts (auth required)

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/posts` | List semua post (pagination) |
| GET | `/api/posts/{id}` | Detail post |
| POST | `/api/posts` | Buat post baru |
| PATCH | `/api/posts/{id}` | Update post |
| DELETE | `/api/posts/{id}` | Hapus post |

### Users (auth required)

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/users` | List semua user (pagination) |
| GET | `/api/users/{id}` | Detail user |

### Weather (auth required)

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/weathers` | Data cuaca terkini kota Perth (cache 15 menit) |

## Testing

```bash
php artisan test
```

Test coverage meliputi:

- `PostTest` — create post (authenticated & guest), validasi data invalid, list post
- `UserTest` — register (valid & duplicate email), login (benar & salah password)
- `WeatherTest` — ambil data weather dari API, data yang sudah di-cache tidak manggil API lagi, handling saat external API gagal (pakai `Http::fake`)