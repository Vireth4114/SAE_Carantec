@extends('template')
@section('content')
<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Vous êtes bien connecté {{ $name }} {{ $surname }} !
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection
