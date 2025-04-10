@extends('layouts.main')

@section('content')

@if(session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">

        <div class="mb-4">
            <section class="grid grid-cols-2 gap-2">
                <div class="row align-items-center">
                    <h3 class="mb-0 font-bold">Ticket #: {{ $tickets->id }}</h3>
                </div>
                <div class="grid justify-end">
                <a class="btn btn-outline-dark border" href="{{ route('tickets.edit.{id}', $tickets->id) }}" >Edit</a>
                </div>
            </section>
        </div>

        <div class="grid mb-4">  
            <section class="flex pt-1">
                <div class="mr-4 w-[5rem] ">
                Title:
                </div>
                <div>{{ $tickets->title }}</div>
            </section>
        </div>

        <div class="grid  mb-4">  
            <section class="flex pt-1">
                <div class="mr-4 w-[5rem] ">
                Content:
                </div>
                <div class="whitespace-pre-line ">{{ $tickets->content }}</div>
            </section>
        </div>

        <div class="grid mb-4">  
            <section class="flex pt-1">
                <div class="mr-4 w-[5rem] ">
                    Status:
                </div>
                <div> <span class="{{  $status[$tickets->sts] }}">{{  $status[$tickets->sts] }}</span></div>
            </section>
        </div>

        <div class="grid mb-4">  
            <section class="flex pt-1">
                <div class="mr-4 w-[5rem] ">
                    Published:
                </div>
                <div> {{ ($tickets->published ) ? 'Yes':'Draft' }}</div>
            </section>
        </div>
        @if ($tickets->attachment) :
        <div class="w-full mt-4 ml-20 ">
            <label for="attachment">Image:</label>
            <img src="{{ asset($tickets->attachment) }}" alt="{{ $tickets->title }}" class="max-w-[16rem]  ">
        </div>
        @endif

    </div>
</div>   
@endsection
