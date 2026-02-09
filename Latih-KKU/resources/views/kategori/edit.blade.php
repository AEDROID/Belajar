<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('kategori.update', $kategori->id)}}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col">
                    <label for="nama_kategori" >Nama kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" value="{{$kategori->nama_kategori}}">
                    </div>
                    <button type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
