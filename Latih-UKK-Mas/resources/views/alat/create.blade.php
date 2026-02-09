<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('alat.store')}}" method="POST" class="p-10">
                    @csrf
                    <div class="flex flex-col">
                    <label for="nama_alat">Nama alat</label>
                    <input type="text" name="nama_alat" id="nama_alat" placeholder="nama alat">
                    </div>

                     <div class="flex flex-col">
                    <label for="nama_kategori">Kategori</label>
                    <select name="kategori_id" id="kategori_id">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{$kategori->id}}">
                            {{$kategori->nama_kategori}}
                        </option>
                        @endforeach
                    </select>
                    </div>

                    <div class="flex flex-col">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi"></textarea>
                    </div>

                    <div class="flex flex-col">
                    <label for="nama_alat">Stok</label>
                    <input type="number" name="stok" id="stok" placeholder="stok alat">
                    </div>

                    <div class="flex flex-col">
                    <label for="denda_per_hari">Denda/Hari</label>
                    <input type="text" name="denda_per_hari" id="denda_per_hari" placeholder="denda/hari">
                    </div>
                    <button type="submit" class="bg-blue-600 w-full mt-10 p-3 text-gray-50">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
