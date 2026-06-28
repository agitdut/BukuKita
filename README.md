# 📚 BukuKita

Sistem Informasi Perpustakaan Kampus berbasis web yang dibangun dengan Laravel, dilengkapi otomasi pengisian data buku dan asisten AI menggunakan **Groq AI (Llama 3.3-70B)**.

Project ini dibuat sebagai **Ujian Akhir Semester (UAS)** mata kuliah Pemrograman Web Lanjut.

---

## ✨ Fitur Utama

- 🔐 **Autentikasi & Role-Based Access Control** — 3 peran pengguna (Admin, Staff, Member) dengan hak akses berbeda menggunakan Spatie Permission
- 📊 **Dashboard Dinamis** — statistik real-time yang menyesuaikan tampilan berdasarkan role pengguna
- 📖 **Manajemen Buku** — CRUD lengkap dengan **auto-fill data buku otomatis** cukup dengan memasukkan ISBN (ditenagai Groq AI)
- 🔍 **Pencarian & Filter Buku** — cari berdasarkan judul, penulis, atau ISBN
- 🔄 **Peminjaman & Pengembalian** — pencatatan transaksi dengan validasi stok dan **perhitungan denda otomatis** untuk keterlambatan
- ⚠️ **Notifikasi Keterlambatan** — peringatan visual di dashboard untuk buku yang belum dikembalikan melewati jatuh tempo
- 👥 **Manajemen Anggota** — admin dapat mendaftarkan dan mengelola data pengguna beserta perannya
- 🤖 **AI Assistant** — chatbot interaktif yang dapat menjawab pertanyaan seputar koleksi buku dan informasi perpustakaan
- 🎨 **Tema Akademis Custom** — tampilan hijau tua & gold yang disesuaikan dari template AdminLTE

---

## 🛠️ Tech Stack

| Kategori | Teknologi |
|---|---|
| Framework | Laravel 13 |
| Frontend / UI | AdminLTE 3.2.0, Bootstrap |
| Autentikasi | Laravel Breeze |
| Role & Permission | Spatie Laravel Permission |
| AI Engine | Groq AI — Llama 3.3-70B Versatile |
| Database | MySQL |

---

## 👤 Role & Hak Akses

| Fitur | Admin | Staff | Member |
|---|:---:|:---:|:---:|
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

# Konfigurasi database & Groq API Key di file .env
# DB_DATABASE=bukukita
# GROQ_API_KEY=isi_api_key_groq_anda

# Migrasi & seeding database
php artisan migrate --seed
php artisan db:seed --class=DummyDataSeeder

# Jalankan server
php artisan serve
```

---

## 🔑 Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | admin@gmail.com | password |
| Staff | staff@gmail.com | password |
| Member | budi@gmail.com | password |

---

## 📁 Struktur Modul

```
app/Http/Controllers/
├── BookController.php       # CRUD buku & integrasi Groq AI untuk auto-fill ISBN
├── LoanController.php       # Transaksi peminjaman, pengembalian, & denda
├── UserController.php       # Manajemen anggota
├── ChatController.php       # AI Assistant perpustakaan
└── DashboardController.php  # Statistik & notifikasi per role
```

---

## 👨‍🎓 Dibuat oleh

**Agit**
NIM: 251351005
Universitas Wastukancana

---

## 📄 Lisensi

Project ini dibuat untuk keperluan akademik (UAS).