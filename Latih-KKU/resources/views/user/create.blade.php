<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('user.store')}}" method="POST" class="p-6">
                    @csrf

                    <div class="flex flex-col">
                    <label for="nama_kategori" >Username</label>
                    <input type="text" name="name" id="name" placeholder="Ketik Nama Anda">
                    </div>

                    <div class="flex flex-col">
                    <label for="nama_kategori" >Email</label>
                    <input type="text" name="email" id="email" placeholder="Contoh : admin@gmail.com">
                    </div>

                    <div class="flex flex-col">
                    <label for="nama_kategori" >Role</label>
                    <select name="role" id="role">
                        <option value="">Pilih Role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                    </select>
                    </div>

                    <div class="flex flex-col">
                    <label for="nama_kategori" >Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukan Password Minimal 8 Karakter">
                    </div>

                     <div class="flex flex-col">
                    <label for="nama_kategori" ></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Masukan Ulang Password">
                    </div>
                    <button type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
