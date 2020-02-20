<!DOCTYPE html>
<html lang="en">
<head>
    @include('base.head')

    <style type="text/css">
        .loader-big {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-color: rgba(0,0,0,0.6);
            z-index: 100;
            background-image: url(/images/loading-big.svg);
            background-repeat: no-repeat;
            background-position: center;
            display: none;
        }
        .bg-aqua-active {
            background-color: #222d32 !important;
        }

        .bg-navy {
            background-color: #222d32 !important;
        }
    </style>
</head>
@php
    $themes = [
        'skin-blue','skin-purple','skin-red','skin-yellow','skin-green',
        'skin-blue-light','skin-purple-light','skin-red-light','skin-yellow-light','skin-green-light'
    ];
    $randIndex = array_rand($themes, 1);
@endphp
<body class="hold-transition skin-black sidebar-mini fixed ">
    <div class="loader-big"></div>
    <div class="wrapper">
        
        @include('base.nav')
        
        @include('base.menu')

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    @yield('page_icon')
                    @yield('page_title')
                    <small>@yield('page_subtitle')</small>
                </h1>
                @yield('breadcrumb')
            </section>

            <section class="content">
                {{-- flash message --}}
                @if(session()->get('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session()->get('success') }} 
                    </div>
                @elseif(session()->get('error'))
                    <div class="alert alert-error alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session()->get('error') }}  
                    </div>
                @endif
                {{-- menu --}}
                @yield('menu')
                {{-- content --}}
                @yield('content')
            </section>
        </div>
        @include('base.modal')

        @include('base.footer')

        <div class="control-sidebar-bg"></div>
    </div>
    @include('base.script')
</body>
</html>