<?php

namespace Database\Seeders;

use App\Models\Blurb;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Friendship;
use App\Models\Guild;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Privacy;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;
use Database\Factories\GuildJoinRequestsFactory;
use Database\Factories\ThreadFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        Thread::factory(15)->create();
        Category::factory(1)->create();
        Reply::factory(30)->create();
        Topic::factory(5)->create();
        Privacy::factory(10)->create();
        Blurb::factory(50)->create();
        Friendship::factory(20)->create();
        Item::factory(500)->create();
        Inventory::factory(500)->create();
        Comment::factory(1000)->create();
        Guild::factory(200)->create();
    }
}
