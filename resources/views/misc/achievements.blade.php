<x-app-layout>
    <x-slot name="title">Achievements</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="achievements-page">
        <h5 class="mb-25">Special Achievements</h5>
        <div class="grid-x grid-margin-x">
            @foreach ($special as $achievement)
                @include('misc._achievement', ['achievement' => $achievement])
            @endforeach
        </div>
        <h5 class="mb-25">Membership Achievements</h5>
        <div class="grid-x grid-margin-x">
            @foreach ($membership as $achievement)
                @include('misc._achievement', ['achievement' => $achievement])
            @endforeach
        </div>
        <h5 class="mb-25">General Achievements</h5>
        <div class="grid-x grid-margin-x">
            @foreach ($general as $achievement)
                @include('misc._achievement', ['achievement' => $achievement])
            @endforeach
        </div>
    </body>
</x-app-layout>
