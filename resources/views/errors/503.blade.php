<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Site Offline / Buildaverse</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/branding/favicon.png') }}" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v6.1.1/css/pro.min.css">
</head>
<body>
    <main class="container main-section pb-5 pb-md-0">
        <div class="page-wrapper">
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-5">
                            <content>
                                <img class="mb-3 mx-auto" src="/img/branding/light_long.svg" width="300px">
                                <h1 class="inline mb-2 font-size-75">We're building</h1>
                                <p class="mt-0 font-size-18">
                                    If all goes to plan, we should be back up in no time. Don't fret, we'll load the page automatically once we're done. In the mean time, why not join our <a href="http://web.archive.org/web/20200426223159/https://discord.gg/XepEQce" target="_blank">Discord server</a>?
                                </p>
                            </content>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
