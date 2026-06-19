<h1 align="center">
  <br>
  🛍️ SellOn
  <br>
</h1>

<h4 align="center">Marketplace Eksklusif Mahasiswa Kampus — <em>Buy & Sell Within Your Campus</em></h4>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-4.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

<p align="center">
  <a href="#-tentang-aplikasi">Tentang</a> •
  <a href="#-fitur-utama">Fitur</a> •
  <a href="#️-arsitektur-sistem">Arsitektur</a> •
  <a href="#-struktur-database">Database</a> •
  <a href="#-alur-sistem--algoritma">Algoritma</a> •
  <a href="#-stack-teknologi">Teknologi</a> •
  <a href="#-instalasi--setup">Instalasi</a>
</p>

---

## 📌 Tentang Aplikasi

**SellOn** adalah platform marketplace berbasis web yang dirancang **secara eksklusif untuk mahasiswa kampus**. Aplikasi ini memungkinkan mahasiswa untuk menjual dan membeli produk, makanan, minuman, serta menawarkan jasa kepada sesama mahasiswa di lingkungan kampus yang sama — layaknya marketplace mini internal kampus.

### 🎯 Tujuan Aplikasi

| Aspek | Deskripsi |
|-------|-----------|
| **Tujuan Utama** | Menyediakan platform jual-beli yang aman dan terpercaya di ekosistem kampus |
| **Target Pengguna** | Mahasiswa aktif yang memiliki NIM (Nomor Induk Mahasiswa) dan email kampus |
| **Keunggulan** | Transaksi langsung via WhatsApp tanpa proses checkout yang rumit |
| **Keamanan** | Sistem verifikasi email kampus memastikan hanya mahasiswa terverifikasi yang bisa bertransaksi |

---

## ✨ Fitur Utama

### 🔐 Autentikasi & Keamanan
- **Registrasi dengan NIM** — Setiap pengguna mendaftar menggunakan Nomor Induk Mahasiswa (15 digit), memastikan hanya mahasiswa resmi yang dapat bergabung.
- **Verifikasi Email Kampus** — Setelah registrasi, sistem mengirim email verifikasi ke inbox kampus. Akses penuh ke marketplace hanya diberikan setelah email terverifikasi.
- **Email Notification Kustom** — Notifikasi verifikasi email menggunakan template kustom Bahasa Indonesia via layanan SMTP Brevo.
- **Session Management** — Sistem logout yang aman dengan invalidasi session dan regenerasi token CSRF.

### 🛒 Manajemen Produk (CRUD Lengkap)
- **Tambah Produk** — Upload produk dengan nama, deskripsi, harga, kategori, kondisi, stok, dan hingga **6 foto produk**.
- **Lihat Detail** — Halaman detail produk dengan galeri foto, informasi lengkap, dan tombol kontak WhatsApp penjual.
- **Edit Produk** — Pemilik produk dapat mengedit data dan mengelola foto (tambah/hapus foto individual).
- **Hapus Produk** — Menghapus produk beserta seluruh file gambar terkait dari storage.
- **Perlindungan Kepemilikan** — Hanya pemilik produk yang dapat mengedit atau menghapus produknya (cek `Auth::id() !== $product->user_id`).

### 🔍 Pencarian & Filter Katalog
- **Pencarian Real-time** — Filter produk berdasarkan nama menggunakan SQL `LIKE`.
- **Filter Kategori** — Saring produk berdasarkan kategori: **All, Preloved, Food, Beverage, Service**.
- **Pengurutan Fleksibel** — Urutkan produk: **Terbaru (Newest)**, **Harga: Terendah**, **Harga: Tertinggi**.
- **Counter Kategori** — Menampilkan jumlah produk per kategori secara dinamis.
- **Pagination** — Katalog dipaginasi 20 produk per halaman, dashboard 20 produk per halaman.

### ❤️ Sistem Favorit
- **Toggle Favorit** — Tambah/hapus produk ke daftar favorit dengan satu klik (mendukung AJAX & non-AJAX).
- **Halaman My Favorites** — Halaman khusus yang menampilkan semua produk yang difavoritkan pengguna.

