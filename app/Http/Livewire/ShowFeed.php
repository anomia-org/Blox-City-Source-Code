<?php

namespace App\Http\Livewire;

use App\Models\Blurb;
use App\Models\Feed;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowFeed extends Component
{
    public $blurb;

    protected $rules = [
        'blurb' => 'required|min:3|max:120',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function add()
    {
        $validatedData = $this->validate();
        Blurb::create([
            'author_id' => auth()->user()->id,
            'text' => $validatedData['blurb'],
        ]);

        return back();
    }

    public function render()
    {
        return view('livewire.show-feed', [
            'blurbs' =>  auth()->user()->get_feed(),
        ]);
    }
}
