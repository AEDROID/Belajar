# Fitur Log Aktivitas - Dokumentasi

## 📋 Deskripsi
Fitur Log Aktivitas adalah sistem pencatatan otomatis yang merekam semua aktivitas penting yang dilakukan oleh user dalam aplikasi peminjaman. Fitur ini membantu admin untuk:
- Memantau aktivitas user
- Audit trail untuk keamanan
- Debugging masalah
- Analisis penggunaan sistem

## 🎯 Fitur Utama

### 1. **Pencatatan Otomatis**
Log aktivitas akan tercatat secara otomatis ketika user melakukan:
- Login/Logout
- CRUD Kategori (Create, Read, Update, Delete)
- CRUD Alat
- CRUD Peminjaman
- Perubahan status peminjaman
- Pengembalian alat
- Pembayaran denda

### 2. **Filter dan Pencarian**
- **Search**: Cari berdasarkan aktivitas, keterangan, atau nama user
- **Filter Modul**: Filter berdasarkan modul (Auth, Peminjaman, Alat, dll)
- **Filter Tanggal**: Filter berdasarkan rentang tanggal

### 3. **Informasi yang Dicatat**
Setiap log mencatat:
- User yang melakukan aktivitas
- Waktu aktivitas (tanggal dan jam)
- Jenis aktivitas
- Modul terkait
- Keterangan detail
- IP Address
- User Agent (browser/device)

## 🚀 Cara Menggunakan

### Untuk Developer

#### 1. Migration
```bash
php artisan migrate
```

#### 2. Seeder (Optional - untuk testing)
```bash
php artisan db:seed --class=LogAktivitasSeeder
```

#### 3. Menggunakan Trait di Controller

Tambahkan trait `LogsActivity` di controller:

```php
use App\Traits\LogsActivity;

class YourController extends Controller
{
    use LogsActivity;
    
    public function store(Request $request)
    {
        // Your logic here
        $item = Item::create($request->all());
        
        // Log aktivitas
        $this->logCreate('Item', "Menambah item: {$item->name}");
        
        return redirect()->back();
    }
}
```

#### 4. Method yang Tersedia

**Method Umum:**
```php
$this->logActivity($aktivitas, $modul, $keterangan);
```

**Method Helper:**
```php
$this->logLogin();                              // Log login
$this->logLogout();                             // Log logout
$this->logCreate($modul, $keterangan);          // Log create
$this->logUpdate($modul, $keterangan);          // Log update
$this->logDelete($modul, $keterangan);          // Log delete
$this->logView($modul, $keterangan);            // Log view
```

### Untuk Admin

#### 1. Mengakses Log Aktivitas
- Login sebagai admin
- Klik menu **"Log Aktivitas"** di navigation bar
- Atau akses: `http://localhost:8000/admin/log-aktivitas`

#### 2. Filter Log
- **Search Box**: Ketik kata kunci untuk mencari
- **Dropdown Modul**: Pilih modul tertentu
- **Tanggal Mulai/Selesai**: Pilih rentang tanggal
- Klik tombol **"🔍 Filter"**

#### 3. Reset Filter
Klik tombol **"🔄 Reset"** untuk menghapus semua filter

#### 4. Hapus Log
- **Hapus satu log**: Klik tombol "Hapus" di baris log
- **Hapus semua log**: Klik tombol **"🗑️ Hapus Semua Log"** (hanya admin)

## 📊 Struktur Database

### Tabel: `log_aktivitas`

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key ke users (nullable) |
| aktivitas | string | Deskripsi aktivitas |
| modul | string | Nama modul (nullable) |
| keterangan | text | Detail tambahan (nullable) |
| ip_address | string(45) | IP address user (nullable) |
| user_agent | text | Browser/device info (nullable) |
| created_at | timestamp | Waktu aktivitas |
| updated_at | timestamp | Waktu update |

## 🎨 Tampilan

### Badge Warna Modul
- **Auth**: Ungu (Purple)
- **Peminjaman**: Biru (Blue)
- **Alat**: Hijau (Green)
- **Kategori**: Kuning (Yellow)
- **User**: Merah (Red)
- **Lainnya**: Abu-abu (Gray)

## 🔒 Keamanan

1. **Hanya Admin**: Hanya user dengan role `admin` yang bisa mengakses log aktivitas
2. **Soft Logging**: Jika terjadi error saat logging, tidak akan mengganggu proses utama
3. **IP Tracking**: Setiap aktivitas tercatat IP address-nya
4. **User Agent**: Tercatat browser/device yang digunakan

## 📝 Contoh Implementasi

### Contoh 1: Log di AlatController
```php
public function store(Request $request)
{
    $alat = Alat::create($request->all());
    
    $this->logCreate('Alat', "Menambah alat: {$alat->nama_alat}");
    
    return redirect()->route('alat.index')
        ->with('success', 'Alat berhasil ditambahkan!');
}
```

### Contoh 2: Log di PeminjamanController
```php
public function updateStatus(Request $request, $id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    
    // Update status logic...
    
    $this->logActivity(
        "Perubahan status: {$oldStatus} → {$newStatus}",
        'Peminjaman',
        "User: {$user->name}, Alat: {$alat->nama_alat}"
    );
    
    return back()->with('success', 'Status berhasil diperbarui!');
}
```

## 🐛 Troubleshooting

### Log tidak tercatat
1. Pastikan migration sudah dijalankan
2. Pastikan trait `LogsActivity` sudah ditambahkan di controller
3. Cek apakah method logging dipanggil
4. Cek log error di `storage/logs/laravel.log`

### Error saat mengakses halaman log
1. Pastikan route sudah terdaftar
2. Pastikan user login sebagai admin
3. Clear cache: `php artisan cache:clear`

## 📚 File Terkait

- **Migration**: `database/migrations/2026_01_29_063854_create_log_aktivitas_table.php`
- **Model**: `app/Models/LogAktivitas.php`
- **Trait**: `app/Traits/LogsActivity.php`
- **Controller**: `app/Http/Controllers/LogAktivitasController.php`
- **View**: `resources/views/log-aktivitas/index.blade.php`
- **Routes**: `routes/web.php` (line 37-39)
- **Seeder**: `database/seeders/LogAktivitasSeeder.php`

## ✅ Checklist Implementasi

- [x] Migration tabel log_aktivitas
- [x] Model LogAktivitas
- [x] Trait LogsActivity
- [x] Controller LogAktivitasController
- [x] View index dengan filter & search
- [x] Routes untuk admin
- [x] Navigation menu link
- [x] Implementasi di AlatController
- [x] Implementasi di PeminjamanController
- [x] Seeder untuk testing
- [x] Dokumentasi lengkap

## 🎓 Tips untuk UKK

1. **Sederhana tapi Lengkap**: Kode dibuat sederhana agar mudah dipahami untuk ujian
2. **Reusable**: Gunakan trait agar bisa dipakai di semua controller
3. **User Friendly**: Interface mudah digunakan dengan filter dan search
4. **Professional**: Tampilan rapi dengan Tailwind CSS
5. **Secure**: Hanya admin yang bisa akses

---

**Dibuat untuk**: UKK Peminjaman 2025/2026  
**Versi**: 1.0  
**Tanggal**: 29 Januari 2026
