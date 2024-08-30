<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>
    <div class="alert bg-danger text-white" id="alert" style="display:none;"></div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Ban User</div>
                <div class="card-body">
                    <form action="{{ route('ais.users.ban.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <label for="length">Length</label>
                        <select class="form-control mb-2" name="length" required>
                            @foreach ($lengths as $name => $value)
                                <option value="{{ $value }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <label for="category">Category</label>
                        <select class="form-control mb-2" name="category" required>
                            @foreach ($categories as $name => $value)
                                <option value="{{ $value }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <label for="content">Content (optional)</label>
                        <textarea class="form-control mb-3" name="content" placeholder="Content" rows="5"></textarea>
                        <label for="note">Note (optional)</label>
                        <textarea class="form-control mb-3" name="note" placeholder="Note" rows="5"></textarea>
                        <button class="red" type="submit">Ban User</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Auto Fill</div>
                <div class="card-body" id="presets"></div>
            </div>
        </div>
    </div>
    <x-slot name="script">
        <script>
            var siteInfo = {};

            $(() => {
                const siteInfoMeta = $('meta[name="site-info"]');
                siteInfo.name = siteInfoMeta.attr('data-name');

                const presetsArray = [
                    'CREATED_TO_BREAK_RULES',
                    'CREATED_TO_BREAK_RULES_HISTORY',
                    'COIN_FARMING',
                    'INAPPROPRIATE_LINKS'
                ];

                const presets = {
                    CREATED_TO_BREAK_RULES: {
                        title: 'Created to Break Rules',
                        length: 'closed',
                        category: 'none',
                        content: 'N/A',
                        note: `Do not sign up to BLOX City with intent on breaking our rules.`
                    },

                    CREATED_TO_BREAK_RULES_HISTORY: {
                        title: 'Refusal to follow TOS + Ban History',
                        length: 'closed',
                        category: 'none',
                        content: 'N/A',
                        note: `Do not sign up to BLOX City with intent on breaking our rules. Because of your extensive ban history and refusal to follow our Terms of Service, your account has been terminated.`
                    },

                    COIN_FARMING: {
                        title: 'Coin Farming',
                        length: 'closed',
                        category: 'coin_farming',
                        content: 'N/A',
                        note: 'Do not use currency generated by alternative accounts for your own gain.'
                    },

                    INAPPROPRIATE_LINKS: {
                        title: 'Inappropriate Links',
                        length: 'warning',
                        category: 'inappropriate_links',
                        content: 'N/A',
                        note: 'Do not post inappropriate links.'
                    }
                };

                presetsArray.forEach((preset) => $('#presets').append(`<button class="blue small w-100 mb-2" data-auto-fill="${preset}">${presets[preset].title}</button>`));

                $('[data-auto-fill]').click(function() {
                    const preset = presets[$(this).attr('data-auto-fill')];
                    var messages = [];

                    $('#alert').empty().hide();

                    if (typeof preset['note'] !== 'undefined')
                        $('textarea[name="note"]').val(preset['note']);
                    else
                        messages.push('You still need to provide a note for this preset.');

                    if (typeof preset['content'] !== 'undefined')
                        $('textarea[name="content"]').val(preset['content']);
                    else
                        messages.push('You still need to provide a content for this preset.');

                    if (typeof preset['length'] !== 'undefined')
                        $('select[name="length"]').val(preset['length']);
                    else
                        messages.push('You still need to provide a length for this preset.');

                    if (typeof preset['category'] !== 'undefined')
                        $('select[name="category"]').val(preset['category']);
                    else
                        messages.push('You still need to provide a category for this preset.');

                    if (messages.length) {
                        var output = '';

                        messages.forEach((message) => output += `<div>${message}</div>`);

                        $('#alert').html(output).show();
                    }
                });
            });
        </script>
    </x-slot>
</x-admin-layout>