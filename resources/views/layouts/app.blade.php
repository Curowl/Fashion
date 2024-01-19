<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') || {{ config('app.name') }}</title>
    <meta content="Fahim Anzam Dip" name="author">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">



    <style>
        /* Estilos para el sidebar normal */
        #sidebar {
            background-color: #1b1811; /* Color del fondo del sidebar */
        }

        .custom-ul {
            background-color: #3b3832; /* Color del fondo de los elementos ul */
            /* Otros estilos para los elementos ul si es necesario */
        }

        .c-sidebar-nav-item.c-active,
        #sidebar.c-sidebar-minimized .c-sidebar-nav-item.c-active {
            background-color: #685c3e !important;
        }

        .c-sidebar-nav-item.c-active  {
            background-color: #685c3e !important;
        }


      .c-active {
            background-color: #685c3e !important;
        }

        /* Cambiar color de fondo al hacer hover */
        .c-sidebar-nav-item:not(.c-active) a.c-sidebar-nav-link:hover {
            background-color: #685c3e;
        }

        /* Estilos para el sidebar minimizado */
        #sidebar.c-sidebar-minimized {
            background-color: #1b1811; /* Color del fondo del sidebar minimizado */
        }

        #sidebar.c-sidebar-minimized .custom-ul {
            background-color: #3b3832; /* Color del fondo de los elementos ul en el sidebar minimizado */
        }

        #sidebar.c-sidebar-minimized .c-sidebar-nav-item {
            background-color: #1b1811; /* Color del fondo de los elementos en el sidebar minimizado */
        }

        #sidebar.c-sidebar-minimized .c-sidebar-nav-item.c-active {
            background-color: #685c3e !important;
        }

        /* Cambiar color de fondo al hacer hover en el sidebar minimizado */
        #sidebar.c-sidebar-minimized .c-sidebar-nav-item:not(.c-active) a.c-sidebar-nav-link:hover {
            background-color: #685c3e;
        }
    </style>



     @include('includes.main-css')
</head>

<body class="c-app">
    @include('layouts.sidebar')

    <div class="c-wrapper">
        <header class="c-header c-header-light c-header-fixed">
            @include('layouts.header')
            <div class="c-subheader justify-content-between px-3">
                @yield('breadcrumb')
            </div>
        </header>

        <div class="c-body">
            <main class="c-main">
                @yield('content')
            </main>
        </div>

        @include('layouts.footer')
    </div>

    @include('includes.main-js')
</body>
</html>
