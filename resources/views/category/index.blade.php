<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <p>Détails catégories</p>
        @foreach ($categories as $category)
            <p>{{ $category->name }}</p>
            {{-- <p>{{ dd($category) }}</p> --}}
        @endforeach
    </div>
</x-app-layout>
