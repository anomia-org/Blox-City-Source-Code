<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use App\Helpers\Ghost;

/**
 * Class GhostBlog
 * @see https://github.com/m1guelpf/php-ghost-api#php-ghost-api-client
 */
class GhostBlog
{
    private $api;

    public function __construct()
    {
        $this->api = new Ghost(
            config('services.ghost.api_url'), // ac_ghost is the container_name
            config('services.ghost.content_key'),
        );
    }

    /**
     * @param int $latest
     * @return array
     */
    public static function latest(int $latest): array
    {
        $cache_key = 'ghost_latest_' . $latest;

        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }
        
        $ghost = new self();
        $response = $ghost->api->getPosts('', '', '', strval($latest));

        try {
            $posts = $ghost->canonicalisePosts($response);
        } catch (\Exception $e) {
            $posts = [];
        } finally {
            Cache::put($cache_key, $posts, 60);
            return $posts;
        }
    }

    /**
     * @param array $response
     * @return array
     * @throws \Exception
     */
    private function canonicalisePosts(array $response): array
    {
        $posts = [];
        if (!array_key_exists('posts', $response)) {
            throw new \Exception('Could not find posts.');
        }
        foreach ($response['posts'] as $post) {
            $posts[] = [
                'title' => $post['title'],
                'created_at' => (new \DateTime($post['created_at']))
                    ->format('d/m/Y H:i:s'),
                'url' => $post['url'],
                'excerpt' => substr(
                    preg_replace(
                        '/\\n/',
                        ' ',
                        $post['excerpt']
                    ),
                    0,
                    100
                ),
            ];
        }
        return $posts;
    }
}