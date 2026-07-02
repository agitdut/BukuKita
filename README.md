# 📚 BukuKita

Sistem Informasi Perpustakaan Kampus berbasis web yang dibangun dengan **Laravel 13**, dilengkapi otomasi pengisian data buku via ISBN dan asisten AI menggunakan **Groq AI (Llama 3.3-70B)**.

Project ini dibuat sebagai **Ujian Akhir Semester (UAS)** mata kuliah Pemrograman Web Lanjut.

---

## ✨ Fitur Utama

- 🔐 **Autentikasi & RBAC** — 3 peran pengguna (Admin, Staff, Member) dengan hak akses berbeda menggunakan Spatie Permission
- 📊 **Dashboard Dinamis** — statistik real-time yang menyesuaikan tampilan berdasarkan role pengguna
- 📖 **Manajemen Buku** — CRUD lengkap dengan **auto-fill data buku otomatis** cukup dengan memasukkan ISBN (ditenagai OpenLibrary API)
- 🔍 **Pencarian & Filter Buku** — cari berdasarkan judul, penulis, atau ISBN
- 🔄 **Peminjaman & Pengembalian** — pencatatan transaksi dengan validasi stok, **perhitungan denda otomatis** (Rp 1.000/hari), dan auto-update status overdue
- ⚠️ **Notifikasi Keterlambatan** — peringatan visual di dashboard untuk buku yang belum dikembalikan melewati jatuh tempo
- 👥 **Manajemen Anggota** — admin dapat mendaftarkan dan mengelola data pengguna beserta perannya (dilindungi dari self-deletion)
- 🤖 **AI Assistant** — chatbot interaktif berbasis Groq AI yang dapat menjawab pertanyaan seputar koleksi buku dan informasi perpustakaan
- 🏷️ **Kategori Buku** — pengelompokan buku dengan relasi many-to-many
- 🎨 **Tema Akademis Custom** — tampilan hijau tua & gold dari template AdminLTE 3

---

## 🛠️ Tech Stack

| Kategori | Teknologi |
|---|---|
| Framework | Laravel 13 |
| Frontend / UI | AdminLTE 3.2.0, Bootstrap 4, Alpine.js |
| Autentikasi | Laravel Breeze |
| Role & Permission | Spatie Laravel Permission |
| AI Engine | Groq AI — Llama 3.3-70B Versatile |
| Database | SQLite |
| Asset Bundler | Vite + Tailwind CSS |
| Testing | Pest PHP |

---

## 👤 Role & Hak Akses

| Fitur | Admin | Staff | Member |
|---|---|---|---|
| Lihat Katalog Buku | ✅ | ✅ | ✅ |
| Kelola Buku (Tambah/Edit) | ✅ | ✅ | ❌ |
| Hapus Buku | ✅ | ❌ | ❌ |
| Kelola Peminjaman | ✅ | ✅ | ❌ |
| Lihat Riwayat Peminjaman Sendiri | — | — | ✅ |
| Manajemen User | ✅ | ❌ | ❌ |
| AI Assistant | ✅ | ✅ | ✅ |

---

## ⚙️ Instalasi

```bash
# Clone repository
git clone https://github.com/agitdut/bukukita.git
cd bukukita

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database (default SQLite) & Groq API Key di file .env
# GROQ_API_KEY=isi_api_key_groq_anda

# Migrasi & seeding database (RoleAndUserSeeder, CategorySeeder, DummyDataSeeder)
php artisan migrate --seed

# Build assets & jalankan server
npm run build
php artisan serve
```

> **Catatan:** Project ini menggunakan **SQLite** secara default (tersimpan di `database/database.sqlite`). Jika ingin beralih ke MySQL, ubah konfigurasi `DB_CONNECTION` di `.env`.

---

## 🔑 Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | admin@gmail.com | password |
| Staff | staff@gmail.com | password |
| Member | member@gmail.com | password |

---

## 🧪 Testing

Jalankan seluruh test (40+ test case):

```bash
php artisan test
```

Atau dengan parallel processing:

```bash
php artisan test --parallel
```

Cakupan test meliputi:
- ✅ Autentikasi & verifikasi email
- ✅ Manajemen profile
- ✅ CRUD buku & otorisasi (admin/staff/member)
- ✅ Peminjaman & pengembalian dengan kalkulasi denda
- ✅ RBAC (role-based access control)
- ✅ Validasi stok buku

---

## 📁 Struktur Project

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── BookController.php       # CRUD buku + auto-fill ISBN via OpenLibrary
│   │   ├── LoanController.php       # Peminjaman, pengembalian, denda, auto-overdue
│   │   ├── UserController.php       # Manajemen anggota & role
│   │   ├── ChatController.php       # AI Assistant (Groq Llama 3.3-70B)
│   │   └── DashboardController.php  # Statistik & notifikasi per role
│   └── Middleware/                   # Middleware kustom
├── Models/
│   ├── Book.php                     # Relasi: hasMany(Loan), belongsToMany(Category)
│   ├── Category.php                 # Relasi: belongsToMany(Book)
│   ├── Loan.php                     # Relasi: belongsTo(User), belongsTo(Book)
│   ├── ChatMessage.php              # Relasi: belongsTo(User)
│   └── User.php                     # Spatie HasRoles trait
database/
├── migrations/                      # 9 migration files
├── seeders/
│   ├── DatabaseSeeder.php           # Memanggil semua seeder
│   ├── RoleAndUserSeeder.php        # 3 roles + 7 permissions + 3 user awal
│   ├── CategorySeeder.php           # 10 kategori buku
│   └── DummyDataSeeder.php          # 5 member + 6 buku + 5 transaksi pinjam
└── factories/
    ├── UserFactory.php
    └── BookFactory.php
resources/views/
├── layouts/app.blade.php            # Layout utama (AdminLTE custom theme)
├── books/                           # index, create, edit, show
├── loans/                           # index, create, show
├── users/                           # index, create, edit
├── chat/                            # Chat AI dengan typewriter effect
├── dashboard/                       # Dashboard per role
├── profile/                         # Manajemen profile
└── auth/                            # Login, register, reset password
routes/
├── web.php                          # Route utama + middleware RBAC
└── auth.php                         # Route autentikasi (Breeze)
tests/
├── Feature/
│   ├── BookTest.php                 # 5 test: akses, CRUD, otorisasi
│   ├── LoanTest.php                 # 4 test: peminjaman, stok, denda
│   ├── RbacTest.php                 # 5 test: RBAC per role
│   └── Auth/                        # Test autentikasi Breeze
└── Unit/
```

---

## 🔌 API Endpoints (Internal)

| Method | Endpoint | Middleware | Deskripsi |
|---|---|---|---|
| GET | `/books/fetch-isbn` | auth | Auto-fill data buku via ISBN |
| POST | `/chat/send` | auth | Kirim prompt ke AI Assistant |
| DELETE | `/chat/clear` | auth | Hapus riwayat chat |
| POST | `/loans/{loan}/return` | auth, role:admin|staff | Proses pengembalian buku |

---

## 👨‍🎓 Dibuat oleh

**Agit**
NIM: 251351005
Universitas Wastukancana

---

## 📄 Lisensi

Project ini dibuat untuk keperluan akademik (UAS) Mata Kuliah Pemrograman Web Lanjut.
