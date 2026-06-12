# Perum API

API administrasi perumahan untuk mengelola rumah, penghuni, tagihan, dan pengeluaran.

> **Skill Fit Test — Full Stack Programmer @ Beon Intermedia**

---

> [!NOTE]
> **PDM tersedia di :**
> ```
> reports/pdm
> ```

---

## Persyaratan

| Dependensi | Versi Minimum |
|---|---|
| PHP | 8.3+ |
| MySQL | 8.0+ |
| Composer | 2.x |

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/KhenCahyo13/perum-api.git
cd perum-api
```

### 2. Install Dependensi

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuaikan konfigurasi database:

```env
APP_NAME="Perum API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perum_api
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

### 4. Buat Database

Buat database MySQL dengan nama yang sesuai di `.env` (default: `perum_api`).

### 5. Setup Database

Jalankan migrasi dalam urutan dependency yang benar menggunakan command bawaan:

```bash
php artisan app:setup-database
```

Tambahkan flag `--with-factories` untuk mengisi data contoh (rumah, penghuni, tagihan, pengeluaran):

```bash
php artisan app:setup-database --with-factories
```

> Gunakan `--fresh` untuk drop semua tabel terlebih dahulu sebelum migrasi ulang (cocok untuk reset lokal).

```bash
php artisan app:setup-database --fresh --with-factories
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Jalankan Server

```bash
php artisan serve
```

API akan berjalan di `http://localhost:8000`.

---

## Autentikasi

API menggunakan Laravel Sanctum (token-based). Login terlebih dahulu untuk mendapatkan token.

**Login:**
```
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@perum.test",
  "password": "password"
}
```

> Kredensial di atas tersedia setelah menjalankan `app:setup-database --with-factories`.

Gunakan token yang dikembalikan pada header setiap request yang memerlukan autentikasi:
```
Authorization: Bearer <token>
```

**Logout:**
```
POST /api/auth/logout
```

**Refresh Token:**
```
POST /api/auth/refresh
```

---

## Daftar Endpoint

### Reports / Dashboard

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/reports/dashboard` | Data dashboard lengkap + data keuangan bulanan |

### Houses (Rumah)

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/houses/stats` | Statistik rumah |
| GET | `/api/houses` | Daftar rumah (dengan filter & paginasi) |
| POST | `/api/houses` | Tambah rumah baru |
| GET | `/api/houses/{id}` | Detail rumah |
| PATCH | `/api/houses/{id}` | Update rumah |
| DELETE | `/api/houses/{id}` | Hapus rumah |
| POST | `/api/houses/{id}/assign-resident` | Tetapkan penghuni ke rumah |
| DELETE | `/api/houses/{id}/remove-resident` | Keluarkan penghuni dari rumah |

**Query params `GET /api/houses`:** `page`, `limit`, `search`, `status` (`occupied`\|`vacant`)

### Residents (Penghuni)

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/residents/stats` | Statistik penghuni |
| GET | `/api/residents` | Daftar penghuni (dengan filter & paginasi) |
| POST | `/api/residents` | Tambah penghuni baru |
| GET | `/api/residents/{id}` | Detail penghuni |
| PATCH | `/api/residents/{id}` | Update penghuni |

**Query params `GET /api/residents`:** `page`, `limit`, `search`, `residentType` (`permanent`\|`contract`)

### Fee Types (Jenis Iuran)

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/fee-types` | Daftar jenis iuran |
| POST | `/api/fee-types` | Tambah jenis iuran |
| GET | `/api/fee-types/{id}` | Detail jenis iuran |
| PATCH | `/api/fee-types/{id}` | Update jenis iuran |
| DELETE | `/api/fee-types/{id}` | Hapus jenis iuran |

### Bills (Tagihan)

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/bills/stats` | Statistik tagihan |
| GET | `/api/bills` | Daftar tagihan (dengan filter & paginasi) |
| POST | `/api/bills` | Buat tagihan baru |
| GET | `/api/bills/{id}` | Detail tagihan |
| PATCH | `/api/bills/{id}` | Update tagihan |
| DELETE | `/api/bills/{id}` | Hapus tagihan |

**Query params `GET /api/bills`:** `page`, `limit`, `search`, `status` (`unpaid`\|`paid`\|`late`), `houseId`, `billingMonth` (format: `YYYY-MM`)

### Payments (Pembayaran)

| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/api/payments` | Catat pembayaran tagihan |
| DELETE | `/api/payments/{id}` | Batalkan pembayaran |

### Expense Categories (Kategori Pengeluaran)

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/expense-categories` | Daftar kategori pengeluaran |
| POST | `/api/expense-categories` | Tambah kategori |
| GET | `/api/expense-categories/{id}` | Detail kategori |
| PATCH | `/api/expense-categories/{id}` | Update kategori |
| DELETE | `/api/expense-categories/{id}` | Hapus kategori |

### Expenses (Pengeluaran)

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/expenses/stats` | Statistik pengeluaran |
| GET | `/api/expenses` | Daftar pengeluaran (dengan filter & paginasi) |
| POST | `/api/expenses` | Tambah pengeluaran baru |
| GET | `/api/expenses/{id}` | Detail pengeluaran |
| PATCH | `/api/expenses/{id}` | Update pengeluaran |
| DELETE | `/api/expenses/{id}` | Hapus pengeluaran |

**Query params `GET /api/expenses`:** `page`, `limit`, `search`, `categoryId`, `isRecurring` (`true`\|`false`), `month` (format: `YYYY-MM`)

---

## Struktur Modul

```
Modules/
├── Core/           # Shared base classes, helpers, dan report/dashboard endpoint
├── House/          # Rumah & penghuni (termasuk riwayat hunian)
├── Bill/           # Tagihan, jenis iuran, dan pembayaran
└── Expense/        # Pengeluaran dan kategori pengeluaran
```

## Stack

- **Framework:** Laravel 13.8
- **Modular:** nwidart/laravel-modules 13.x
- **Auth:** Laravel Sanctum 4.x
- **PHP:** 8.3+
- **Database:** MySQL 8.0+
