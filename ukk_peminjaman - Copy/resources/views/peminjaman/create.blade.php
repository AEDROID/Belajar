<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('error'))
                        <div class="mb-4 text-red-600 font-bold">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">Whoops! Something went wrong.</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf

                        <!-- User -->
                        <div class="mb-4">
                            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Peminjam:</label>
                            <select name="user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">-- Pilih Peminjam --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                         <!-- Alat -->
                         <div class="mb-4">
                            <label for="alat_id" class="block text-gray-700 text-sm font-bold mb-2">Alat yang Dipinjam:</label>
                            <select name="alat_id" id="alat_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">-- Pilih Alat --</option>
                                @foreach($alats as $alat)
                                    <option value="{{ $alat->id }}" {{ old('alat_id') == $alat->id ? 'selected' : '' }}>
                                        {{ $alat->nama_alat }} (Stok: {{ $alat->stok }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jumlah -->
                        <div class="mb-4">
                            <label for="jumlah" class="block text-gray-700 text-sm font-bold mb-2">Jumlah:</label>
                            <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 1) }}" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="tanggal_peminjaman" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Peminjaman:</label>
                                <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" value="{{ old('tanggal_peminjaman', date('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                            <div>
                                <label for="tanggal_pengembalian_rencana" class="block text-gray-700 text-sm font-bold mb-2">Rencana Pengembalian:</label>
                                <input type="date" name="tanggal_pengembalian_rencana" id="tanggal_pengembalian_rencana" value="{{ old('tanggal_pengembalian_rencana', date('Y-m-d', strtotime('+1 day'))) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                        </div>



                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Simpan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
