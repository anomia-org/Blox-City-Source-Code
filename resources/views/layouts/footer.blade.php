<footer class="footer" style="padding-top:50px;">
    <div class="footer-links">
        <a href="{{ route('notes', ['page' => 'terms']) }}" class="footer-link">Terms</a>
        <a href="{{ route('notes', ['page' => 'privacy']) }}" class="footer-link">Privacy</a>
        <a href="{{ route('notes', ['page' => 'contact']) }}" class="footer-link">Support</a>
        <a href="{{ route('notes', ['page' => 'about']) }}" class="footer-link">About</a>
        <a href="{{ route('notes', ['page' => 'jobs']) }}" class="footer-link">Jobs</a>
        <a href="{{ route('notes', ['page' => 'team']) }}" class="footer-link">Team</a>
        <a href="{{ route('notes', ['page' => 'contact']) }}" class="footer-link">Contact</a>
    </div>
    <div class="footer-copyright">&copy;2016-{{ date('Y') }} {{ config('app.name') }}</div>
    <div class="footer-social-icons">
        <a href="{{ config('blox.socials.twitter') }}" class="footer-social-icon" title="Follow us on Twitter!" target="_blank" data-tooltip>
            <img src="{{ asset('img/footer/twitter.png') }}">
        </a>
        <a href="{{ config('blox.socials.discord') }}" class="footer-social-icon" title="Join our Discord!" target="_blank" data-tooltip>
            <img src="{{ asset('img/footer/discord.png') }}">
        </a>
    </div>
</footer>