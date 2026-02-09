<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 flex justify-end">
                    <a href="{{route('alat.create')}}" class="bg-blue-600 text-gray-100 p-3">+ Tambahkan Alat</a>
                </div>
                @if(session('success'))
                <p> {{session('success')}}</p>
                @endif
                <table class="w-full m-6 border-collapse border">
                    <thead>
                        <tr>
                            <td class="border border-gray-300 p-2 w-4">No</td>
                            <td class="border border-gray-300 p-2">Nama Alat</td>
                            <td class="border border-gray-300 p-2">Kategori</td>
                            <td class="border border-gray-300 p-2">Deskripsi</td>
                            <td class="border border-gray-300 p-2">Stok</td>
                            <td class="border border-gray-300 p-2">Denda/hari</td>
                            <td class="border border-gray-300 p-2">Aksi</td>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($alats as $alat)
                        <tr>
                            <td class="border border-gray-300 p-2">
                               {{$loop->iteration}} 
                            </td>
                            <td class="border border-gray-300 p-2">
                               {{$alat->nama_alat}} 
                            </td>
                            <td class="border border-gray-300 p-2">
                                {{$alat->kategori->nama_kategori ?? '-'}} 
                            </td>
                            <td class="border border-gray-300 p-2">
                                {{$alat->deskripsi}}
                            </td>
                             <td class="border border-gray-300 p-2">
                                {{$alat->stok}}
                            </td>
                             <td class="border border-gray-300 p-2">
                                Rp {{number_format ($alat->denda_per_hari, 0, ',', '.')}}
                            </td>
                            <td class="border border-gray-300 p-2">
                                <a href="{{route('alat.edit', $alat->id)}}">Edit</a>
                                <form action="{{ route('alat.destroy', $alat->id) }}" method="post"  onsubmit="return confirm('apakah anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr class="w-full">
                            <td class="justify-center flex w-full">
                                data tidak ada
                            </td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