### 👤 Profil Pengguna
- **Halaman Profil Publik** — Setiap pengguna memiliki halaman profil yang dapat dilihat publik, menampilkan data diri dan daftar produknya.
- **Edit Profil** — Pengguna dapat mengubah nama, NIM, jurusan, email, dan nomor WhatsApp.
- **Re-verifikasi Email** — Jika email diubah, sistem otomatis mereset status verifikasi dan mengirim link verifikasi baru ke email yang baru.
- **Avatar Otomatis** — Avatar dibuat otomatis oleh layanan DiceBear berdasarkan nama pengguna (tidak perlu upload foto profil).

### 📱 Antarmuka Responsif
- **Mobile-First Design** — Navigasi hamburger menu untuk tampilan mobile.
- **Multi-Foto Produk** — Galeri foto interaktif untuk menampilkan hingga 6 foto per produk.
- **Toast Notifications** — Notifikasi sukses/gagal yang muncul di sudut layar untuk setiap aksi pengguna.

---

## 🏗️ Arsitektur Sistem

SellOn dibangun menggunakan pola **MVC (Model-View-Controller)** dengan framework Laravel 12.

```
sellon/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── ProductController.php    # Logika CRUD produk + katalog
│   │       ├── UserController.php       # Autentikasi + manajemen profil
│   │       ├── FavoriteController.php   # Toggle & daftar favorit
│   │       └── VerificationController.php # Verifikasi email kampus
│   ├── Models/
│   │   ├── User.php                     # Model pengguna (mahasiswa)
│   │   ├── Product.php                  # Model produk
│   │   ├── ProductImage.php             # Model gambar produk (multi-foto)
│   │   └── Favorite.php                 # Model favorit (pivot user-product)
│   ├── Notifications/
│   │   └── VerifyEmailNotification.php  # Template email verifikasi kustom
│   └── Providers/
├── database/
│   ├── migrations/                      # Skema database
│   └── seeders/
├── resources/
│   ├── css/                             # Stylesheet Tailwind CSS
│   ├── js/                              # JavaScript interaktivitas
│   └── views/
│       ├── auth/                        # Halaman login, register, verifikasi
│       ├── layouts/                     # Layout dasar aplikasi
│       ├── partials/                    # Komponen reusable (navbar, footer, card)
│       ├── products/                    # View CRUD produk & katalog
│       └── users/                       # View profil & dashboard pengguna
└── routes/
    └── web.php                          # Definisi semua rute aplikasi
```

---

## 🗄️ Struktur Database

SellOn menggunakan **4 tabel utama** dengan relasi sebagai berikut:

```
┌─────────────────────────────────────┐
│              users                  │
├─────────────────────────────────────┤
│  id            (PK, Auto Increment) │
│  name          (string)             │ ← Nama lengkap sesuai KTM
│  nim           (string 15, UNIQUE)  │ ← NIM 15 digit
│  major         (string)             │ ← Jurusan
│  email         (string, UNIQUE)     │ ← Email kampus
│  whatsapp_no   (string 15)          │ ← No. WhatsApp untuk kontak
│  role          (string, def: 'user')│
│  password      (string, hashed)     │
│  email_verified_at (timestamp)      │ ← NULL = belum terverifikasi
│  created_at / updated_at            │
└─────────────────┬───────────────────┘
                  │ hasMany
                  ▼
┌─────────────────────────────────────┐
│             products                │
├─────────────────────────────────────┤
│  id            (PK, Auto Increment) │
│  name          (string)             │
│  description   (text)               │
│  price         (decimal 15,2)       │
│  stock         (integer, NULLABLE)  │ ← NULL untuk kategori Service
│  category      (string)             │ ← Preloved|Food|Beverage|Service
│  condition     (string)             │ ← Kondisi barang
│  image_url     (string, NULLABLE)   │ ← URL thumbnail utama
│  user_id       (FK → users.id)      │
│  created_at / updated_at            │
└──────┬──────────────┬───────────────┘
       │ hasMany      │ hasMany
       ▼              ▼
┌──────────────┐  ┌───────────────────┐
│product_images│  │     favorites     │
├──────────────┤  ├───────────────────┤
│ id           │  │ id                │
│ product_id   │  │ user_id (FK)      │
│ image_url    │  │ product_id (FK)   │
│ created_at   │  │ created_at        │
│ updated_at   │  │ updated_at        │
└──────────────┘  └───────────────────┘
```

