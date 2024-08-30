<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Site Offline / BLOX City</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="120; url=/">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/branding/favicon.png') }}" />

    <!-- Stylesheets -->
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css">
    <link rel="stylesheet" href="{{ asset('css/light-theme.css') }}?r=12351264516" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
        body {
			background: #4d94ff!important;
			font-family: 'Open Sans', 'sans-serif'!important;
		    font-size: 14px!important;
		}
        .container {
			width: 750px;
			margin: 0 auto;
			margin-top: 35px;
		}
				
		.content {
			background: #ffffff!important;
			padding: 30px!important;
			margin-top: 35px!important;
		}
        .wrench {
			background:url(/img/wrench.png);
			background-size: 128px;
			height: 128px;
			width: 128px;
			margin: 0 auto;
		}
        .dev-login {
            border: 0!important;
            padding: 0 !important;
            margin: 0 !important;
            height: 35px !important;
            background-color: #ffffff !important;
            border: 2px solid #039be5 !important;
            font-size: 15px !important;
            width: 30% !important;
            padding: 0 12px !important;
            display: inline!important;
        }
        .dev-login-btn {
            border: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            height: 35px !important;
            background-color: #039be5 !important;
            border: 2px solid #039be5 !important;
            font-size: 15px !important;
            width: 10% !important;
            padding: 0 12px !important;
            color:#fff !important;
            display: inline!important;
        }
    </style>
</head>
<body class="maintenance-page">
    <div id="app">
        <div class="page-wrapper">
            <div class="grid-container maintenance-grid">
                <div class="grid-x">
                    <div class="cell medium-10 medium-offset-1">
                        <div class="maintenance-container">
                            <div class="grid-x align-middle">
                                <div class="cell auto"></div>
                                <div class="cell medium-8">
                                    <div class="wrench"></div>
                                    <div class="content">
                                        <h2>Site Offline</h2>
                                        <p>We're sorry, BLOX City is currently offline for scheduled maintenance. Please try again later.</p>
                                        <p>Stay up to date on our current status by joining our Discord: <a href="https://discord.gg/bloxcity">https://discord.gg/bloxcity</a></p>
                                        <form>
                                            <input type="text" class="dev-login" name="passcode" placeholder="Developer Login" required=""><input type="submit" class="dev-login-btn" value="GO">
                                        </form>
                                    </div>
                                </div>
                                <div class="cell auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
