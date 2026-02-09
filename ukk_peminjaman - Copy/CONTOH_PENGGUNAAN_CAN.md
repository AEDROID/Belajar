# Contoh Penggunaan @can di CRUD User

## 📚 Penjelasan Fitur @can

`@can` adalah Blade directive di Laravel untuk melakukan authorization check di level view/template. Ini adalah bagian dari Laravel's Authorization system yang memungkinkan kita mengontrol akses berdasarkan Gate atau Policy.

### Keuntungan menggunakan @can:
1. ✅ **Clean Code** - Lebih mudah dibaca daripada `@if(auth()->user()->role == 'admin')`
2. ✅ **Centralized Logic** - Authorization logic terpusat di `AppServiceProvider`
3. ✅ **Reusable** - Bisa digunakan di berbagai tempat
4. ✅ **Secure** - Lebih aman karena logic tidak tersebar

---

## 🎯 Implementasi @can di Project Ini

### 1. Definisi Gate di AppServiceProvider

**File**: `app/Providers/AppServiceProvider.php`

```php
use Illuminate\Support\Facades\Gate;

public function boot(): void
{
    Gate::define('Admin', function($user){
        return $user->role == 'admin';
    });

    Gate::define('Petugas', function($user){
        return $user->role == 'petugas';
    });

    Gate::define('User', function($user){
        return $user->role == 'user';
    });
}
```

**Penjelasan**:
- `Gate::define('Admin', ...)` mendefinisikan gate bernama 'Admin'
- Function menerima parameter `$user` (user yang sedang login)
- Return `true` jika user memiliki role 'admin', `false` jika tidak

---

### 2. Penggunaan @can di Views

#### A. Di Navigation Menu

**File**: `resources/views/layouts/navigation.blade.php`

```blade
@can('Admin')
    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
        {{ __('Users') }}
    </x-nav-link>
    <x-nav-link :href="route('kategori.index')" :active="request()->routeIs('kategori.index')">
        {{ __('Kategori') }}
    </x-nav-link>
    <x-nav-link :href="route('alat.index')" :active="request()->routeIs('alat.index')">
        {{ __('Alat') }}
    </x-nav-link>
@endcan
```

**Hasil**: Menu Users, Kategori, Alat hanya muncul untuk admin.

---

#### B. Di Halaman Index Users

**File**: `resources/views/users/index.blade.php`

```blade
@can('Admin')
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium text-gray-900">Kelola User</h3>
        <a href="{{ route('users.create') }}" class="...">
            + Tambah User
        </a>
    </div>

    <!-- Tabel daftar user -->
    <table>
        <!-- ... -->
    </table>
@else
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
        <p class="font-bold">Akses Ditolak!</p>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    </div>
@endcan
```

**Hasil**: 
- Jika admin → Tampilkan tabel dan tombol tambah user
- Jika bukan admin → Tampilkan pesan "Akses Ditolak"

---

#### C. Di Halaman Create User

**File**: `resources/views/users/create.blade.php`

```blade
@can('Admin')
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <!-- Form fields -->
        <input type="text" name="name" ...>
        <input type="email" name="email" ...>
        <!-- ... -->
        <button type="submit">Simpan User</button>
    </form>
@else
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
        <p class="font-bold">Akses Ditolak!</p>
        <p>Hanya admin yang dapat mengelola user.</p>
    </div>
@endcan
```

**Hasil**: Form hanya muncul untuk admin, non-admin melihat pesan error.

---

#### D. Di Halaman Edit User

**File**: `resources/views/users/edit.blade.php`

```blade
@can('Admin')
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Form fields dengan data user -->
        <input type="text" name="name" value="{{ $user->name }}" ...>
        <!-- ... -->
        <button type="submit">Update User</button>
    </form>
@else
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
        <p class="font-bold">Akses Ditolak!</p>
        <p>Hanya admin yang dapat mengelola user.</p>
    </div>
@endcan
```