### Relasi Antar Model

| Relasi | Dari | Ke | Tipe |
|--------|------|----|------|
| User → Products | `users` | `products` | `hasMany` |
| Product → User | `products` | `users` | `belongsTo` |
| Product → Images | `products` | `product_images` | `hasMany` |
| User → Favorites | `users` | `favorites` | `hasMany` |
| Product → Favorites | `products` | `favorites` | `hasMany` |
| Favorite → User | `favorites` | `users` | `belongsTo` |
| Favorite → Product | `favorites` | `products` | `belongsTo` |

---

## ⚙️ Alur Sistem & Algoritma

### 1. Alur Registrasi & Verifikasi Email

```
User Input (name, nim, major, email, whatsapp, password)
        │
        ▼
 Validasi Data (Laravel Validator)
        │
        ├── NIM sudah terdaftar? ──► Redirect + Toast Error
        │
        ├── Email sudah terdaftar? ──► Redirect + Toast Error
        │
        ▼
 Hash Password (bcrypt, 12 rounds)
        │
        ▼
 Simpan User ke Database
        │
        ▼
 Auto Login (auth guard 'web')
        │
        ▼
 Kirim Email Verifikasi (Brevo SMTP)
  [Template kustom Bahasa Indonesia]
        │
        ▼
 Redirect ke Halaman /verify (instruksi)
        │
 User klik link di email
        │
        ▼
 Laravel verifikasi signed URL (middleware 'signed')
        │
        ▼
 email_verified_at diisi → Akses penuh dibuka
```

### 2. Alur Upload & Penyimpanan Foto Produk

```
User Upload (maks. 6 foto, maks. 5120KB/foto, format: jpeg/png/jpg/gif)
        │
        ▼
 Validasi File (image, mimes, max size)
        │
        ▼
 Foto pertama → disimpan sebagai thumbnail (products.image_url)
        │
        ▼
 Semua foto → disimpan di storage/app/public/products/
        │
        ▼
 Setiap path foto → disimpan ke tabel product_images
  (product_id, image_url)
        │
        ▼
 Akses foto via: /uploads/products/{filename}
 (menggunakan request()->root() agar tidak terikat APP_URL)
```

### 3. Algoritma Katalog dengan Search, Filter & Sort

```php
// Pseudocode alur katalog produk

baseQuery = Product::query()

// Step 1: Terapkan pencarian (jika ada input 'search')
if (request.search != '') {
    baseQuery.where('name', 'LIKE', '%search%')
}

// Step 2: Hitung jumlah per kategori (SEBELUM filter kategori diterapkan)
// Ini memastikan counter menampilkan hasil pencarian, bukan semua produk
categoryCounts = {
    'All'      => count(baseQuery),
    'Preloved' => count(baseQuery.where('category', 'Preloved')),
    'Food'     => count(baseQuery.where('category', 'Food')),
    'Beverage' => count(baseQuery.where('category', 'Beverage')),
    'Service'  => count(baseQuery.where('category', 'Service')),
}

// Step 3: Terapkan filter kategori (jika bukan 'All')
query = clone(baseQuery)
if (request.filter != 'All') {
    query.where('category', request.filter)
}

// Step 4: Terapkan pengurutan
if (sort == 'Price: Low → High') {
    query.orderBy('price', 'asc')
} else if (sort == 'Price: High → Low') {
    query.orderBy('price', 'desc')
} else {  // Default: Newest
    query.orderBy('created_at', 'desc')
}

// Step 5: Paginate hasil (20 per halaman, pertahankan query string)
products = query.paginate(20).withQueryString()
```

> **Kunci desain**: `baseQuery` untuk menghitung `categoryCounts` diclone *sebelum* filter kategori diterapkan, sehingga counter kategori selalu mencerminkan hasil pencarian yang benar.

### 4. Sistem Favorit (Toggle)

```
User klik tombol Favorit pada produk X
        │
        ▼
 Cek di tabel favorites:
 WHERE user_id = Auth::id() AND product_id = X
        │
        ├── Ditemukan ──► DELETE record → Status: 'removed'
        │
        └── Tidak ada ──► INSERT record → Status: 'added'
        │
        ▼
 Jika AJAX request → return JSON response
 Jika non-AJAX    → redirect back + Toast notification
```

