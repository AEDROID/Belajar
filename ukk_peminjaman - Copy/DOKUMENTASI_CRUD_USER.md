# CRUD User - Dokumentasi

## Deskripsi
Fitur CRUD (Create, Read, Update, Delete) untuk manajemen user yang **hanya bisa diakses oleh Admin** menggunakan fitur `@can` Laravel.

## Fitur Utama

### 1. **Authorization dengan Gate**
- Menggunakan Gate `Admin` yang sudah didefinisikan di `AppServiceProvider`
- Setiap method di controller menggunakan `$this->authorize('Admin')`
- View menggunakan directive `@can('Admin')` untuk kontrol akses di tampilan

### 2. **Daftar User (Index)**
- Menampilkan semua user dalam tabel
- Informasi yang ditampilkan:
  - No urut
  - Nama (dengan badge "Anda" untuk user yang sedang login)
  - Email
  - Role (dengan badge berwarna)
  - Tanggal terdaftar
  - Aksi (Edit & Hapus)
- Tombol hapus disabled untuk user yang sedang login

### 3. **Tambah User (Create)**
- Form input:
  - Nama Lengkap (required)
  - Email (required, unique)
  - Role (required: user/petugas/admin)
  - Password (required, min 8 karakter)
  - Konfirmasi Password (required)
- Validasi lengkap di backend

### 4. **Edit User (Update)**
- Form edit dengan data user yang sudah ada
- Password bersifat opsional (hanya diupdate jika diisi)
- Validasi email unique kecuali untuk user yang sedang diedit
- Tidak bisa menghapus user sendiri

### 5. **Hapus User (Delete)**
- Konfirmasi sebelum menghapus
- Proteksi: Admin tidak bisa menghapus akun sendiri
- Soft delete bisa ditambahkan jika diperlukan

## File yang Dibuat/Dimodifikasi

### 1. Controller
```
app/Http/Controllers/UserController.php
```
- Berisi semua logic CRUD
- Menggunakan authorization di setiap method
- Validasi input yang ketat

### 2. Views
```
resources/views/users/index.blade.php   - Daftar user
resources/views/users/create.blade.php  - Form tambah user
resources/views/users/edit.blade.php    - Form edit user
```
- Menggunakan Tailwind CSS untuk styling
- Responsive design
- Menggunakan `@can('Admin')` untuk kontrol akses

### 3. Routes
```
routes/web.php
```
Ditambahkan route resource di dalam middleware admin:
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // ... routes lain
    Route::resource('users', App\Http\Controllers\UserController::class);
});
```

### 4. Base Controller
```
app/Http/Controllers/Controller.php
```
Ditambahkan trait `AuthorizesRequests` untuk enable method `authorize()`.

## Cara Menggunakan

### 1. Akses Halaman User Management
- Login sebagai **admin**
- Akses URL: `/users`
- Atau tambahkan link di navigation menu

### 2. Menambah User Baru
1. Klik tombol "**+ Tambah User**"
2. Isi form dengan lengkap
3. Pilih role yang sesuai
4. Klik "**Simpan User**"

### 3. Mengedit User
1. Klik tombol "**Edit**" pada user yang ingin diubah
2. Ubah data yang diperlukan
3. Untuk mengubah password, isi field password baru
4. Klik "**Update User**"

### 4. Menghapus User
1. Klik tombol "**Hapus**" pada user yang ingin dihapus
2. Konfirmasi penghapusan
3. User akan dihapus dari database

## Keamanan

### 1. **Middleware Level**
Route dilindungi dengan middleware:
- `auth` - User harus login
- `role:admin` - Hanya admin yang bisa akses

### 2. **Controller Level**
Setiap method menggunakan:
```php
$this->authorize('Admin');
```

### 3. **View Level**
Konten dilindungi dengan:
```blade
@can('Admin')
    <!-- Konten hanya untuk admin -->
@else
    <!-- Pesan akses ditolak -->
@endcan
```

### 4. **Validasi Input**
- Email harus unique
- Password minimal 8 karakter
- Password confirmation harus match
- Role harus salah satu dari: user, petugas, admin

### 5. **Business Logic Protection**
- Admin tidak bisa menghapus akun sendiri
- Password di-hash menggunakan `Hash::make()`

## Testing

### Test sebagai Admin
1. Login sebagai admin
2. Akses `/users` - ✅ Harus bisa akses
3. Tambah user baru - ✅ Harus berhasil
4. Edit user - ✅ Harus berhasil
5. Hapus user lain - ✅ Harus berhasil
6. Coba hapus akun sendiri - ❌ Harus ditolak

### Test sebagai Non-Admin (Petugas/User)
1. Login sebagai petugas atau user
2. Akses `/users` - ❌ Harus ditolak (403 Forbidden)
3. Coba akses langsung `/users/create` - ❌ Harus ditolak

## Customization

### Menambahkan Field Baru
1. Tambahkan kolom di migration
2. Update `$fillable` di model User
3. Tambahkan field di form create & edit
4. Update validasi di controller

### Mengubah Styling
- Edit file blade di `resources/views/users/`
- Gunakan Tailwind CSS classes

### Menambahkan Fitur Export
Bisa ditambahkan tombol export di `index.blade.php`:
```blade
<a href="{{ route('users.export') }}" class="...">
    Export Excel
</a>
```

## Troubleshooting

### Error 403 Forbidden
- Pastikan user yang login memiliki role 'admin'
- Cek Gate definition di `AppServiceProvider`

### Error "authorize() method not found"
- Pastikan Controller.php sudah menggunakan trait `AuthorizesRequests`

### Password tidak ter-update
- Pastikan menggunakan `Hash::make()` untuk password
- Cek apakah field password ada di `$fillable` model User

## Kesimpulan

Fitur CRUD User ini sudah dilengkapi dengan:
- ✅ Authorization multi-level (middleware, controller, view)
- ✅ Validasi input yang ketat
- ✅ UI yang responsive dan user-friendly
- ✅ Proteksi terhadap self-deletion
- ✅ Password hashing yang aman
- ✅ Menggunakan `@can` directive sesuai permintaan

Fitur ini siap digunakan untuk manajemen user dalam aplikasi peminjaman alat.
