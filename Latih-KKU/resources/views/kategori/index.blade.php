<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
               <div class="p-5 flex justify-end">
                <a href="{{route('kategori.create')}}" class="p-3 bg-blue-600 text-gray-50">+ Tambahkan Kategori</a>
               </div>
               <table class="w-full border-collapse border border-black">
                <thead>
                    <tr>
                        <td class="border border-black w-4">No</td>
                        <td class="border border-black w-4">Nama Kategori</td>
                        <td class="border border-black w-4">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $kategori)
                    <tr>
                        <td class="border border-black w-4">{{$loop->iteration}}</td>
                        <td class="border border-black w-4">{{$kategori->nama_kategori}}</td>
                        <td class="border border-black w-4">
                            <a href="{{route('kategori.edit', $kategori->id)}}">Edit</a>
                            <form action="{{route('kategori.destroy', $kategori->id)}}" onsubmit="return confirm('Apakah anda yaki ingin hapus?');" method="POST"> 
                                @csrf
                                @method('DELETE')
                                <button type="submit">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td  class="">Data Tidak Ada</td>
                    </tr>
                </tbody>
                    @endforelse
               </table>
            </div>
        </div>
    </div>
</x-app-layout>
