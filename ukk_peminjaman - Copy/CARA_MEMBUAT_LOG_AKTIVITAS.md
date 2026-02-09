# 📝 Cara Membuat Fitur Log Aktivitas - Step by Step

## 🎯 Tujuan
Membuat fitur log aktivitas yang mencatat semua aktivitas user dalam aplikasi peminjaman secara otomatis.

---

## 📋 Langkah-Langkah Pembuatan

### **STEP 1: Membuat Migration untuk Tabel Log Aktivitas**

#### 1.1 Generate Migration
```bash
php artisan make:migration create_log_aktivitas_table
```

#### 1.2 Edit File Migration
Buka file: `database/migrations/xxxx_xx_xx_xxxxxx_create_log_aktivitas_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('aktivitas'); // Deskripsi aktivitas
            $table->string('modul')->nullable(); // Modul terkait
            $table->text('keterangan')->nullable(); // Detail tambahan
            $table->string('ip_address', 45)->nullable(); // IP Address
            $table->text('user_agent')->nullable(); // Browser/Device info
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
```

#### 1.3 Jalankan Migration
```bash
php artisan migrate
```

**Penjelasan Kolom:**
- `user_id`: ID user yang melakukan aktivitas (nullable jika user dihapus)
- `aktivitas`: Deskripsi singkat aktivitas (contoh: "Login ke sistem")
- `modul`: Nama modul terkait (contoh: "Auth", "Peminjaman", "Alat")
- `keterangan`: Detail tambahan tentang aktivitas
- `ip_address`: IP address user saat melakukan aktivitas
- `user_agent`: Informasi browser/device yang digunakan

---

### **STEP 2: Membuat Model LogAktivitas**

#### 2.1 Generate Model
```bash
php artisan make:model LogAktivitas
```

