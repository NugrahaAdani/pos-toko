# Barokah Mart 🛒

### Kelola toko, stok, dan transaksi dalam satu tempat.

Barokah Mart adalah aplikasi point of sale (POS) berbasis web untuk membantu toko mengelola produk, persediaan, pembelian, pengguna, dan penjualan harian.

## Gambaran Umum

Kasir dapat mencatat transaksi dan melihat riwayat penjualan, sementara admin mengelola katalog dan operasional toko. Dashboard menampilkan ringkasan omzet, tren penjualan, produk terlaris, dan stok yang perlu diperhatikan.

Aplikasi menggunakan antarmuka berbahasa Indonesia dan menyediakan laporan penjualan yang dapat diekspor ke Excel.

## Fitur

- **Dashboard:** ringkasan omzet dan transaksi, tren penjualan tujuh hari, produk terlaris, serta stok menipis.
- **Point of sale:** pencatatan transaksi penjualan dan cetak struk.
- **Produk dan kategori:** pengelolaan katalog, harga, stok, serta gambar produk.
- **Pemasok dan pembelian:** pencatatan pemasok dan purchase order beserta item pembeliannya.
- **Laporan penjualan:** filter berdasarkan periode dan kasir, dengan ekspor Excel.
- **Pengguna dan akses:** akun admin dan kasir dengan akses sesuai peran.
- **Riwayat transaksi:** penelusuran transaksi yang sudah tercatat.

## Teknologi

- **Aplikasi web:** Laravel 13 dan PHP 8.3+.
- **Antarmuka:** Blade, Tailwind CSS 4, Alpine.js, dan Chart.js.
- **Build frontend:** Vite 8 dan npm.
- **Database:** MySQL.
- **Ekspor laporan:** Laravel Excel 4.

## Struktur Proyek

```text
.
|-- app/
|   |-- Exports/              # Export laporan penjualan
|   |-- Http/Controllers/     # Alur halaman dan transaksi
|   `-- Models/               # Model data aplikasi
|-- database/
|   |-- migrations/           # Struktur tabel
|   `-- seeders/              # Akun demo dan data contoh
|-- resources/
|   |-- css/                  # Style aplikasi
|   |-- js/                   # JavaScript frontend
|   `-- views/                # Halaman Blade
|-- routes/web.php            # Route aplikasi
|-- composer.json             # Dependensi dan perintah Laravel
`-- package.json              # Dependensi dan perintah frontend
```

## Mulai

### Prasyarat

- PHP 8.3 atau lebih baru, Composer, Node.js, dan npm.
- Ekstensi PHP SQLite aktif untuk konfigurasi database bawaan.

### Instalasi di Windows PowerShell

Jalankan perintah berikut dari direktori proyek:

```powershell
composer install

if (!(Test-Path '.env')) { Copy-Item '.env.example' '.env' }
if (!(Test-Path 'database/database.sqlite')) {
    New-Item -ItemType File -Path 'database/database.sqlite' | Out-Null
}

php artisan key:generate
php artisan migrate --seed

npm install
npm run build
```

### Jalankan saat pengembangan

```bash
composer run dev
```

Perintah tersebut menjalankan server Laravel, worker queue, dan Vite. Buka alamat lokal yang ditampilkan oleh server Laravel di terminal.

## Akun Demo

Seeder membuat akun berikut untuk penggunaan lokal:

| Peran | Email | Kata sandi |
| --- | --- | --- |
| Admin | `admin@barokahmart.test` | `password` |
| Kasir | `kasir@barokahmart.test` | `password` |

## Perintah Berguna

```bash
php artisan migrate --seed   # Terapkan migrasi dan isi data contoh
php artisan test             # Jalankan test aplikasi
npm run dev                  # Jalankan Vite saja
npm run build                # Build aset frontend
```
