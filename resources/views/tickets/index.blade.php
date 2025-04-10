@extends('layouts.main')

@section('content')
<div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="w-full">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif

            <div class="grid grid-cols-2 mb-2 ">
                <div><h3>{{ __('My Tickets') }}</h3></div>
                <div class="text-right">
                    <a class="btn btn-outline-dark border" href="{{ route('ticket.create') }}" >Create Ticket</a>
                </div>
            </div>

            <table id="tbl_tickets" class="display" style="width:100%">
                <thead>

                        <tr>
                            <th class="w-[5rem]">#</th>
                            <th>Title</th>
                            <th class="w-[10rem] no-sort">
                            <select class="form-select form-select-sm" name="status" id="status" aria-controls="tbl_tickets" >
                                <option value="" selected >All</option>
                                @foreach($status as $key=>  $item)
                                    <option value="{{$key }}"  >{{$item }} </option>
                                @endforeach
                            </select>
                            </th>
                            <th class="w-[10rem]">Created Date</th>
                        </tr>
                </thead>
                <tbody>

                </tbody>

            </table>


        </div>
    </div>
</div>


@endsection
