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
                <a href="{{route('user.create')}}" class="p-3 bg-blue-600 text-gray-50">+ Tambahkan Kategori</a>
               </div>
               <table class="w-full border-collapse border border-black">
                <thead>
                    <tr>
                        <td class="border border-black w-4">No</td>
                        <td class="border border-black w-4">Nama Kategori</td>
                        <td class="border border-black w-4">Email</td>
                        <td class="border border-black w-4">Role</td>
                        <td class="border border-black w-4">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="border border-black w-4">{{$loop->iteration}}</td>
                        <td class="border border-black w-4">{{$user->name}}</td>
                        <td class="border border-black w-4">{{$user->email}}</td>
                        <td class="border border-black w-4">{{$user->role}}</td>
                        <td class="border border-black w-4">
                            <a href="{{route('user.edit', $user->id)}}">Edit</a>
                            @if($user->id !== auth()->id())
                            <form action="{{route('user.destroy', $user->id)}}" onsubmit="return confirm('Apakah anda yaki ingin hapus?');" method="POST"> 
                                @csrf
                                @method('DELETE')
                                <button type="submit">
                                    Hapus
                                </button>
                            </form>
                            @endif
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
