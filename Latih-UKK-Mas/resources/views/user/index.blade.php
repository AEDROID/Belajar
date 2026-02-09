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
                    <a href="{{route('user.create')}}" class="bg-blue-600 text-gray-100 p-3">+ Tambahkan User</a>
                </div>
                @if(session('success'))
                <p> {{session('success')}}</p>
                @endif
                <table class="w-full m-6 border-collapse border">
                    <thead>
                        <tr>
                            <td class="border border-gray-300 p-2 w-4">No</td>
                            <td class="border border-gray-300 p-2">Nama</td>
                            <td class="border border-gray-300 p-2">Email</td>
                            <td class="border border-gray-300 p-2">Role</td>
                            <td class="border border-gray-300 p-2">Aksi</td>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($users as $user)
                        <tr>
                            <td class="border border-gray-300 p-2">
                               {{$loop->iteration}} 
                            </td>
                            <td class="border border-gray-300 p-2">
                               {{$user->name}} 
                            </td>
                            <td class="border border-gray-300 p-2">
                                {{$user->email}} 
                            </td>
                            <td class="border border-gray-300 p-2">
                                {{$user->role}}
                            </td>
                            <td class="border border-gray-300 p-2">
                                <a href="{{route('user.edit', $user->id)}}">Edit</a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('user.destroy', $user->id) }}" method="post"  onsubmit="return confirm('apakah anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                                @endif
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
