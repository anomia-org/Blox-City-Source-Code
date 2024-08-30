<x-app-layout>
    <meta name="community-info" data-id="{{ $guild->id }}">
    <x-slot name="pageDescription">{{ $guild->name }} is one of thousands of groups on BLOXCity.com, the fastest growing user-generated sandbox platform. Join us today!</x-slot>
    <x-slot name="pageImage">{{ $guild->thumbnail() }}</x-slot>
    <x-slot name="title">{{ $guild->name }}</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="group-page">
        <div class="container mb-25">
            <div class="grid-x grid-margin-x">
                <div class="cell small-12 medium-3 text-center">
                    <img src="{{ $guild->thumbnail() }}" class="group-icon">
                    <div class="push-25"></div>
                    @auth
                        @if(auth()->user()->isInGuild($guild->id) && auth()->user()->id != $guild->owner->id)
                            <form method="POST" action="{{ route('groups.leave.post', $guild->id) }}">
                                @csrf
                                <button type="submit" class="button button-block button-red">Leave</button>
                            </form>
                        @elseif(!auth()->user()->isInGuild($guild->id))
                            <form method="POST" action="{{ route('groups.join.post', $guild->id) }}">
                                @csrf
                                <button type="submit" class="button button-block button-green">Join</button>
                            </form>
                        @endif
                        @if(auth()->user()->isInGuild($guild->id) && (auth()->user()->rankInGuild($guild->id)->can_view_audit || auth()->user()->rankInGuild($guild->id)->can_change_ranks || auth()->user()->rankInGuild($guild->id)->can_kick_members || auth()->user()->rankInGuild($guild->id)->can_accept_members || auth()->user()->rankInGuild($guild->id)->can_spend_funds || auth()->user()->rankInGuild($guild->id)->can_create_items || auth()->user()->rankInGuild($guild->id)->can_edit_games))
                            <div class="push-10"></div>
                            <a href="{{ route('groups.edit', $guild) }}" class="button button-block button-blue">Manage</a>
                        @endif
                    @endauth
                </div>
                <div class="cell small-12 medium-9">
                    <div class="group-name">{{ $guild->name }}</div>
                    <div class="group-description" style="white-space: pre-line">
                        @if($guild->desc != null)
                            {{ $guild->desc }}
                        @else
                            No description set.
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="container group-stats-container mb-25">
            <div class="grid-x grid-margin-x">
                <div class="cell small-12 medium-4">
                    <div class="group-stat-result"><a href="{{ route('user.profile', $guild->owner->id) }}">{{ $guild->owner->username }}</a></div>
                    <div class="group-stat-name">Owner</div>
                    <div class="push-15 show-for-small-only"></div>
                </div>
                <div class="cell small-12 medium-4">
                    <div class="group-stat-result">{{ $guild->get_short_num($guild->members()->count()) }}</div>
                    <div class="group-stat-name">Members</div>
                    <div class="push-15 show-for-small-only"></div>
                </div>
                <div class="cell small-12 medium-4">
                    @if($guild->is_vault_viewable)
                        <div class="group-stat-result">
                            @if($guild->is_vault_viewable)
                                <span>
                                    <i class="currency currency-cash currency-md currency-align"></i> {{ $guild->get_short_num($guild->cash) }}
                                </span>
                                &nbsp;
                                <span>
                                    <i class="currency currency-coin currency-lg currency-align"></i> {{ $guild->get_short_num($guild->coins) }}
                                </span>
                            @endif
                        </div>
                    @endif
                    <div class="group-stat-name">Vault</div>
                </div>
            </div>
        </div>
        <div class="container group-members-container">
            <div class="grid-x grid-margin-x align-middle">
                <div class="cell auto">
                    <h5>Members</h5>
                    <div class="push-10"></div>
                </div>
                <div class="cell shrink">
                    <select class="form-input" id="rankSelect">
                        @foreach ($guild->ranks() as $rank)
                            <option value="{{ $rank->rank }}">{{ $rank->name }} ({{ $rank->memberCount() }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid-x grid-margin-x" id="members-data">
            </div>
        </div>
    </body>

    <x-slot name="script">
        <script>
            var id = parseInt($('meta[name="community-info"]').attr('data-id'));
            $(() => {
                getMembers(1, 1);

                $('#rankSelect').change(function() {
                    getMembers($(this).val(), 1);
                });
            });

            function getMembers(rank, page) {
                $.get('members', { id, rank, page }).done((data) => {
                    $('#members-data').html('');

                    if (typeof data.error !== 'undefined')
                        return $('#members-data').html(`<div class="col">${data.error}</div>`);

                    $.each(data.members, function() {
                        $('#members-data').append(`
                            <div class="cell small-6 medium-2 group-member">
                                <a href="${this.url}">
                                    <img src="${this.thumbnail}" class="group-member-avatar" />
                                </a>
                                <div class="push-5"></div>
                                <a href="${this.url}">${this.username}</a>
                            </div>`);
                    });

                    if (data.total_pages > 1) {
                        const previousDisabled = (data.current_page == 1) ? 'disabled' : '';
                        const nextDisabled = (data.current_page == data.total_pages) ? 'disabled' : '';
                        const previousPage = data.current_page - 1;
                        const nextPage = data.current_page + 1;

                        $('#members-data').append(`
                            <div class="cell small-12 text-center">
                                <button class="button button-red" onclick="getMembers(${rank}, ${previousPage})" ${previousDisabled} aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </button>
                                <span class="text-muted mx-2 mt-1">${data.current_page} of ${data.total_pages}</span>
                                <button class="button button-green" onclick="getMembers(${rank}, ${nextPage})" ${nextDisabled} aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </button>
                            </li>`);
                    }
                }).fail(() => $('#members-data').html('<div class="col">Unable to get members.</div>'));
            }
        </script>
    </x-slot>
</x-app-layout>