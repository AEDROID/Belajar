<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('user.store')}}" method="POST" class="p-10 gap-2">
                    @csrf
                    <div class="flex flex-col">
                    <label for="name">Nama</label>
                    <input type="text" name="name" id="name" placeholder="Masukkan Nama Anda" required>
                    @error('name')
                    <p>{{$message}}</p>
                    @enderror
                    </div>

                    <div class="flex flex-col pt-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Contoh : user@gmail.com" required>
                    @error('email')
                    <p>{{$message}}</p>
                    @enderror
                    </div>

                    <div class="flex flex-col pt-3">
                     <label for="email">Role</label>   
                    <select name="role" id="role" required>
                        <option value="">Pilih Role</option>
                        <option value="user" {{old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="petugas" {{old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="admin" {{old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                    <p>{{$message}}</p>
                    @enderror
                    </div>

                    <div class="flex flex-col pt-3">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Minimal 8 Karakter" required>
                    @error('password')
                    <p>{{$message}}</p>
                    @enderror
                    </div>

                    <div class="flex flex-col pt-3">
                    <label for="password">Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Password" required>
                    @error('password')
                    <p>{{$message}}</p>
                    @enderror
                    </div>


                    <button type="submit" class="bg-blue-600 w-full mt-10 p-3 text-gray-50">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
