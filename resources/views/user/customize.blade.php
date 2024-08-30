<x-app-layout>
    <x-slot name="title">Customize Avatar</x-slot>
    <x-slot name="navigation"></x-slot>
    <meta name="user-info" data-id="{{ auth()->user()->id }}">
    <meta name="character-info" data-spinner="{{ asset('img/avatar/LoadingGif2.gif') }}">
    <style>
        .avatar-body-color {
            width: 50px;
            height: 50px;
            cursor: pointer;
            display: inline-block;
        }

        .avatar-body-color:not(:last-child) {
            margin-right: 5px
        }

        .avatar-body-color.inProgress {
            opacity: .8;
            pointer-events: none;
            cursor: not-allowed;
        }

        .avatar-item-category.active {
            font-weight: 600;
        }

        .avatar-body-part {
            outline: none;
            cursor: pointer;
        }

        .avatar-body-part:disabled {
            opacity: .8;
            pointer-events: none;
            cursor: not-allowed;
        }

        .character-item {
            position: relative;
        }

        .character-button {
            position: absolute;
            right: 0;
            top: 0;
            border-bottom: none !important;
            border-radius: 0;
        }

        .palette {
            background: #FFF;
            position: absolute;
            margin-left: 300px;
            margin-top: 308px;
            padding: 15px;
            border: 1px solid #CCC;
            z-index: 1337;
            display: none;
        }

        @media only screen and (max-width: 768px) {
            .palette {
                margin-top: 200px;
                margin-left: 20px;
            }
        }

        .palette-header-text {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
            font-weight: 600;
        }
    </style>

    <body class="character-page">
        <div class="palette" id="colors">
            <div class="palette-header-text" id="colorsText">Choose a color</div>
            <div class="avatar-body-colors">
                <div class="avatar-body-color" style="background:#8d5524;" data-color="brown"></div>
                <div class="avatar-body-color" style="background:#c68642;" data-color="light-brown"></div>
                <div class="avatar-body-color" style="background:#e0ac69;" data-color="lighter-brown"></div>
                <div class="avatar-body-color" style="background:#f1c27d;" data-color="lighter-lighter-brown"></div>
                <div class="avatar-body-color" style="background:#faf123;" data-color="bloxcity-yellow"></div>
            </div>
            <div class="avatar-body-colors">
                <div class="avatar-body-color" style="background:#f19d9a;" data-color="salmon"></div>
                <div class="avatar-body-color" style="background:#769fca;" data-color="blue"></div>
                <div class="avatar-body-color" style="background:#a2d1e6;" data-color="light-blue"></div>
                <div class="avatar-body-color" style="background:#a08bd0;" data-color="purple"></div>
                <div class="avatar-body-color" style="background:#312b4c;" data-color="dark-purple"></div>
            </div>
            <div class="avatar-body-colors">
                <div class="avatar-body-color" style="background:#046306;" data-color="dark-green"></div>
                <div class="avatar-body-color" style="background:#1b842c;" data-color="green"></div>
                <div class="avatar-body-color" style="background:#f7b155;" data-color="yellow"></div>
                <div class="avatar-body-color" style="background:#f79039;" data-color="orange"></div>
                <div class="avatar-body-color" style="background:#ff0000;" data-color="red"></div>
            </div>
            <div class="avatar-body-colors">
                <div class="avatar-body-color" style="background:#f8a3d5;" data-color="light-pink"></div>
                <div class="avatar-body-color" style="background:#ff0e9a;" data-color="pink"></div>
                <div class="avatar-body-color" style="background:#f1efef;" data-color="white"></div>
                <div class="avatar-body-color" style="background:#7d7d7d;" data-color="gray"></div>
                <div class="avatar-body-color" style="background:#000;" data-color="black"></div>
            </div>
        </div>
        <div class="grid-x grid-margin-x">
            <div class="cell medium-4">
                <div style="font-size:18px;">Avatar</div>
                <div class="container mb-15">
                    <img id="avatar" src="{{ Auth::user()->get_avatar() }}" style="width:100%;">
                    <div id="avatarError" style="color:red;"></div>
                    <br>
                    <div class="text-center">
                        <button class="button button-primary" data-angle="1" @if (Auth::user()->avatar->orient == '1') disabled @endif><i class="fas fa-arrow-left"></i></button>
                        <button class="button button-primary" data-angle="2" @if (Auth::user()->avatar->orient == '2') disabled @endif><i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
                <div style="font-size:18px;">Colors</div>
                <div class="container text-center">
                    <div>
                        <button class="avatar-body-part" data-part="head">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" x="0px" y="0px" width="62px" height="62px" viewBox="0 0 128 128">
                                <defs>
                                    <g id="Head0_0_FILL">
                                        <path fill="#000000" stroke="none" d="
                                                            M 48.15 34.6
                                                            Q 45.75 34.6 44 37.35 42.25 40.15 42.25 44.05 42.25 47.95 44 50.75 45.75 53.5 48.15 53.5 50.55 53.5 52.25 50.75 54 47.95 54 44.05 54 40.15 52.25 37.35 50.55 34.6 48.15 34.6
                                                            M 85.75 44.05
                                                            Q 85.75 40.15 83.95 37.35 82.25 34.6 79.85 34.6 77.45 34.6 75.7 37.35 74 40.15 74 44.05 74 47.95 75.7 50.75 77.45 53.5 79.85 53.5 82.25 53.5 83.95 50.75 85.75 47.95 85.75 44.05 Z" />
                                    </g>

                                    <path class="avatar-part-color" data-part-color="head" id="Head1_0_1_STROKES" stroke="#000000" stroke-width="5" stroke-linejoin="round" stroke-linecap="round" fill="{{ auth()->user()->avatar->hex_head }}" d="
                                                        M 44.15 3.6
                                                        L 83.9 3.6
                                                        Q 98 3.6 107.95 13.25 118 22.9 118 36.55
                                                        L 118 91.4
                                                        Q 118 105.1 107.95 114.75 98 124.4 83.9 124.4
                                                        L 44.15 124.4
                                                        Q 30 124.4 20.05 114.75 10.05 105.1 10.05 91.4
                                                        L 10.05 36.55
                                                        Q 10.05 22.9 20.05 13.25 30 3.6 44.15 3.6 Z" />

                                    <path id="Head0_0_1_STROKES" stroke="#000000" stroke-width="1" stroke-linejoin="round" stroke-linecap="round" fill="none" d="
                                                        M 85.75 44.05
                                                        Q 85.75 47.95 83.95 50.75 82.25 53.5 79.85 53.5 77.45 53.5 75.7 50.75 74 47.95 74 44.05 74 40.15 75.7 37.35 77.45 34.6 79.85 34.6 82.25 34.6 83.95 37.35 85.75 40.15 85.75 44.05 Z
                                                        M 54 44.05
                                                        Q 54 47.95 52.25 50.75 50.55 53.5 48.15 53.5 45.75 53.5 44 50.75 42.25 47.95 42.25 44.05 42.25 40.15 44 37.35 45.75 34.6 48.15 34.6 50.55 34.6 52.25 37.35 54 40.15 54 44.05 Z" />

                                    <path id="Head0_0_2_STROKES" stroke="#000000" stroke-width="5" stroke-linejoin="round" stroke-linecap="round" fill="none" d="
                                                        M 86.95 79.4
                                                        Q 85.2 82.45 82.65 85.15 74.95 93.4 64.1 93.4 53.2 93.4 45.25 85.15 42.75 82.45 41.1 79.4" />
                                </defs>

                                <g transform="matrix( 1, 0, 0, 1, 0,0) ">
                                    <use xlink:href="#Head1_0_1_STROKES" />
                                </g>

                                <g transform="matrix( 1, 0, 0, 1, 0,0) ">
                                    <use xlink:href="#Head0_0_FILL" />

                                    <use xlink:href="#Head0_0_1_STROKES" />

                                    <use xlink:href="#Head0_0_2_STROKES" />
                                </g>
                            </svg>
                        </button>
                    </div>
                    <div style="margin-top:-3px;">
                        <button class="avatar-body-part" style="padding-right:0px;" data-part="rarm">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" x="0px" y="0px" width="48px" height="94px" viewBox="0 0 100 188">
                                <defs>
                                    <path class="avatar-part-color" id="LArm0_0_1_STROKES" data-part-color="rarm" stroke="#000000" stroke-width="5" stroke-linejoin="round" stroke-linecap="round" fill="{{ auth()->user()->avatar->hex_rarm }}" d="
                                                            M 17.9 4.8
                                                            L 82.1 4.8
                                                            Q 95.05 4.8 95.05 17.85
                                                            L 95.05 170.2
                                                            Q 95.05 183.25 82.1 183.25
                                                            L 17.9 183.25
                                                            Q 4.95 183.25 4.95 170.2
                                                            L 4.95 17.85
                                                            Q 4.95 4.8 17.9 4.8 Z" />
                                </defs>

                                <g transform="matrix( 1, 0, 0, 1, 0,0) ">
                                    <use xlink:href="#LArm0_0_1_STROKES" />
                                </g>
                            </svg>
                        </button>
                        <button class="avatar-body-part" data-part="torso">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" x="0px" y="0px" width="94px" height="94px" viewBox="0 0 188 188">
                                <defs>
                                    <path class="avatar-part-color" data-part-color="torso" id="Torso0_0_1_STROKES" stroke="#000000" stroke-width="5" stroke-linejoin="round" stroke-linecap="round" fill="{{ auth()->user()->avatar->hex_torso }}" d="
                                                            M 17.6 4.8
                                                            L 170.45 4.8
                                                            Q 183.4 4.8 183.4 17.85
                                                            L 183.4 170.2
                                                            Q 183.4 183.25 170.45 183.25
                                                            L 17.6 183.25
                                                            Q 4.65 183.25 4.65 170.2
                                                            L 4.65 17.85
                                                            Q 4.65 4.8 17.6 4.8 Z" />
                                </defs>

                                <g transform="matrix( 1, 0, 0, 1, 0,0)">
                                    <use xlink:href="#Torso0_0_1_STROKES" />
                                </g>
                            </svg>
                        </button>
                        <button class="avatar-body-part" style="padding-right:0px;" data-part="larm">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" x="0px" y="0px" width="48px" height="94px" viewBox="0 0 100 188">
                                <defs>
                                    <path class="avatar-part-color" data-part-color="larm" id="RArm0_0_1_STROKES" stroke="#000000" stroke-width="5" stroke-linejoin="round" stroke-linecap="round" fill="{{ auth()->user()->avatar->hex_larm }}" d="
                                                            M 17.9 4.8
                                                            L 82.1 4.8
                                                            Q 95.05 4.8 95.05 17.85
                                                            L 95.05 170.2
                                                            Q 95.05 183.25 82.1 183.25
                                                            L 17.9 183.25
                                                            Q 4.95 183.25 4.95 170.2
                                                            L 4.95 17.85
                                                            Q 4.95 4.8 17.9 4.8 Z" />
                                </defs>

                                <g transform="matrix( 1, 0, 0, 1, 0,0) ">
                                    <use xlink:href="#RArm0_0_1_STROKES" />
                                </g>
                            </svg>
                        </button>
                    </div>
                    <div style="margin-top:-4px;">
                        <button class="avatar-body-part" style="padding-right:0px;" data-part="rleg">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" x="0px" y="0px" width="43px" height="98px" viewBox="0 0 87 196">
                                <defs>
                                    <path class="avatar-part-color" data-part-color="rleg" id="RLeg0_0_1_STROKES" stroke="#000000" stroke-width="5" stroke-linejoin="round" stroke-linecap="round" fill="{{ auth()->user()->avatar->hex_rleg }}" d="
                                                            M 5.05 4.85
                                                            L 82.4 4.85 82.4 39
                                                            Q 77.95 45.05 77.5 56.1
                                                            L 77.5 164.9 77.5 191.2 4.55 191.2 4.55 164.9 4.55 4.85
                                                            M 77.5 164.9
                                                            Q 60.55 161.6 43.6 161.3
                                                            L 43.15 161.3
                                                            Q 42.45 161.3 41.8 161.3 23.2 161.3 4.55 164.9" />
                                </defs>

                                <g transform="matrix( 1, 0, 0, 1, 0,0) ">
                                    <use xlink:href="#RLeg0_0_1_STROKES" />
                                </g>
                            </svg>
                        </button>
                        <button class="avatar-body-part" style="padding-right:0px;" data-part="lleg">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" x="0px" y="0px" width="43px" height="98px" viewBox="0 0 87 196">
                                <defs>
                                    <path class="avatar-part-color" data-part-color="lleg" id="LLeg0_0_1_STROKES" stroke="#000000" stroke-width="5" stroke-linejoin="round" stroke-linecap="round" fill="{{ auth()->user()->avatar->hex_lleg }}" d="
                                                            M 81.95 4.85
                                                            L 4.6 4.85 4.6 39
                                                            Q 9.05 45.05 9.5 56.1
                                                            L 9.5 164.9
                                                            Q 26.45 161.6 43.4 161.3
                                                            L 43.85 161.3
                                                            Q 44.55 161.3 45.2 161.3 63.8 161.3 82.45 164.9
                                                            L 82.45 4.85
                                                            M 82.45 164.9
                                                            L 82.45 191.2 9.5 191.2 9.5 164.9" />
                                </defs>

                                <g transform="matrix( 1, 0, 0, 1, 0,0) ">
                                    <use xlink:href="#LLeg0_0_1_STROKES" />
                                </g>
                            </svg>

                        </button>
                    </div>
                </div>
                <div class="push-15 show-for-small-only"></div>
            </div>
            <div class="cell medium-8">
                <div style="font-size:18px;">Inventory</div>
                <div class="container mb-15">
                    <div class="text-center mb-15">
                        <a class="avatar-item-category active" data-category="hats">Hats</a> |
                        <a class="avatar-item-category" data-category="faces">Faces</a> |
                        <a class="avatar-item-category" data-category="accessories">Accessories</a> |
                        <a class="avatar-item-category" data-category="t-shirts">T-Shirts</a> |
                        <a class="avatar-item-category" data-category="shirts">Shirts</a> |
                        <a class="avatar-item-category" data-category="pants">Pants</a> |
                        <a class="avatar-item-category" data-category="heads">Heads</a>
                    </div>
                    <div id="inventory"></div>
                    <div id="inventoryButtons"></div>
                </div>
                <div style="font-size:18px;">Currently Wearing</div>
                <div class="container">
                    <div id="currentlyWearing"></div>
                </div>
            </div>
        </div>
    </body>
    <x-slot name="script">
        <script src="{{ asset('js/site/character.js?v=17') }}"></script>
    </x-slot>
</x-app-layout>