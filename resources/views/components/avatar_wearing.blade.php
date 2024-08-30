                    @if(auth()->user()->avatar->hat1_id != 0)
                    <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-2">
                            <button onclick="removeItem({{ auth()->user()->avatar->hat1_id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ auth()->user()->avatar->hat()->name }}" style="background-color: inherit;border:none;">
                                <div class="slot has-item remove-item">
                                    <i class="fa-solid fa-x text-6xl position-absolute text-danger"></i>
                                    <img src="{{ auth()->user()->avatar->hat()->get_render() }}" class="img-fluid">
                                </div>
                            </button>
                    </div>
                    @endif

                    @if(auth()->user()->avatar->hat2_id != 0)
                    <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-2">
                            <button onclick="removeItem({{ auth()->user()->avatar->hat2_id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ auth()->user()->avatar->hat2()->name }}" style="background-color: inherit;border:none;">
                                <div class="slot has-item remove-item">
                                    <i class="fa-solid fa-x text-6xl position-absolute text-danger"></i>
                                    <img src="{{ auth()->user()->avatar->hat2()->get_render() }}" class="img-fluid">
                                </div>
                            </button>
                    </div>
                    @endif

                    @if(auth()->user()->avatar->hat3_id != 0)
                    <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-2">
                            <button onclick="removeItem({{ auth()->user()->avatar->hat3_id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ auth()->user()->avatar->hat3()->name }}" style="background-color: inherit;border:none;">
                                <div class="slot has-item remove-item">
                                    <i class="fa-solid fa-x text-6xl position-absolute text-danger"></i>
                                    <img src="{{ auth()->user()->avatar->hat3()->get_render() }}" class="img-fluid">
                                </div>
                            </button>
                    </div>
                    @endif

                    @if(auth()->user()->avatar->face_id != 0)
                    <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-2">
                            <button onclick="removeItem({{ auth()->user()->avatar->face_id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ auth()->user()->avatar->face()->name }}" style="background-color: inherit;border:none;">
                                <div class="slot has-item remove-item">
                                    <i class="fa-solid fa-x text-6xl position-absolute text-danger"></i>
                                    <img src="{{ auth()->user()->avatar->face()->get_render() }}" class="img-fluid">
                                </div>
                            </button>
                    </div>
                    @endif

                    @if(auth()->user()->avatar->shirt_id != 0)
                    <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-2">
                            <button onclick="removeItem({{ auth()->user()->avatar->shirt_id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ auth()->user()->avatar->shirt()->name }}" style="background-color: inherit;border:none;">
                                <div class="slot has-item remove-item">
                                    <i class="fa-solid fa-x text-6xl position-absolute text-danger"></i>
                                    <img src="{{ auth()->user()->avatar->shirt()->get_render() }}" class="img-fluid">
                                </div>
                            </button>
                    </div>
                    @endif


                    @if(auth()->user()->avatar->pants_id != 0)
                    <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-2">
                            <button onclick="removeItem({{ auth()->user()->avatar->pants_id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ auth()->user()->avatar->pants()->name }}" style="background-color: inherit;border:none;">
                                <div class="slot has-item remove-item">
                                    <i class="fa-solid fa-x text-6xl position-absolute text-danger"></i>
                                    <img src="{{ auth()->user()->avatar->pants()->get_render() }}" class="img-fluid">
                                </div>
                            </button>
                    </div>
                    @endif

                    @if(auth()->user()->avatar->tool_id != 0)
                    <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-2">
                            <button onclick="removeItem({{ auth()->user()->avatar->tool_id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ auth()->user()->avatar->tool()->name }}" style="background-color: inherit;border:none;">
                                <div class="slot has-item remove-item">
                                    <i class="fa-solid fa-x text-6xl position-absolute text-danger"></i>
                                    <img src="{{ auth()->user()->avatar->tool()->get_render() }}" class="img-fluid">
                                </div>
                            </button>
                    </div>
                    @endif