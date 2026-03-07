@php
    $activeTheme = config('mail.ACTIVE_THEME');
@endphp


@include('emails.inc.theme.' . $activeTheme . '.header')


<!-- Main Content -->
<div class="content">
    @yield('content')
</div>


@include('emails.inc.theme.' . $activeTheme . '.footer')
