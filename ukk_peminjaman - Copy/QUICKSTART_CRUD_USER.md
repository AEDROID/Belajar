# Quick Start - CRUD User

## 🚀 Cara Cepat Menggunakan Fitur CRUD User

### 1️⃣ Login sebagai Admin
```
Email: admin@example.com (sesuaikan dengan data di database)
Password: password (sesuaikan dengan data di database)
```

### 2️⃣ Akses Menu Users
- Setelah login, klik menu **"Users"** di navigation bar
- Atau akses langsung: `http://localhost/users`

### 3️⃣ Tambah User Baru
1. Klik tombol **"+ Tambah User"**
2. Isi form:
   - **Nama**: Contoh: "John Doe"
   - **Email**: Contoh: "john@example.com"
   - **Role**: Pilih salah satu (user/petugas/admin)
   - **Password**: Min 8 karakter
   - **Konfirmasi Password**: Sama dengan password
3. Klik **"Simpan User"**

### 4️⃣ Edit User
1. Klik tombol **"Edit"** pada user yang ingin diubah
2. Ubah data yang diperlukan
3. **Catatan**: Password bersifat opsional, kosongkan jika tidak ingin mengubah
4. Klik **"Update User"**

### 5️⃣ Hapus User
1. Klik tombol **"Hapus"** pada user yang ingin dihapus
2. Konfirmasi penghapusan
3. **Catatan**: Anda tidak bisa menghapus akun Anda sendiri

---

## ✅ Fitur Keamanan yang Sudah Diterapkan

### 🔒 Triple Layer Protection
1. **Middleware** - Route hanya bisa diakses oleh admin
2. **Controller** - Setiap method menggunakan `$this->authorize('Admin')`
3. **View** - Menggunakan `@can('Admin')` untuk kontrol tampilan

### 🛡️ Validasi & Proteksi
- ✅ Email harus unique
- ✅ Password minimal 8 karakter & harus di-confirm
- ✅ Password di-hash dengan bcrypt
- ✅ Admin tidak bisa menghapus akun sendiri
- ✅ Role hanya bisa: user, petugas, atau admin

---

## 🧪 Testing Checklist

### Test sebagai Admin ✅
- [ ] Login sebagai admin
- [ ] Akses halaman `/users` → Harus berhasil
- [ ] Tambah user baru → Harus berhasil
- [ ] Edit user → Harus berhasil
- [ ] Hapus user lain → Harus berhasil
- [ ] Coba hapus akun sendiri → Harus ditolak dengan pesan error

### Test sebagai Non-Admin ❌
- [ ] Login sebagai petugas atau user biasa
- [ ] Coba akses `/users` → Harus ditolak (403 Forbidden)
- [ ] Coba akses `/users/create` → Harus ditolak (403 Forbidden)

---

## 📁 File-file yang Dibuat

```
app/Http/Controllers/UserController.php          ← Controller CRUD
resources/views/users/index.blade.php            ← Halaman daftar user
resources/views/users/create.blade.php           ← Form tambah user
resources/views/users/edit.blade.php             ← Form edit user
routes/web.php                                   ← Route resource users
DOKUMENTASI_CRUD_USER.md                         ← Dokumentasi lengkap
```

---

## 🎨 Screenshot Fitur

### Halaman Daftar User
- Tabel dengan kolom: No, Nama, Email, Role, Terdaftar Sejak, Aksi
- Badge berwarna untuk role (Admin: purple, Petugas: green, User: gray)
- Badge "Anda" untuk user yang sedang login
- Tombol Edit (kuning) dan Hapus (merah)

### Form Tambah/Edit User
- Input field yang clean dan modern
- Validasi real-time
- Pesan error yang jelas
- Tombol Batal dan Simpan

---

## 🔧 Troubleshooting

### ❓ Tidak bisa akses halaman users?
**Solusi**: Pastikan Anda login sebagai admin (role = 'admin')

### ❓ Error "authorize() method not found"?
**Solusi**: Pastikan `Controller.php` sudah menggunakan trait `AuthorizesRequests`

### ❓ Password tidak ter-update saat edit?
**Solusi**: Pastikan menggunakan `Hash::make()` dan field password ada di `$fillable`

### ❓ Tidak ada menu Users di navigation?
**Solusi**: Menu Users hanya muncul untuk admin, pastikan Anda login sebagai admin

---

## 📝 Catatan Penting

1. **Fitur ini menggunakan `@can` directive** sesuai permintaan
2. **Hanya admin yang bisa akses** - sudah diproteksi di 3 level (middleware, controller, view)
3. **Password aman** - menggunakan bcrypt hashing
4. **UI responsive** - menggunakan Tailwind CSS
5. **Validasi ketat** - di backend untuk keamanan

---

## 🎯 Next Steps (Opsional)

Jika ingin menambahkan fitur tambahan:
- [ ] Export user ke Excel/PDF
- [ ] Filter & Search user
- [ ] Pagination untuk banyak data
- [ ] Soft delete dengan restore
- [ ] Activity log untuk perubahan user
- [ ] Email notification saat user dibuat

---

**Selamat! Fitur CRUD User sudah siap digunakan! 🎉**
