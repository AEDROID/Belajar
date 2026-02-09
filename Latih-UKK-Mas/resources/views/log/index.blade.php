<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full m-6 border-collapse border">
                    <thead>
                        <tr>
                            <td class="border border-gray-300 p-2 w-4">No</td>
                            <td class="border border-gray-300 p-2">Nama user</td>
                            <td class="border border-gray-300 p-2">Aksi</td>
                            <td class="border border-gray-300 p-2">Detail</td>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($log as $log)
                        <tr>
                            <td class="border border-gray-300 p-2">
                               {{$loop->iteration}} 
                            </td>
                            <td class="border border-gray-300 p-2">
                               {{$log->user->name}} 
                            </td>
                            <td class="border border-gray-300 p-2">
                                {{$log->aksi}} 
                            </td>
                            <td class="border border-gray-300 p-2">
                                {{$log->detail}}
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
