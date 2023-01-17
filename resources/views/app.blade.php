<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        
        <link rel="shortcut icon" href="/favicon.ico">

        @vite('resources/js/app.js')
    </head>
    <body class="g-sidenav-show bg-gray-100">
        @inertia
    </body>
</html>
