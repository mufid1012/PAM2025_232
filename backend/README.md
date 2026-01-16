# RunRace Backend API

REST API backend untuk aplikasi RunRace menggunakan PHP + MySQL.

## Persyaratan

- PHP 7.4+ atau 8.0+
- MySQL 5.7+ atau MariaDB 10.3+
- Apache/Nginx dengan mod_rewrite

## Instalasi

### 1. Setup Database

```bash
# Login ke MySQL
mysql -u root -p

# Jalankan script database
source database.sql
```

Atau import melalui phpMyAdmin.

### 2. Konfigurasi

Edit file `config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'runrace_db');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
```

### 3. Setup Web Server

#### Apache (.htaccess sudah disediakan)

Pastikan `mod_rewrite` aktif dan `AllowOverride All` di konfigurasi Apache.

#### Nginx

```nginx
location /backend/api {
    try_files $uri $uri/ /backend/api/index.php?$query_string;
}
```

### 4. Jalankan Server

#### Menggunakan PHP Built-in Server (Development)

```bash
cd backend/api
php -S localhost:8000
```

Akses API di: `http://localhost:8000`

#### Menggunakan XAMPP/Laragon

Taruh folder di `htdocs` atau `www`, akses melalui:
`http://localhost/RunRaceFinalProject/backend/api`

## Akun Default

| Email | Password | Role |
|-------|----------|------|
| admin@runrace.com | password | Admin |
| john@example.com | password | User |
| jane@example.com | password | User |

## Update Constants di Android

Setelah mengetahui URL backend, update file Android:

```kotlin
// utils/Constants.kt
const val BASE_URL = "http://10.0.2.2:8000/"  // Untuk emulator
// atau
const val BASE_URL = "http://192.168.x.x:8000/api/"  // Untuk device fisik
```

## API Endpoints

### Authentication

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | /auth/login | Login |
| POST | /auth/register | Register |
| POST | /auth/logout | Logout |
| GET | /auth/me | Get current user |

### Events

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | /events | List semua event |
| GET | /events?status=ongoing | Filter by status |
| GET | /events/{id} | Detail event |
| POST | /events | Buat event (Admin) |
| PUT | /events/{id} | Update event (Admin) |
| DELETE | /events/{id} | Hapus event (Admin) |
| POST | /events/{id}/register | Daftar event |

### Registrations

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | /registrations/my | Event yang didaftar user |

### Users

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | /users/profile | Get profile |
| PUT | /users/profile | Update profile |
| PUT | /users/password | Ganti password |

### News

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | /news | List semua berita |
| GET | /news/featured | Berita pilihan |

## Contoh Request

### Login

```bash
curl -X POST http://localhost:8000/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@runrace.com","password":"password"}'
```

### Get Events (dengan token)

```bash
curl http://localhost:8000/events \
  -H "Authorization: Bearer YOUR_TOKEN"
```
