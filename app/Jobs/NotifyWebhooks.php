<?php

namespace App\Jobs;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class NotifyWebhooks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;
    public $is_new;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $isNew)
    {
        $this->id = $id;
        $this->is_new = $isNew;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $item = Item::where('id', '=', $this->id);

        if ($item->exists() && $this->is_new) {
            $item = $item->first();
            $site = config('app.name');
            $webhook = 'https://discord.com/api/webhooks/1247422520349556757/3z94KZcF0h4n7P2gpXmSvVp-5B1opjc1rlwpMlaCGx_5QHfqZMvgvHkRNz6p1EJx282M';
            $url = route('market.item', $item);
            $prices = [];
            $embed = [
                'title' => $item->name,
                'url' => $url,
                'description' => "[View on {$site}]({$url})",
                'thumbnail' => ['url' => $item->get_render()],
            ];

            $message = 'New item!';

            if ($item->special)
                $message = 'New collectible item!';
            else if ($item->offsale_at != null)
                $message = 'New timed item!';

            if ($item->cash != 0 || $item->coins != 0) {
                if ($item->cash > 0)
                    $prices[] = [
                        'inline' => true,
                        'name' => 'Cash',
                        'value' => $item->cash
                    ];

                if ($item->coins > 0)
                    $prices[] = [
                        'inline' => true,
                        'name' => 'Coins',
                        'value' => $item->coins
                    ];
                if ($item->free())
                    $prices[] = [
                        'inline' => true,
                        'name' => 'Free',
                        'value' => '0'
                    ];
            }

            if (!empty($prices))
                $embed['fields'] = $prices;

            sleep(5);
            Http::post($webhook, [
                'content' => "@here {$message}",
                'embeds' => [$embed]
            ]);
        }
    }
}
