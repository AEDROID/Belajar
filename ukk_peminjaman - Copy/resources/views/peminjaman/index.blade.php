<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-4 flex justify-between">
                        <a href="{{ route('peminjaman.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Buat Peminjaman
                        </a>
                        @can('Petugas')
                         <a href="{{ route('peminjaman.print') }}" target="_blank" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cetak Laporan
                        </a>
                        @endcan
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jml</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rencana Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    @can('admin')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($peminjamans as $peminjaman)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $peminjaman->user->name  ??'User Deleted' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $peminjaman->alat->nama_alat ?? 'Alat Deleted' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $peminjaman->jumlah }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian_rencana)->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $peminjaman->status }}
                                        @can('Petugas')
                                        @if($peminjaman->status == 'menunggu')
                                            <div class="flex gap-1">
                                                <form action="{{ route('peminjaman.updateStatus', $peminjaman->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="dipinjam">
                                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs">Setujui</button>
                                                </form>
                                                <form action="{{ route('peminjaman.updateStatus', $peminjaman->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs">Tolak</button>
                                                </form>
                                            </div>
                                        @elseif($peminjaman->status == 'dipinjam' || $peminjaman->status == 'disetujui')
                                            <form action="{{ route('peminjaman.updateStatus', $peminjaman->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="dikembalikan">
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-xs">Kembalikan</button>
                                            </form>
                                        @elseif($peminjaman->status == 'dikembalikan' && $peminjaman->status_denda == 'terkena_denda')
                                            <form action="{{ route('peminjaman.updateStatus', $peminjaman->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status_denda" value="denda_lunas">
                                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs">Bayar Denda</button>
                                            </form>
                                        @else
                                            <div class="flex flex-col gap-1">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $peminjaman->status == 'ditolak' ? 'bg-red-100 text-red-800' : 
                                                       ($peminjaman->status == 'dikembalikan' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ ucfirst($peminjaman->status) }}
                                                </span>
                                                @if($peminjaman->status_denda == 'denda_lunas')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Lunas
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        @endcan
                                    </td>

                                    @can('Admin')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus peminjaman ini? Stok akan dikembalikan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
