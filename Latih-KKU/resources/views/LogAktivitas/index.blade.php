<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
               
               <table class="w-full border-collapse border border-black">
                <thead>
                    <tr>
                        <td class="border border-black w-4">No</td>
                        <td class="border border-black w-4">Nama Kategori</td>
                        <td class="border border-black w-4">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($log as $log)
                    <tr>
                        <td class="border border-black w-4">{{$loop->iteration}}</td>
                        <td class="border border-black w-4">{{$log->user->name}}</td>
                        <td class="border border-black w-4">{{$log->aksi}}</td>
                        <td class="border border-black w-4">{{$log->detail}}</td>
                        <td class="border border-black w-4"></td>
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
