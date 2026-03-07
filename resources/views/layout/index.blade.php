<!DOCTYPE html>
<html lang="en" data-theme="light">

@include('inc.header')

@vite(['resources/js/app.js'])

<body>
    @include('inc.sidebar')

    <main class="dashboard-main">
        @include('inc.navbar')
        <div class="dashboard-main-body">
            @yield('content')
        </div>
        @include('inc.footer')
    </main>


    @include('inc.scripts')

</body>



</html>