**Hasil**: Form edit hanya muncul untuk admin.

---

### 3. Penggunaan authorize() di Controller

**File**: `app/Http/Controllers/UserController.php`

```php
public function index()
{
    $this->authorize('Admin'); // Throw 403 jika bukan admin
    
    $users = User::orderBy('created_at', 'desc')->get();
    return view('users.index', compact('users'));
}

public function create()
{
    $this->authorize('Admin');
    return view('users.create');
}

public function store(Request $request)
{
    $this->authorize('Admin');
    // ... validation & save
}

public function edit(string $id)
{
    $this->authorize('Admin');
    // ... load user & show form
}

public function update(Request $request, string $id)
{
    $this->authorize('Admin');
    // ... validation & update
}

public function destroy(string $id)
{
    $this->authorize('Admin');
    // ... delete user
}
```

**Penjelasan**:
- `$this->authorize('Admin')` akan throw `AuthorizationException` (403) jika user bukan admin
- Ini adalah **double protection** selain middleware di routes

---

## 🔐 Triple Layer Protection

### Layer 1: Route Middleware
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});
```
**Fungsi**: Cegah akses route jika bukan admin

### Layer 2: Controller Authorization
```php
$this->authorize('Admin');
```
**Fungsi**: Double check di controller, throw 403 jika bukan admin

### Layer 3: View @can Directive
```blade
@can('Admin')
    <!-- Konten admin -->
@else
    <!-- Pesan error -->
@endcan
```
**Fungsi**: Kontrol tampilan, user-friendly error message

---

## 🎨 Variasi Penggunaan @can

### 1. @can dengan @else
```blade
@can('Admin')
    <button>Edit</button>
@else
    <span>Tidak ada akses</span>
@endcan
```

### 2. @can dengan @elsecan
```blade
@can('Admin')
    <button>Kelola User</button>
@elsecan('Petugas')
    <button>Lihat User</button>
@else
    <span>Tidak ada akses</span>
@endcan
```

### 3. @cannot (kebalikan dari @can)
```blade
@cannot('Admin')
    <p>Anda bukan admin</p>
@endcannot
```

### 4. @canany (salah satu dari beberapa gate)
```blade
@canany(['Admin', 'Petugas'])
    <button>Akses Peminjaman</button>
@endcanany
```

---

## 📝 Contoh Kasus Lain

### Kontrol Tombol Edit/Hapus
```blade
<table>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>
                @can('Admin')
                    <a href="{{ route('users.edit', $user->id) }}">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                @endcan
            </td>
        </tr>
    @endforeach
</table>
```

### Kontrol Section di Dashboard
```blade
<div class="dashboard">
    @can('Admin')
        <div class="admin-panel">
            <h2>Admin Panel</h2>
            <!-- Admin-only content -->
        </div>
    @endcan
    
    @can('Petugas')
        <div class="petugas-panel">
            <h2>Petugas Panel</h2>
            <!-- Petugas-only content -->
        </div>
    @endcan
    
    <div class="user-panel">
        <h2>User Panel</h2>
        <!-- Content untuk semua user -->
    </div>
</div>
```

---

## ✅ Kesimpulan

**Fitur @can di CRUD User ini**:
1. ✅ Menggunakan Gate 'Admin' yang didefinisikan di `AppServiceProvider`
2. ✅ Digunakan di semua view (index, create, edit)
3. ✅ Dikombinasikan dengan `authorize()` di controller
4. ✅ Dikombinasikan dengan middleware di routes
5. ✅ Memberikan user experience yang baik dengan pesan error yang jelas

**Best Practice**:
- Selalu gunakan triple layer protection (middleware + controller + view)
- Definisikan Gate di satu tempat (AppServiceProvider)
- Gunakan nama Gate yang jelas dan konsisten
- Berikan pesan error yang informatif untuk user

---

**Dengan implementasi ini, CRUD User sudah menggunakan fitur @can secara optimal! 🎉**
