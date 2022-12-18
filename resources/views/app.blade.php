<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        {{-- <script defer="defer" src="https://colorlib.com/polygon/adminator/main.js"></script> --}}

        @vite('resources/js/app.js')

        <style>
        #loader {
            transition: all .3s ease-in-out;
            opacity: 1;
            visibility: visible;
            position: fixed;
            height: 100vh;
            width: 100%;
            background: #fff;
            z-index: 90000
        }

        #loader.fadeOut {
            opacity: 0;
            visibility: hidden
        }

        .spinner {
            width: 40px;
            height: 40px;
            position: absolute;
            top: calc(50% - 20px);
            left: calc(50% - 20px);
            background-color: #333;
            border-radius: 100%;
            -webkit-animation: sk-scaleout 1s infinite ease-in-out;
            animation: sk-scaleout 1s infinite ease-in-out
        }

        @-webkit-keyframes sk-scaleout {
            0% {
            -webkit-transform: scale(0)
            }

            100% {
            -webkit-transform: scale(1);
            opacity: 0
            }
        }

        @keyframes sk-scaleout {
            0% {
            -webkit-transform: scale(0);
            transform: scale(0)
            }

            100% {
            -webkit-transform: scale(1);
            transform: scale(1);
            opacity: 0
            }
        }
        </style>
    </head>
    <body class="app is-collapsed">
        @inertia
    </body>
</html>
