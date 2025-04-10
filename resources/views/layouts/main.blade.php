<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Scripts -->        
        <link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />

        <script src="{{ asset('/js/app.js') }}" type="text/javascript" ></script>
        <script src="{{ asset('/js/jquery.3.2.1.min.js') }}" type="text/javascript"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')


            <!-- Page Content -->
            <main>

                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @if(!empty($success))
                            <div class="alert alert-success"> {{ $success }}</div>
                        @endif
                         @yield('content')

                    </div>
                </div>
               
            </main>
        </div>
    </body>

    <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script>

     $(document).ready(function() {
      
        let searchdata = $('#search_data').val();
        $('#tbl_tickets').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "pagingType": 'full_numbers',
                "deferRender": true,
                select: true,
                "sAjaxSource":  "{{ route('tickets.gettickets') }}",
                "lengthMenu": [
                    [10, 20, 30, -1],
                    [10, 20, 30, 'All']
                ],
                "columns": [
                    { data: 'id' },
                    { data: 'title' },
                    { data: 'sts' },
                    { data: 'created_at' },
                ],
                "columnDefs": [ 
                    { "targets": 'no-sort', "orderable": false },
                    { className: 'dt-center', targets: [ 2,3 ] },
                    {
                        targets: 1,
                        data: 'title',
                        render: function (data, type, row, meta) {
                            return '<a href="/tickets/info/'+ row.id +'" onclick="showInfo('+ row.id +')">'+ data +'</span>';
                        }
                    },
                    {
                        targets: 2,
                        data: 'sts',
                        render: function (data, type, row, meta) {
                            return '<span class="' + data + '">'+ data +'</span>';
                        }
                    }
                ],
                initComplete: function () {
                    this.api().columns([2]).every( function () {
                        var column = this;
                        var select = $('select#status')
                            .on( 'change', function () {
                                var val = $(this).val();
                                column.search( this.value ).draw();
                            } );
                    } );
                }
        });

        function showInfo(obj) {
            console.log(obj)
        }

    });
 </script>

</html>
