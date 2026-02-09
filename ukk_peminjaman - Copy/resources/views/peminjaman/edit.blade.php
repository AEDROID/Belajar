
<x-app-layout>
     @can('User')
        <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hanya Bisa Diakses Oleh admin
        </h2>
       
    </x-slot>
        @endcan
    @can('admin')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Peminjaman') }}
        </h2>
       
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 bg-gray-100 p-3 rounded">
                            <p><strong>Alat:</strong> {{ $peminjaman->alat->nama_alat ?? '-' }}</p>
                            <p><strong>Jumlah:</strong> {{ $peminjaman->jumlah }}</p>
                            <p class="text-sm text-gray-500">*Item dan jumlah tidak dapat diubah di sini. Hapus dan buat baru jika salah input.</p>
                        </div>

                        <!-- User -->
                        <div class="mb-4">
                            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Peminjam:</label>
                            <select name="user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $peminjaman->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="tanggal_peminjaman" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Peminjaman:</label>
                                <input type="date" name="tanggal_peminjaman" value="{{ $peminjaman->tanggal_peminjaman }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                            <div>
                                <label for="tanggal_pengembalian_rencana" class="block text-gray-700 text-sm font-bold mb-2">Rencana Pengembalian:</label>
                                <input type="date" name="tanggal_pengembalian_rencana" value="{{ $peminjaman->tanggal_pengembalian_rencana }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                            <div>
                                <label for="tanggal_pengembalian_aktual" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Kembali (Aktual):</label>
                                <input type="date" name="tanggal_pengembalian_aktual" value="{{ $peminjaman->tanggal_pengembalian_aktual }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                        </div>

                        <!-- Status & Denda -->
                         <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    @foreach(['menunggu','disetujui','dipinjam','ditolak','dikembalikan'] as $s)
                                        <option value="{{ $s }}" {{ $peminjaman->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status_denda" class="block text-gray-700 text-sm font-bold mb-2">Status Denda (Otomatis):</label>
                                <input type="text" value="{{ ucfirst(str_replace('_', ' ', $peminjaman->status_denda)) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline" readonly>
                            </div>
                            <div>
                                <label for="denda" class="block text-gray-700 text-sm font-bold mb-2">Denda (Rp) - Otomatis:</label>
                                <input type="number" value="{{ $peminjaman->denda }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline" readonly>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Simpan & Hitung Denda
                            </button>
                            <a href="{{ route('peminjaman.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @endcan
</x-app-layout>