@extends('base.main')
@section('title') Asset @endsection
@section('page_icon') <i class="fa fa-cube"></i> @endsection
@section('page_title') Asset @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('asset.create') }}" class="btn btn-success" title="Create Asset">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-1">
            <button id="btn-reset" class="btn btn-default btn-lg"><i class="fa fa-refresh"></i></button>    
        </div>
        <div class="col-md-10">
            <div style="min-width: 400px; overflow-x: scroll; margin: 0 0 15px 0;">
                <button id="btn-status-active" class="btn btn-success btn-lg">
                    <div class="loader"></div>
                    <div id="value-status-active" style="display: inline-block;"></div> 
                    <div style="display: inline-block;">Active</div>
                </button>
                <button id="btn-status-takeout" class="btn btn-danger btn-lg">
                    <div class="loader"></div>
                    <div id="value-status-takeout" style="display: inline-block;"></div> 
                    <div style="display: inline-block;">Take Out</div>
                </button>
                <button id="btn-status-closed" class="btn btn-warning btn-lg">
                    <div class="loader"></div>
                    <div id="value-status-closed" style="display: inline-block;"></div> 
                    <div style="display: inline-block;">Closed</div>
                </button>
                <button id="btn-investasi-open" class="btn btn-primary btn-lg">
                    <div class="loader"></div>
                    <div id="value-investasi-open" style="display: inline-block;"></div> 
                    <div style="display: inline-block;">Open</div>
                </button>
                <button id="btn-investasi-closed" class="btn btn-default btn-lg">
                    <div class="loader"></div>
                    <div id="value-investasi-closed" style="display: inline-block;"></div> 
                    <div style="display: inline-block;">Closed</div>
                </button>
            </div>  
        </div>
        
    </div>
    <div class="box box-solid">
        <div class="loading"></div>
        {{-- <div class="box-header">
            <button class="btn btn-sm btn-danger btn-mass-delete"><i class="fa fa-trash"></i></button>
        </div> --}}
        <div class="box-body">
            <table id="datatable" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        {{-- <th></th> --}}
                        {{-- <th>No</th> --}}
                        <th>Asset name</th>
                        <th>Owner Name</th>
                        <th>Category</th>
                        <th>Class</th>
                        <th>Interest</th>
                        <th>Sisa Investasi</th>
                        <th>Sisa Tenor</th>
                        <th>Status</th>
                        <th>Investasi</th>
                        <!-- <th>Created By</th>
                        <th>Created Date</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
    <style type="text/css">
        .loader {
            background-size: contain;
            background-image: url('/images/loading.svg');
            background-repeat: no-repeat;
            display: inline-block;
            height: 20px;
            width: 20px;
            /*position: absolute;*/
            float: left;
        }
        .loading {
            background: url(/images/loading-big.svg) no-repeat;
            background-position: center;
            background-size: auto;
            background-color: rgba(0,0,0,0.5);
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            position: absolute;
            z-index: 100;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function(){
            var table = $('#datatable').DataTable({
                fnDrawCallback : function(){
                    $('.loading').hide();
                },
                responsive : true,
                processing : true,
                serverSide : true,
                pageLength : 20,
                ajax: "{{ route('table.asset') }}",
                columns: [
                    // {data : 'checkbox', name : 'checkbox'},
                    // {data : 'DT_Row_Index', name : 'id'},
                    {data : 'asset_name', name : 'asset_name'},
                    {data : 'owner_name', name : 'owner_name'},
                    {data : 'category', name : 'category'},
                    {data : 'class_id', name : 'class_id'},
                    {data : 'interest', name : 'interest'},
                    {data : 'sisa_investasi', name : 'sisa_investasi'},
                    {data : 'sisa_tenor', name : 'sisa_tenor'},
                    {data : 'status', name : 'status'},
                    {data : 'status_investasi', name : 'status_investasi'},
                    // {data : 'created_by', name : 'created_by'},
                    // {data : 'created_at', name : 'created_at'},
                    {data : 'action', name : 'action'}
                ]
            });

            setTimeout(function(){
                let valueStatusActive   = $('#value-status-active');
                let valueStatusTakeout  = $('#value-status-takeout');
                let valueStatusClosed   = $('#value-status-closed');
                let valueInvestasiOpen      = $('#value-investasi-open');
                let valueInvestasiClosed    = $('#value-investasi-closed');

                $.ajax({
                    url: '{{ route('asset.countdata') }}',
                    method: 'get',
                    dataType: 'json',
                    complete: function(){
                        $('.loader').hide();
                    },
                    success: function(r){
                        valueStatusActive.html(r.statusActive);
                        valueStatusTakeout.html(r.statusTakeout);
                        valueStatusClosed.html(r.statusClosed);
                        valueInvestasiOpen.html(r.investasiOpen);
                        valueInvestasiClosed.html(r.investasiClosed);
                    },
                    error: function(e){
                        console.log(e);
                    }
                });
            }, 5000);

            function clearFilter() {
                table.columns(7).search("").draw();
                table.columns(8).search("").draw();
            }

            $('#btn-status-active').on('click', function(){
                $('.loading').show();
                table.columns(7).search('^ACTIVE$', true, false).draw({
                    fnDrawCallback: function(){
                        $('.loading').hide();
                    }
                });
            });
            $('#btn-status-takeout').on('click', function(){
                $('.loading').show();
                table.columns(7).search('^TAKE OUT$', true, false).draw({
                    fnDrawCallback: function(){
                        $('.loading').hide();
                    }
                });
            });
            $('#btn-status-closed').on('click', function(){
                $('.loading').show();
                table.columns(7).search('^CLOSED$', true, false).draw({
                    fnDrawCallback: function(){
                        $('.loading').hide();
                    }
                });
            });
            $('#btn-investasi-open').on('click', function(){
                $('.loading').show();
                table.columns(8).search('^OPEN$', true, false).draw({
                    fnDrawCallback: function(){
                        $('.loading').hide();
                    }
                });
            });
            $('#btn-investasi-closed').on('click', function(){
                $('.loading').show();
                table.columns(8).search('^CLOSED$', true, false).draw({
                    fnDrawCallback: function(){
                        $('.loading').hide();
                    }
                });
            });

            $('#btn-reset').on('click', function(){
                $('.loading').show();
                table.columns().search("").draw({
                    fnDrawCallback: function(){
                        $('.loading').hide();
                    }
                });
            });

        });
    </script>
@endpush