<x-app-layout>
    <x-slot name="title">Marketplace</x-slot>
    <x-slot name="navigation"></x-slot>
<body class="market-page">
<div id="app">

<script type="text/javascript">
  function handleSelect(elm)
  {
     window.location = "/market?sort="+elm.value;
  }
</script>

<div class="grid-x grid-margin-x mb-25">
<div class="auto cell">
<div class="market-header">Market</div>
</div>
<div class="shrink cell">
<a href="{{ route('market.create.index') }}" class="button button-green">Create</a>
<a href="/market" class="button button-blue">Home</a>
</div>
</div>
<div class="grid-x grid-margin-x mb-15">
<div class="cell small-12 medium-2">
<select class="form-input" onchange="javascript:handleSelect(this)">
<option value="recent" selected>Recent</option>
<option value="hats">Hats</option>
<option value="faces">Faces</option>
<option value="accessories">Accessories</option>
<option value="shirts">Shirts</option>
<option value="pants">Pants</option>
</select>
</div>
<div class="cell small-12 medium-10">
<div class="push-5 show-for-small-only"></div>
<input class="form-input" id="search" type="text" placeholder="Search and press enter">
</div>
</div>
<div class="market-header" id="header">Recent</div>
<div class="container">
<div class="market-search-results" id="results-for" style="display:none;"></div>
<div id="market-data" class="grid-x grid-margin-x">
@include('components.load_market')
</div>

</div>
</div>
</div>
</div>
</div>
    <x-slot name="script">
        <script>
            var query = window.location.search;
            var pageUrl = '';
            if(query) {
                pageUrl = query+'&page=';
            } else {
                pageUrl = '?page=';
            }
            function loadMoreData(page) {
                $.ajax({
                    url:pageUrl+''+page,
                    type:'get',
                })
                    .done(function(data) {
                        if(data.html == " ") {
                            return;
                        }
                        $("#market-data").append(data.html);
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError) {
                        console.log("Server not responding...");
                    });
            }

            var page = 1;
            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    page++;
                    loadMoreData(page);
                }
            });
        </script>
    </x-slot>
</x-app-layout>