### 5. Pengelolaan Edit Foto Produk

```
Pengguna di halaman Edit Produk:
        │
        ├── Hapus foto yang ada
        │       │
        │       ▼
        │   Ambil ID foto yang dipilih (deleted_images[])
        │   → Hapus file dari Storage::disk('public')
        │   → Hapus record dari tabel product_images
        │
        └── Tambah foto baru
                │
                ▼
            Simpan file baru ke storage
            → Buat record baru di product_images
        │
        ▼
 Update thumbnail: ambil foto pertama yang tersisa
 → Update products.image_url dengan foto pertama
```

---

## 🔑 Sistem Middleware & Proteksi Rute

| Rute | Middleware | Keterangan |
|------|-----------|------------|
| `/` (Home) | — | Publik, tanpa login |
| `/products/catalog` | — | Publik, tanpa login |
| `/products/{id}` | — | Detail produk, publik |
| `/profile/{id}` | — | Profil pengguna, publik |
| `/products/create` | `auth`, `verified` | Harus login & email terverifikasi |
| `/products/form` (POST) | `auth`, `verified` | Harus login & email terverifikasi |
| `/products/{id}/edit` | `auth`, `verified` | Harus login & email terverifikasi |
| `/users/my-products` | `auth`, `verified` | Harus login & email terverifikasi |
| `/favorite/toggle/{id}` | `auth`, `verified` | Harus login & email terverifikasi |
| `/my-favorites` | `auth`, `verified` | Harus login & email terverifikasi |
| `/profile/{id}/edit` | `auth` | Hanya perlu login (bolum belum verified) |
| `/verify/resend` | `auth`, `throttle:1,1` | Throttle: maks. 1x per menit |

---

## 📦 Stack Teknologi

### Backend
| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| **PHP** | ^8.2 | Bahasa pemrograman server-side |
| **Laravel** | ^12.0 | Framework MVC utama |
| **Livewire** | ^4.2 | Komponen dinamis reaktif |
| **Laravel Tinker** | ^2.10 | REPL untuk debugging |

### Frontend
| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| **Blade** | (Laravel) | Template engine server-side |
| **TailwindCSS** | ^4.2 | Utility-first CSS framework |
| **Vite** | ^7.0 | Build tool & dev server frontend |
| **Axios** | ^1.11 | HTTP client untuk request AJAX |
| **Font Awesome** | (CDN) | Ikon UI |
| **DiceBear API** | (CDN) | Avatar pengguna otomatis |

### Database & Storage
| Teknologi | Kegunaan |
|-----------|----------|
| **MySQL** | Database utama |
| **Laravel File Storage** | Penyimpanan foto produk lokal (`storage/app/public`) |

### Email & Komunikasi
| Layanan | Kegunaan |
|---------|----------|
| **Brevo (Sendinblue) SMTP** | Pengiriman email verifikasi |
| **WhatsApp** | Komunikasi langsung pembeli-penjual (link `wa.me/`) |

### Development Tools
| Tool | Kegunaan |
|------|----------|
| **Laravel Pail** | Log viewer real-time |
| **Laravel Sail** | Docker environment |
| **Concurrently** | Menjalankan beberapa proses sekaligus (`artisan serve` + `queue` + `vite`) |

---

## 🚀 Instalasi & Setup

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL
- Akun Brevo (untuk email verifikasi)

### 1. Clone & Install Dependensi

```bash
git clone <repo-url>
cd sellon

# Install semua dependensi sekaligus (menggunakan composer script)
composer run setup
```

Script `setup` secara otomatis menjalankan:
1. `composer install`
2. Salin `.env.example` → `.env` (jika belum ada)
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

### 2. Konfigurasi Environment

Buka file `.env` dan sesuaikan konfigurasi berikut:

```env
# Konfigurasi Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1c
DB_PORT=3306
DB_DATABASE=sellon
DB_USERNAME=root
DB_PASSWORD=your_password

# Konfigurasi Email (Brevo SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your_brevo_smtp_username
MAIL_PASSWORD=your_brevo_smtp_password
MAIL_FROM_ADDRESS="sellon@yourdomain.com"
MAIL_FROM_NAME="SellOn"
```

### 3. Setup Storage

