@extends('layouts.main')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
  
        <form method="POST" action="{{ route('tickets.update.{id}', $tickets->id) }}" autocomplete="off" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <div class="row align-items-center">
                    <h3 class="mb-0 font-bold">{{ __('Edit Ticket') }}</h3>
                </div>
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
                    Content
                    </div>
                    <textarea id="content" name="content" rows="4" cols="50" class="w-full" placeholder="Describe yourself here...">{{ old('content', $tickets->content ) }}</textarea>
                </section>
                    @error('content')
                        <div class="text-red-500 text-sm ml-16 mt-2">{{ $message }}</div>
                    @enderror
            </div>

            <div class="grid mb-4">  
                    <section class="flex pt-1">
                        <div class="mr-4 w-[5rem] ">
                        Status
                        </div>
                        
                        <select class="form-select form-select-sm" name="status" id="status"  >
                            <option value="" selected >All</option>
                            @foreach($status as $key=>  $item)
                             <option value="{{$key }}" @if($key == $tickets->sts) selected @endif >{{$item }} </option>
                            @endforeach
                        </select>

                    </section>
                    @error('status')
                        <div class="text-red-500 text-sm ml-16  mt-2">{{ $message }}</div>
                    @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="w-full mt-4 ml-20 ">
                    <label for="attachment">Image:</label>
                    <input type="file" name="attachment" id="attachment">
                    <img src="{{ asset($tickets->attachment) }}" alt="{{ $tickets->title }}" class="max-w-[16rem]  ">
                    @error('attachment')
                            <div class="text-red-500 text-sm ml-16  mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="w-full text-right mt-4">
                    <label for="published"> Would you like to publish this ticket </label>
                    <input type="checkbox" id="published" name="published" value="1" {{ (old('published')  || $tickets->published )? 'checked="checked"' : '' }} >
                    

                    <div class="w-full text-right">
                        <button type="submit" class="btn btn-save mt-3" >{{ __('Save') }}</button>
                    </div>
                </div>
            </div>


        </form>
    </div>
</div>   
@endsection
