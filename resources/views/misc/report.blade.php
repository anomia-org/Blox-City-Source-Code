<x-app-layout>
    <x-slot name="title">Submit Report</x-slot>
    <x-slot name="navigation"></x-slot>
	<body class="report-page">
    <div id="app">
	<div class="page-wrapper">
<div class="grid-container  report-grid">
<div class="grid-x">
<div class="cell medium-10 medium-offset-1">
<div class="push-50"></div>
<h5>Tell us how you think this is breaking the rules</h5>
<div class="container">
<form action="{{ route($route, $rid) }}" method="POST" class="p-3">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="rid" value="{{ $rid }}">
                    <input type="hidden" name="uid" value="{{ $uid }}">
                    <div class="form-group">
                        <strong>Which rule does this content violate?</strong>
                        <select class="form-input" name="rule" id="rule">
                            <option value="" selected="selected" disabled="disabled">Please select a category</option>
                            <option value="Spam">Spam</option>
                            <option value="Excessive Profanity">Excessive Profanity</option>
                            <option value="Sexual Content">Sexual Content</option>
                            <option value="Sensitive Topics">Sensitive Topics</option>
                            <option value="Offsite Links">Offsite Links</option>
                            <option value="Harassment / Discrimination">Harassment / Discrimination</option>
                            <option value="Exploiting / Cheating">Exploiting / Cheating</option>
                            <option value="Account Theft - Phishing / Hacking">Account Theft - Phishing / Hacking</option>
                            <option value="Other">Other</option>
                        </select>
<strong>Leave a comment (optional)</strong>
<textarea class="form-input" name="comment" placeholder="This person is breaking the rules by..." rows="5"></textarea>
<div>
<div></div>
</div>
<button class="button button-blue" type="submit">Send Report</button>
</form>
</div>
</div>
</div>
</div>
</div>
</div>

</x-app-layout>