#### 2.2 Edit Model
Buka file: `app/Models/LogAktivitas.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aktivitas',
        'modul',
        'keterangan',
        'ip_address',
        'user_agent',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

**Penjelasan:**
- `$table`: Nama tabel di database
- `$fillable`: Kolom yang boleh diisi mass assignment
- `user()`: Relasi belongsTo ke model User

---

### **STEP 3: Membuat Trait LogsActivity (Helper)**

#### 3.1 Buat Folder dan File
Buat file: `app/Traits/LogsActivity.php`

```php
<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Catat aktivitas ke database
     */
    protected function logActivity($aktivitas, $modul = null, $keterangan = null)
    {
        try {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => $aktivitas,
                'modul' => $modul,
                'keterangan' => $keterangan,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silent fail - jangan sampai log error mengganggu proses utama
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Helper methods untuk aktivitas umum
     */
    protected function logLogin()
    {
        $this->logActivity('Login ke sistem', 'Auth', 'User berhasil login');
    }

    protected function logLogout()
    {
        $this->logActivity('Logout dari sistem', 'Auth', 'User berhasil logout');
    }

    protected function logCreate($modul, $keterangan = null)
    {
        $this->logActivity("Menambah data {$modul}", $modul, $keterangan);
    }

    protected function logUpdate($modul, $keterangan = null)
    {
        $this->logActivity("Mengubah data {$modul}", $modul, $keterangan);
    }

    protected function logDelete($modul, $keterangan = null)
    {
        $this->logActivity("Menghapus data {$modul}", $modul, $keterangan);
    }

    protected function logView($modul, $keterangan = null)
    {
        $this->logActivity("Melihat data {$modul}", $modul, $keterangan);
    }
}
```

**Penjelasan:**
- **Trait**: Kode yang bisa digunakan ulang di banyak controller
- **logActivity()**: Method utama untuk mencatat log
- **Helper methods**: Method-method shortcut untuk aktivitas umum
- **Try-catch**: Agar error logging tidak mengganggu proses utama

---

### **STEP 4: Membuat Controller LogAktivitasController**

#### 4.1 Generate Controller
```bash
php artisan make:controller LogAktivitasController --resource
```

#### 4.2 Edit Controller
Buka file: `app/Http/Controllers/LogAktivitasController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use App\Traits\LogsActivity;

class LogAktivitasController extends Controller
{
    use LogsActivity;

    /**
     * Tampilkan daftar log aktivitas
     */
    public function index(Request $request)
    {
        // Log aktivitas melihat halaman log
        $this->logView('Log Aktivitas', 'Mengakses halaman log aktivitas');

        $query = LogAktivitas::with('user')->orderBy('created_at', 'desc');

        // Filter berdasarkan modul
        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('aktivitas', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(20);

        // Ambil daftar modul unik untuk filter
        $moduls = LogAktivitas::distinct()->pluck('modul')->filter();

        return view('log-aktivitas.index', compact('logs', 'moduls'));
    }

    /**
     * Hapus satu log
     */
    public function destroy(string $id)
    {
        $log = LogAktivitas::findOrFail($id);
        
        $this->logDelete('Log Aktivitas', "Menghapus log: {$log->aktivitas}");
        
        $log->delete();

        return redirect()->route('log-aktivitas.index')
            ->with('success', 'Log aktivitas berhasil dihapus');
    }

    /**
     * Hapus semua log (untuk admin)
     */
    public function clearAll()
    {
        $this->logActivity('Menghapus semua log aktivitas', 'Log Aktivitas', 'Semua log dibersihkan');
        
        LogAktivitas::truncate();

        return redirect()->route('log-aktivitas.index')
            ->with('success', 'Semua log aktivitas berhasil dihapus');
    }
}
```

**Penjelasan:**
- **index()**: Menampilkan daftar log dengan filter dan search
- **destroy()**: Menghapus satu log
- **clearAll()**: Menghapus semua log (hanya admin)

---

### **STEP 5: Membuat View untuk Log Aktivitas**

#### 5.1 Buat Folder dan File
Buat file: `resources/views/log-aktivitas/index.blade.php`

```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Aktivitas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Filter dan Search -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('log-aktivitas.index') }}" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <!-- Search -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                           placeholder="Cari aktivitas, user..." 
                                           class="w-full rounded-md border-gray-300 shadow-sm">
                                </div>

                                <!-- Filter Modul -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Modul</label>
                                    <select name="modul" class="w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Semua Modul</option>
                                        @foreach($moduls as $modul)
                                            <option value="{{ $modul }}" {{ request('modul') == $modul ? 'selected' : '' }}>
                                                {{ $modul }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tanggal Mulai -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm">
                                </div>

                                <!-- Tanggal Selesai -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    🔍 Filter
                                </button>
                                <a href="{{ route('log-aktivitas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    🔄 Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Tabel Log -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($logs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $log->user->name ?? 'System' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ $log->modul ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $log->aktivitas }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $log->keterangan ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada log aktivitas
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

**Penjelasan:**
- Form filter dengan search, modul, dan tanggal
- Tabel untuk menampilkan log
- Pagination untuk navigasi halaman

---

### **STEP 6: Menambahkan Routes**

#### 6.1 Edit File Routes
Buka file: `routes/web.php`

Tambahkan di dalam group middleware admin:

```php
// Route Khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // ... route lainnya ...

    // Log Aktivitas
    Route::delete('/admin/log-aktivitas/clear', [App\Http\Controllers\LogAktivitasController::class, 'clearAll'])->name('log-aktivitas.clear');
    Route::resource('/admin/log-aktivitas', App\Http\Controllers\LogAktivitasController::class)->only(['index', 'destroy']);
});
```

**Penjelasan:**
- Route `clear` untuk hapus semua log
- Resource route `index` dan `destroy` untuk CRUD dasar

---

### **STEP 7: Menambahkan Menu Navigation**

#### 7.1 Edit Navigation
Buka file: `resources/views/layouts/navigation.blade.php`

Tambahkan menu link di dalam section admin:

```blade
@can('Admin')
    <!-- Menu lainnya -->
    
    <x-nav-link :href="route('log-aktivitas.index')" :active="request()->routeIs('log-aktivitas.*')">
        {{ __('Log Aktivitas') }}
    </x-nav-link>
@endcan
```

---

### **STEP 8: Implementasi di Controller Lain**

#### 8.1 Contoh: AlatController

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alat;
use App\Traits\LogsActivity; // Import trait

class AlatController extends Controller
{
    use LogsActivity; // Gunakan trait

    public function index()
    {
        // Log saat melihat halaman
        $this->logView('Alat', 'Mengakses halaman daftar alat');
        
        $alats = Alat::with('kategori')->get();
        return view('admin.alat.index', compact('alats'));
    }

    public function store(Request $request)
    {
        $request->validate([...]);
        
        $alat = Alat::create($request->all());
        
        // Log saat menambah data
        $this->logCreate('Alat', "Menambah alat: {$alat->nama_alat}");

        return redirect()->route('alat.index')
            ->with('success', 'Alat berhasil ditambahkan!');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([...]);
        
        $alat = Alat::findOrFail($id);
        $alat->update($request->all());
        
        // Log saat mengubah data
        $this->logUpdate('Alat', "Mengubah alat: {$alat->nama_alat}");

        return redirect()->route('alat.index')
            ->with('success', 'Alat berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $alat = Alat::findOrFail($id);
        $namaAlat = $alat->nama_alat;
        $alat->delete();
        
        // Log saat menghapus data
        $this->logDelete('Alat', "Menghapus alat: {$namaAlat}");

        return redirect()->route('alat.index')
            ->with('success', 'Alat berhasil dihapus!');
    }
}
```

#### 8.2 Contoh: PeminjamanController

```php
use App\Traits\LogsActivity;

class PeminjamanController extends Controller
{
    use LogsActivity;

    public function store(Request $request)
    {
        // Logic peminjaman...
        $peminjaman = Peminjaman::create([...]);
        
        $user = User::find($request->user_id);
        $this->logCreate('Peminjaman', 
            "Membuat peminjaman untuk {$user->name}, alat: {$alat->nama_alat}"
        );

        return redirect()->route('peminjaman.index');
    }

    public function updateStatus(Request $request, $id)
    {
        // Logic update status...
        
        $this->logActivity(
            "Perubahan status: {$oldStatus} → {$newStatus}",
            'Peminjaman',
            "User: {$user->name}, Alat: {$alat->nama_alat}"
        );

        return back()->with('success', 'Status berhasil diperbarui!');
    }
}
```

---

### **STEP 9: Testing (Optional)**

#### 9.1 Buat Seeder
Buat file: `database/seeders/LogAktivitasSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogAktivitas;
use App\Models\User;

class LogAktivitasSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        
        $logs = [
            [
                'user_id' => $admin->id,
                'aktivitas' => 'Login ke sistem',
                'modul' => 'Auth',
                'keterangan' => 'User berhasil login',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subDays(2),
            ],
            // ... data lainnya
        ];

        foreach ($logs as $log) {
            LogAktivitas::create($log);
        }
    }
}
```

#### 9.2 Jalankan Seeder
```bash
php artisan db:seed --class=LogAktivitasSeeder
```

---

## ✅ Checklist Implementasi

Pastikan semua langkah berikut sudah dilakukan:

- [ ] **STEP 1**: Migration dibuat dan dijalankan
- [ ] **STEP 2**: Model LogAktivitas dibuat dengan relasi
- [ ] **STEP 3**: Trait LogsActivity dibuat
- [ ] **STEP 4**: Controller LogAktivitasController dibuat
- [ ] **STEP 5**: View index.blade.php dibuat
- [ ] **STEP 6**: Routes ditambahkan
- [ ] **STEP 7**: Menu navigation ditambahkan
- [ ] **STEP 8**: Trait diimplementasikan di controller lain
- [ ] **STEP 9**: Testing dengan seeder (optional)

---

## 🧪 Cara Testing

### 1. Akses Halaman Log
```
http://localhost:8000/admin/log-aktivitas
```

### 2. Test CRUD di Modul Lain
- Tambah/Edit/Hapus data Alat
- Tambah/Edit/Hapus data Peminjaman
- Cek apakah log tercatat di halaman Log Aktivitas

### 3. Test Filter
- Coba filter berdasarkan modul
- Coba search berdasarkan kata kunci
- Coba filter berdasarkan tanggal

---

## 🎓 Tips untuk UKK

1. **Pahami Konsep Trait**: Trait adalah cara untuk reuse code di PHP
2. **Relasi Database**: Pahami relasi belongsTo antara LogAktivitas dan User
3. **Query Builder**: Pahami penggunaan `where`, `orWhere`, `whereHas` untuk filter
4. **Blade Template**: Pahami penggunaan `@foreach`, `@forelse`, `@empty`
5. **Middleware**: Pahami penggunaan middleware untuk proteksi route

---

## 📚 Referensi

- Laravel Documentation: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com
- PHP Traits: https://www.php.net/manual/en/language.oop5.traits.php

---

**Selamat Belajar! 🚀**

Jika ada yang kurang jelas, silakan tanya atau cek dokumentasi lengkap di `LOG_AKTIVITAS_DOKUMENTASI.md`