```bash
# Buat symlink untuk storage publik (akses foto produk via browser)
php artisan storage:link
```

### 4. Jalankan Aplikasi (Development)

```bash
# Menjalankan semua proses sekaligus (server + queue + logs + vite)
composer run dev

# Atau secara manual:
php artisan serve          # Web server
php artisan queue:listen   # Queue worker (untuk email)
npm run dev                # Vite dev server
```

Akses aplikasi di: **http://localhost:8000**

---

## 📋 Daftar Route

```
GET    /                              → Home (landing page + produk terbaru)
GET    /login                         → Halaman login
POST   /login_post                    → Proses login
GET    /register                      → Halaman registrasi
POST   /register_post                 → Proses registrasi
POST   /logout                        → Proses logout

GET    /verify                        → Halaman instruksi verifikasi email
GET    /email/verify/{id}/{hash}      → Callback link verifikasi dari email
POST   /verify/resend                 → Kirim ulang email verifikasi

GET    /products/catalog              → Katalog semua produk (search/filter/sort)
GET    /products/create               → Form tambah produk [auth+verified]
POST   /products/form                 → Simpan produk baru [auth+verified]
GET    /products/view/{view_type}     → Tampilkan produk ke view tertentu
GET    /products/{id}                 → Detail produk
GET    /products/{id}/edit            → Form edit produk [auth+verified]
PUT    /products/{id}                 → Update produk [auth+verified]
DELETE /products/delete/{id}          → Hapus produk [auth+verified]

GET    /profile/{id?}                 → Halaman profil pengguna
GET    /profile/{id}/edit             → Form edit profil [auth]
PUT    /profile/{id}                  → Update profil [auth]
GET    /users/my-products             → Dashboard produk saya [auth+verified]

POST   /favorite/toggle/{product_id}  → Toggle favorit [auth+verified]
GET    /my-favorites                  → Daftar produk favorit [auth+verified]
```

---

## 🔒 Keamanan

- **Password Hashing**: Menggunakan `bcrypt` dengan 12 rounds melalui `Hash::make()`.
- **CSRF Protection**: Semua form POST/PUT/DELETE dilindungi token CSRF (`@csrf`).
- **Email Verification**: Akses ke fitur marketplace dibatasi hanya untuk email terverifikasi.
- **Signed URL**: Link verifikasi email menggunakan signed URL Laravel untuk mencegah manipulasi.
- **Authorization Check**: Controller memeriksa kepemilikan sebelum izinkan edit/hapus.
- **Mass Assignment Protection**: Semua model menggunakan `$fillable` untuk mencegah mass assignment vulnerability.
- **Input Validation**: Semua input pengguna divalidasi di sisi server menggunakan Laravel Validator.
- **Session Security**: Logout melakukan invalidasi session + regenerasi CSRF token.
- **Throttle**: Endpoint kirim ulang email dibatasi 1 request per menit (`throttle:1,1`).

---

## 📂 Kategori Produk

| Kategori | Deskripsi | Stok |
|----------|-----------|------|
| **Preloved** | Barang bekas pakai mahasiswa | ✅ Wajib diisi |
| **Food** | Makanan yang dijual mahasiswa | ✅ Wajib diisi |
| **Beverage** | Minuman yang dijual mahasiswa | ✅ Wajib diisi |
| **Service** | Jasa yang ditawarkan mahasiswa | ❌ `NULL` (tidak relevan) |

> **Catatan**: Untuk kategori **Service**, field `stock` otomatis diset `NULL` karena jasa tidak memiliki stok fisik.

---

## 🤝 Kontribusi

SellOn adalah proyek kampus. Untuk berkontribusi:

1. Fork repositori ini
2. Buat branch fitur baru (`git checkout -b feature/NamaFitur`)
3. Commit perubahan (`git commit -m 'feat: tambah fitur X'`)
4. Push ke branch (`git push origin feature/NamaFitur`)
5. Buat Pull Request

---

## 📄 Lisensi

Proyek ini menggunakan lisensi **MIT**. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

---

<p align="center">
  Dibuat dengan ❤️ oleh Mahasiswa, untuk Mahasiswa
  <br>
  <strong>SellOn</strong> — <em>Buy & Sell Within Your Campus</em>
</p>
