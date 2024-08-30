<x-admin-layout>
    <x-slot name="title">View Report</x-slot>
    <div class="row">
        <div class="col-12">
            <h3>Information</h3>
            <div class="card mb-4">
                <div class="card-body">
                <form method="POST" action="{{ route('ais.report.action', $report->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">

                            <h5 class="mb-0">Submitted</h5>
                            <p>By <a href="{{ route('user.profile', $report->owner->id) }}" target="_blank">{{ $report->owner->username }}</a> at {{ $report->created_at }}</p>

                            <h5 class="mb-0">Content Uploaded</h5>
                            <p>By <a href="{{ route('user.profile', $report->reportedUser->id) }}" target="_blank">{{ $report->reportedUser->username }}</a> at {{ $report->content->created_at }}</p>

                            <h5 class="mb-0">Content Type</h5>
                            <p>{{ $report->type() }}</p>

                            <h5 class="mb-0">Reported For</h5>
                            <p>{{ $report->rule }}</p>


                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label h5">Reported Content</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" name="content" rows="6" disabled>
@if($report->type == 1)
Title: {{ $report->content->title }}
Body: {{ $report->content->body }}
@elseif($report->type == 2)
{{ $report->content->body }}
@elseif($report->type == 3)
Username: {{ $report->content->username }}
About Me: {{ $report->content->biography }}
Signature: {{ $report->content->signature }}
@if($report->content->blurb)
Blurb: {{ $report->content->blurb->text }}
@endif
@elseif($report->type == 4)
{{ $report->content->text }}
@elseif($report->type == 5)
<img src="https://cdn.bloxcity.com/{{ $report->content->hash }}.png" class="img-fluid" style="max-width: 400px;">
Title: {{ $report->content->name }}
Description: {{ $report->content->desc }}
@elseif($report->type == 6)
{{ $report->content->text }}
@elseif($report->type == 7)
Subject: {{ $report->content->subject }}
Body: {{ $report->content->body }}
@elseif($report->type == 8)
Name: {{ $report->content->name }}
Description: {{ $report->content->desc }}
@elseif($report->type == 9)
{{ $report->content->text }}
@elseif($report->type == 10)
{{ $report->content->body }}
@elseif($report->type == 11)
{{ $report->content->image_path }}
@endif</textarea>
                            </div>
                            <a href="{{ $report->linkify() }}" target="_blank">Link to content on site</a>

                        </div>
                    </div>
                </div>
            </div>

            <h3>Actions</h3>
            <div class="card mb-4">
                <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Punishment <span class="text-xs">Not sure? <a href="#">Check the mod guide.</a></span></h5>

                                <div class="form-check">
                                    <input class="form-check-input form-check-input-success" type="radio" name="actionSelect" id="exampleRadios1" value="dismiss" checked>
                                    <label class="form-check-label text-success" for="exampleRadios1">
                                        Discard report without punishing the user
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input  form-check-input-warning" type="radio" name="actionSelect" id="exampleRadios2" value="warn">
                                    <label class="form-check-label text-warning" for="exampleRadios2">
                                        Warn the user
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input  form-check-input-danger" type="radio" name="actionSelect" id="exampleRadios3" value="ban">
                                    <label class="form-check-label text-danger" for="exampleRadios3">
                                        Ban the user
                                    </label>
                                </div>

                                <span id="banlen" class="d-none">
                                    <h5>Ban Length</h5>
                                    <select name="length" class="form-select mb-3">
                                        <option selected disabled>Open this select menu</option>
                                        <option value="1">6h</option>
                                        <option value="2">12h</option>
                                        <option value="3">1d</option>
                                        <option value="4">2d</option>
                                        <option value="5">3d</option>
                                        <option value="6">1 week</option>
                                        <option value="7">1 month</option>
                                        <option value="8">6 months</option>
                                        <option value="9">1 year</option>
                                        <option value="10">Permanent</option>
                                    </select>
                                </span>

                                <span id="mess" class="d-none">
                                    <h5 class="mt-2 mb-0">Message</h5>
                                    <div class="mb-2">
                                        <label for="exampleFormControlTextarea1 h5" class="form-label">(shown to user along with the reported content)</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="note"></textarea>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="scrub" value="" id="scrubCheckbox">
                                        <label class="form-check-label" for="scrubCheckbox">
                                            Scrub (removes content and replaces with generic message)
                                        </label>
                                    </div>
                                </span>

                                <h5 class="mb-1">Complete the Report</h5>
                                <p>Accuracy in moderation is important, so make sure you've reviewed all the information. If everything looks right, click the submit button.</p>
                                <div class="bg-gray-500 rounded p-3 mb-1">
                                    <p id="dismissMess" class="text-success mb-0">- You are not warning or banning the user.</p>
                                    <p id="warnMess" class="text-warning mb-0 d-none">- You are warning the user.</p>
                                    <p id="banMess" class="text-danger mb-0 d-none">- You are banning the user.</p>
                                    <p id="scrubMess" class="text-gray-150 mb-0 d-none">- You are scrubbing the content.</p>
                                </div>


                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label h5">Add Internal Notes</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="internal" rows="6"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="submit" value="Complete" class="btn btn-primary btn-lg mt-3">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="script">
        <script>
            $('input[type=radio][name=actionSelect]').change(function() {
                if (this.value == 'dismiss') {
                    $('#banlen').addClass('d-none');
                    $('#mess').addClass('d-none');
                    $('#banMess').addClass('d-none');
                    $('#warnMess').addClass('d-none');
                    $('#dismissMess').removeClass('d-none');
                } else if (this.value == 'warn') {
                    $('#banlen').addClass('d-none');
                    $('#mess').removeClass('d-none');
                    $('#banMess').addClass('d-none');
                    $('#warnMess').removeClass('d-none');
                    $('#dismissMess').addClass('d-none');
                } else if (this.value == 'ban') {
                    $('#banlen').removeClass('d-none');
                    $('#mess').removeClass('d-none');
                    $('#banMess').removeClass('d-none');
                    $('#warnMess').addClass('d-none');
                    $('#dismissMess').addClass('d-none');
                }
            });

            $('#scrubCheckbox').change(function() {
                if(this.checked) {
                    $('#scrubMess').removeClass('d-none');
                } else {
                    $('#scrubMess').addClass('d-none');
                }
            });
        </script>
    </x-slot>
</x-admin-layout>