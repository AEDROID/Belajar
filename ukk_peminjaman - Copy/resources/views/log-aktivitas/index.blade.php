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
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Filter Modul -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Modul</label>
                                    <select name="modul" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Tanggal Selesai -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Filter
                                </button>
                                <a href="{{ route('log-aktivitas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Reset
                                </a>
                                @if(auth()->user()->role === 'admin')
                                <form action="{{ route('log-aktivitas.clear') }}" method="POST" class="inline-block" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA log aktivitas?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Hapus Semua Log
                                    </button>
                                </form>
                                @endif
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Informasi Total -->
                    <div class="mb-4 text-sm text-gray-600">
                        Total: <strong>{{ $logs->total() }}</strong> log aktivitas
                    </div>

                    <!-- Tabel Log -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Modul
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aktivitas
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        IP Address
                                    </th>
                                    @if(auth()->user()->role === 'admin')
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="text-gray-900">{{ $log->created_at->format('d/m/Y') }}</div>
                                        <div class="text-gray-500 text-xs">{{ $log->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="text-gray-900 font-medium">{{ $log->user->name ?? 'System' }}</div>
                                        <div class="text-gray-500 text-xs">{{ $log->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($log->modul)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $log->modul === 'Auth' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $log->modul === 'Peminjaman' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $log->modul === 'Alat' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $log->modul === 'Kategori' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $log->modul === 'User' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ !in_array($log->modul, ['Auth', 'Peminjaman', 'Alat', 'Kategori', 'User']) ? 'bg-gray-100 text-gray-800' : '' }}
                                            ">
                                                {{ $log->modul }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $log->aktivitas }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ Str::limit($log->keterangan ?? '-', 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $log->ip_address ?? '-' }}
                                    </td>
                                    @if(auth()->user()->role === 'admin')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('log-aktivitas.destroy', $log->id) }}" method="POST" class="inline-block" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus log ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
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
