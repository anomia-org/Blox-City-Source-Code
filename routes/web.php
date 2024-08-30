<?php

use App\Http\Controllers\AIS\AdminController;
use App\Http\Controllers\AIS\AssetApprovalController;
use App\Http\Controllers\AIS\Itemscontroller;
use App\Http\Controllers\AIS\AssetsController;
use App\Http\Controllers\AIS\BanController;
use App\Http\Controllers\AIS\CreateItemsController;
use App\Http\Controllers\AIS\UsersController;
use App\Http\Controllers\AIS\GroupsController;
use App\Http\Controllers\AIS\ForumsController;
use App\Http\Controllers\AIS\ExecutiveController;
use App\Http\Controllers\AIS\ManageUserController;
use App\Http\Controllers\AIS\SiteController;
use App\Http\Controllers\AIS\StaffController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GuildsController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\API\AvatarsController;
use App\Http\Controllers\API\UsersApiController;
use App\Http\Controllers\BansController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\User\AdvertismentController;
use App\Http\Controllers\User\CustomizationController;
use App\Http\Controllers\User\MessageController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\PromocodesController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UpgradesController;
use App\Http\Controllers\User\CreatorPanelController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Main website */

Route::domain('www.bloxcity.com')->group(function () {
    Route::middleware([])->group(function () {
        /* Authentication Scaffolding */
        Auth::routes(['verify' => true]);

        /* Discord OAuth */
        Route::controller(DiscordController::class)->group(function () {
            Route::middleware(['verified'])->group(function () {
                Route::group(['prefix' => 'discord'], function() {
                    Route::redirect('/connect', 'https://discord.com/oauth2/authorize?client_id=' . config('discord.client_id')
                        . '&redirect_uri=' . config('discord.redirect_uri')
                        . '&response_type=code&scope=' . implode('%20', explode('&', config('discord.scopes')))
                        . '&prompt=' . config('discord.prompt', 'consent'))
                        ->name('discord.connect');

                    Route::get('/callback', 'handle')
                        ->name('discord.login');

                    Route::redirect('/refresh-token', '/discord/connect')
                        ->name('discord.refresh_token');

                    Route::post('/unlink', 'unlink')
                        ->name('discord.unlink')->middleware(['throttle:15,1']);
                });
            });
        });

        Route::controller(BansController::class)->group(function () {
            Route::middleware(['auth'])->group(function () {
                Route::get('/account/suspended', 'index')->name('suspended');
                Route::post('/account/suspended/reactivate', 'reactivate')->name('suspended.reactivate')->middleware(['throttle:15,1']);
            });
        });

        /* User-related routing handled by UserController */
        Route::controller(UserController::class)->group(function () {
            /* Guest routes */
            Route::get('/', 'index')->name('index');
            Route::get('/site/offline', 'maintenance')->name('maintenance.index');
            Route::get('/user/{user}', 'profile')->name('user.profile');
            Route::get('/user/{user}/friends', 'friends')->name('user.friends');
            Route::get('/user/{user}/inventory', 'inventory')->name('user.inventory');
            Route::get('/user/{user}/groups', 'groups')->name('user.groups');
            Route::get('/user/{user}/blurb', 'blurb')->name('user.blurb')->middleware(['throttle:15,1']);
            Route::get('/users/search', 'search')->name('users.search')->middleware(['throttle:15,1']);
            Route::get('/users/online', 'online')->name('users.online');
            Route::get('/achievements', 'achievements')->name('achievements');

            /* verified routes */
            Route::post('/money/trade', 'trade_currency')->name('user.trade.currency')->middleware('verified');

            /* Authenticated routes */
            Route::middleware(['auth'])->group(function () {
                Route::get('/dashboard', 'dashboard')->name('dashboard');
                Route::get('/friends', 'my_friends')->name('user.myfriends');
                Route::get('/account/settings', 'settings')->name('user.settings');
                Route::get('/money', 'money')->name('user.money');
                /**
                 * Creator Panel
                 */
                Route::controller(CreatorPanelController::class)->group(function ()
                {
                    Route::get('/creator-area', 'index')->name('user.creator-area');
                    Route::get('/creator-area/shirts', 'shirts')->name('user.creator-area.shirts');
                    Route::get('/creator-area/pants', 'pants')->name('user.creator-area.pants');
                    Route::get('/creator-area/ads', 'ads')->name('user.creator-area.ads');
                });

                // Customization APIs
                Route::get('/users/avatar/wearing', 'avatar_wearing')->name('user.avatar.wearing')->middleware('throttle:render');;
                Route::get('/users/avatar/inventory', 'avatar_inventory')->name('user.avatar.inventory')->middleware('throttle:render');;
                Route::post('/users/avatar/update', 'avatar_update')->name('user.avatar.update')->middleware('throttle:render');;
                Route::get('/users/avatar/src', 'avatar_src')->name('user.avatar.src')->middleware('throttle:render');;


                Route::post('/account/blurb/update', 'post_blurb')->name('user.blurb.update')->middleware(['throttle:15,1']);
                
                Route::post('/account/settings', 'settings_update')->name('user.settings.update')->middleware(['throttle:15,1']);
                Route::post('/account/settings/logout_all', 'logout_other_sessions')->name('user.settings.logoutall')->middleware(['throttle:15,1']);
                Route::post('/friends/{user}/accept', 'accept_friend')->name('user.friends.accept')->middleware(['throttle:15,1']);
                Route::post('/friends/accept', 'accept_all_friends')->name('user.friends.accept.all')->middleware(['throttle:15,1']);
                Route::post('/friends/{user}/decline', 'decline_friend')->name('user.friends.decline')->middleware(['throttle:15,1']);
                Route::post('/friends/decline', 'decline_all_friends')->name('user.friends.decline.all')->middleware(['throttle:15,1']);
                Route::post('/friends/{user}/remove', 'remove_friend')->name('user.friends.remove')->middleware(['throttle:15,1']);
                Route::post('/friends/{user}/add', 'add_friend')->name('user.friends.add')->middleware(['throttle:15,1']);

                Route::post('/me/verification-notification', function () {
                    auth()->user()->sendEmailVerificationNotification();
                
                    return back()->with('success', 'Verification link sent!');
                })->name('verification.send')->middleware('throttle:20,15');
                Route::post('/me/notifications-read', 'notifs_read')->name('user.notifications.read')->middleware(['throttle:15,1']);
                Route::get('/me/notifications', 'notifications')->name('notifications')->middleware(['throttle:15,1']);
            });
        });

        /* Customization-related routing handled by User/CustomizationController */
        Route::controller(CustomizationController::class)->middleware(['verified'])->group(function() {
            Route::get('/character/customize', 'index')->name('customize.index');

            Route::post('/character/customize/color', 'color')->name('customize.color')->middleware(['throttle:15,1']);
            Route::get('/character/customize/equip/{item}', 'equip')->name('customize.equip')->middleware(['throttle:15,1']);
            Route::get('/character/customize/unequip/{item}', 'unequip')->name('customize.unequip')->middleware(['throttle:15,1']);
            Route::post('/character/customize/avatar', 'avatar')->name('customize.avatar')->middleware(['throttle:15,1']);
            Route::post('/character/customize/orient', 'orient')->name('customize.orient')->middleware(['throttle:15,1']);
        });

        /* Advertisement-related routing handled by User/AdvertismentController */
        Route::controller(AdvertismentController::class)->middleware(['verified'])->group(function() {
            Route::get('/creator-area/advertise/{id}', 'advertise_view')->name('ad.creator-area.advertise.view');
            Route::post('/creator-area/advertise/{id}', 'advertise')->name('ad.creator-area.advertise')->middleware(['throttle:15,1']);
            Route::get('/creator-area/ads/{id}/manage', 'bid_view')->name('ad.manage');
            Route::post('/creator-area/ads/{id}/manage', 'bid')->name('ad.manage.bid')->middleware(['throttle:15,1']);

            Route::get('/ads/retrieve-ad', 'show_ad')->name("ad.show");
            Route::get('/ads/take-down/{id}', 'takedown')->name("ad.show");
        });

        /* Messages-related routing handled by User/MessageController */
        Route::controller(MessageController::class)->middleware(['verified'])->group(function() {
            Route::get('/messages/compose/{id}', 'compose_view')->name('messages.compose.view');
            Route::post('/messages/create', 'compose')->name('messages.compose.action')->middleware(['throttle:15,1']);
            Route::get('/messages/reply/{id}', 'reply_view')->name('messages.reply.view');
            Route::post('/messages/reply', 'reply')->name('messages.reply.action')->middleware(['throttle:15,1']);

            Route::get('/messages/{type}/{read}', 'view_all')->name('messages.index');
            Route::get('/messages/{id}', 'view')->name('messages.view');
        });

        /* Forum-related routing handed by ForumController */
        Route::controller(ForumController::class)->group(function () {
            /* Guest routes */
            
            /* Authenticated routes */
            Route::middleware(['auth'])->group(function () {
                Route::get('/forum', 'index')->name('forum.index');
                Route::get('/forum/thread/{thread}', 'show_thread')->name('forum.thread');
                Route::get('/forum/topic/{topic}', 'show_topic')->name('forum.topic');

                

                /* Forum Moderation */
                Route::get('/forum/thread/{thread}/move', 'show_move')->name('forum.thread.move');
                Route::post('/forum/thread/{thread}/lock', 'lock_thread')->name('forum.thread.lock');
                Route::post('/forum/thread/{thread}/pin', 'pin_thread')->name('forum.thread.pin');
                Route::post('/forum/thread/{thread}/move', 'move_thread')->name('forum.thread.move.post');
                Route::post('/forum/thread/{thread}/delete', 'delete_thread')->name('forum.thread.delete');
                Route::post('/forum/thread/{thread}/scrub', 'scrub_thread')->name('forum.thread.scrub');
                Route::post('/forum/reply/{reply}/scrub', 'scrub_reply')->name('forum.reply.scrub');
            });

            /* Verified Routes */

            /* Likes */
            Route::post('/forum/thread/{thread}/like', 'thread_like')->middleware('verified')->middleware(['throttle:15,1']);
            Route::post('/forum/reply/{reply}/like', 'reply_like')->middleware('verified')->middleware(['throttle:15,1']);
            Route::get('/forum/create', 'create_thread')->name('forum.thread.create')->middleware('verified');
            Route::get('/forum/thread/{thread}/reply', 'create_reply')->name('forum.thread.reply')->middleware('verified');
            Route::get('/forum/thread/{thread}/quote/{quote_id}/{quote_type}', 'show_quote')->name('forum.thread.quote')->middleware('verified');
            Route::post('/forum/create', 'store_thread')->name('forum.thread.create.post')->middleware('verified')->middleware(['throttle:15,1']);;
            Route::post('/forum/thread/{thread}/reply', 'store_reply')->name('forum.thread.reply.post')->middleware('verified')->middleware(['throttle:15,1']);;
            Route::post('/forum/thread/{thread}/quote/{quote_id}/{quote_type}', 'store_quote')->name('forum.thread.quote.post')->middleware('verified')->middleware(['throttle:15,1']);;
        });

        /* Reports-related routing handled by ReportController */
        Route::controller(ReportController::class)->group(function () {
            /* Guest routes */
            /* Authenticated routes */
            Route::middleware(['verified'])->group(function () {
                /* Frontend Views */
                Route::get('/thread/{thread}/report', 'report_thread')->name('report.threads');
                Route::get('/reply/{reply}/report', 'report_reply')->name('report.reply');
                Route::get('/user/{user}/report', 'report_user')->name('report.user');
                Route::get('/blurb/{blurb}/report', 'report_blurb')->name('report.blurb');
                Route::get('/item/{item}/report', 'report_item')->name('report.item');
                Route::get('/comment/{comment}/report', 'report_comment')->name('report.comment');
                Route::get('/message/{message}/report', 'report_message')->name('report.message');
                Route::get('/guilds/{guild}/report', 'report_guild')->name('report.guild');
                Route::get('/guilds/wall/{post}/report', 'report_wall_post')->name('report.wall_post');
                Route::get('/guilds/announcement/{post}/report', 'report_guild_announcement')->name('report.guild_announcement');
                Route::get('/ads/{ad}/report', 'report_ads')->name('report.ads');

                /* Handle Report Submit */
                Route::post('/thread/{thread}/report', 'submit')->name('report.threads.submit')->middleware(['throttle:15,1']);
                Route::post('/reply/{reply}/report', 'submit')->name('report.reply.submit')->middleware(['throttle:15,1']);
                Route::post('/user/{user}/report', 'submit')->name('report.user.submit')->middleware(['throttle:15,1']);
                Route::post('/blurb/{blurb}/report', 'submit')->name('report.blurb.submit')->middleware(['throttle:15,1']);
                Route::post('/item/{item}/report', 'submit')->name('report.item.submit')->middleware(['throttle:15,1']);
                Route::post('/comment/{comment}/report', 'submit')->name('report.comment.submit')->middleware(['throttle:15,1']);
                Route::post('/message/{message}/report', 'submit')->name('report.message.submit')->middleware(['throttle:15,1']);
                Route::post('/guilds/{guild}/report', 'submit')->name('report.guild.submit')->middleware(['throttle:15,1']);
                Route::post('/guilds/wall/{post}/report', 'submit')->name('report.wall_post.submit')->middleware(['throttle:15,1']);
                Route::post('/guilds/announcement/{post}/report', 'submit')->name('report.guild_announcement.submit')->middleware(['throttle:15,1']);
                Route::post('/ads/{ad}/report', 'submit')->name('report.ads.submit')->middleware(['throttle:15,1']);
            });
        });

        Route::controller(MarketController::class)->group(function () {
            /* Guest routes */
            Route::get('/market', 'index')->name('market.index')->middleware(['throttle:60,1']);
            Route::get('/market/item/{item}', 'show_item')->name('market.item')->middleware(['throttle:60,1']);
            ;
            /* Authenticated routes */
            Route::middleware(['verified'])->group(function () {
                Route::get('/market/create', 'create_item')->name('market.create.index');
                Route::get('/market/create/shirt', 'create_shirt')->name('market.create.shirt');
                Route::get('/market/create/pants', 'create_pants')->name('market.create.pants');
                Route::get('/market/{item}/edit', 'edit_item')->name('market.item.edit')->middleware(['throttle:15,1']);

                Route::post('/market/item/{item}/list', 'list')->name('market.list')->middleware(['throttle:15,1']);
                Route::post('/market/item/{item}/unlist', 'unlist')->name('market.unlist')->middleware(['throttle:15,1']);
                Route::post('/market/item/{item}/listing/{listing}/buy', 'buy_listing')->name('market.listing.buy')->middleware(['throttle:15,1']);
                Route::post('/market/item/{item}/buy/{type}', 'buy_item')->name('market.item.buy')->middleware(['throttle:15,1']); 
                Route::post('/market/item/{item}/comment', 'comment')->name('market.item.comment')->middleware(['throttle:15,1']);
                Route::post('/market/create/shirt/process', 'upload_shirt')->name('market.create.shirt.process')->middleware(['throttle:15,1']);
                Route::post('/market/create/pants/process', 'upload_pants')->name('market.create.pants.process')->middleware(['throttle:15,1']);
                Route::post('/market/{item}/edit', 'edit')->name('market.item.edit.post')->middleware(['throttle:15,1']);
                Route::post('/market/{item}/delete', 'delete')->name('market.item.delete')->middleware(['throttle:15,1']);

                /* Moderation Tools */
                Route::post('/market/comment/{comment}/scrub', 'scrub_comment')->name('market.comment.scrub');
            });
        });

        Route::controller(GuildsController::class)->group(function ()
        {
            /* Guest routes */
            Route::get('/groups/{guild}/view', 'view')->name('groups.view');
            Route::get('/groups/search', 'search')->name('groups.search');
            Route::post('/groups/search', 'search')->name('groups.search.post');
            Route::get('/groups/explore', 'explore')->name('groups.explore');
            Route::get('/groups/{guild}/members', 'members');

            /* Authenticated routes */
            Route::middleware(['verified'])->group(function ()
            {
                Route::get('/groups/{guild}/edit', 'edit')->name('groups.edit');
                Route::get('/groups/create', 'create')->name('groups.create');
                Route::get('/groups', 'index')->name('groups.index');

                Route::post('/groups/create', 'create_post')->name('groups.create.post');
                Route::post('/groups/{guild}/join', 'join_guild')->name('groups.join.post');
                Route::post('/groups/{guild}/leave', 'leave_guild')->name('groups.leave.post');
                Route::post('/groups/{guild}/update_announcement', 'announce')->name('groups.announce.post')->middleware(['check.beta']);
                Route::post('/groups/{guild}/wall/post', 'wall_post')->name('groups.wall.post')->middleware(['check.beta']);

                Route::post('/groups/{guild}/edit/general', 'edit_general')->name('groups.edit.general.post');
            });
        });

        /**
         * Notes
         */
        Route::controller(NotesController::class)->group(function ()
        {
            Route::get('/notes/{page}', 'index')->name('notes');
        });

        /**
         * Games
         */
        //Route::controller('games', 'GamesController')->group(function ()
        //{
        //    Route::get('/', 'index')->name('games.index');
        //    Route::get('/{game}', 'show')->name('games.show');
        //});

        /**
         * Promocodes
         */
        Route::controller(PromocodesController::class)->group(function ()
        {
            Route::get('/promocodes', 'index')->name('promocodes.index')->middleware('verified');
            Route::post('/promocodes-redeem', 'redeem')->name('promocodes.redeem')->middleware('verified')->middleware(['throttle:15,1']);
        });

        /**
         * Upgrades
         */
        Route::controller(UpgradesController::class)->group(function ()
        {
            Route::middleware(['verified'])->group(function ()
            {
                Route::get('/upgrade', 'index')->name('upgrade.index');
                Route::get('/upgrade/{plan}', 'show')->name('upgrade.plans');
            });
        });
    });

    Route::controller(StripeController::class)->group(function ()
        {
            Route::middleware(['verified'])->group(function ()
            {
                Route::get('/upgrade/checkout/{product}', 'createSession')->name('checkout');
            });
            Route::post('/internal/stripe/webhook', 'webhook')->name('stripe.webhook')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        });

});

/* Administrative */
Route::domain('east.bloxcity.com')->group(function()
{
    Route::middleware(['auth'])->group(function ()
    {
        Route::group(['as' => 'ais.'], function ()
        {
            Route::controller(AdminController::class)->group(function ()
            {
                Route::get('/', 'index')->name('index');
                Route::get('/info', 'info')->name('info');
                Route::get('/auth', 'auth')->name('auth');
                Route::post('/auth', 'authPost')->name('auth.post');

                //reports
                Route::group(['prefix' => 'reports'], function ()
                {
                    Route::get('/', 'reports')->name('reports');
                    Route::get('/{report}/view', 'report')->name('report');

                    Route::post('/{report}/take-action', 'reportsAction')->name('report.action');
                });

                //executive-level admin only
                Route::group(['prefix' => 'executive'], function () 
                {
                    //settings
                    Route::group(['prefix' => 'settings'], function ()
                    {
                        Route::get('/', 'settings')->name('settings');

                        Route::post('/', 'settingsUpdate')->name('settings.post');
                    });

                    //directory
                    Route::group(['prefix' => 'directory'], function ()
                    {
                        
                    });
                    
                });
            });

            Route::controller(CreateItemsController::class)->group(function ()
            {
                Route::group(['as' => 'create_item.', 'prefix' => 'create-item'], function() {
                    Route::get('/{type}', 'index')->name('index');
                    Route::post('/create', 'create')->name('create');
                });
            });

            Route::controller(ItemsController::class)->group(function ()
            {
                Route::group(['as' => 'items.', 'prefix' => 'items'], function() {
                    Route::get('/', 'index')->name('index');
                    Route::get('/view/{id}', 'view')->name('view');
                    Route::post('/update', 'update')->name('update');
                });
            });

            Route::controller(AssetApprovalController::class)->group(function ()
            {
                Route::group(['prefix' => 'asset-approval', 'as' => 'assets.'], function() {
                    Route::get('/{category}', 'index')->name('index');
                    Route::get('/', 'index');
                    Route::post('/', 'update')->name('update');
                });
            });


            Route::controller(UsersController::class)->group(function ()
            {
                Route::group(['as' => 'users.', 'prefix' => 'users'], function() {
                    Route::get('/', 'index')->name('index');
                    Route::get('/view/{id}', 'view')->name('view');
                    Route::post('/update', 'usersUpdate')->name('update');
                });
            });

            Route::controller(ManageUserController::class)->group(function ()
            {
                Route::group(['as' => 'users.', 'prefix' => 'users'], function() {
                    Route::group(['as' => 'manage.', 'prefix' => 'manage'], function() {
                        Route::get('/{type}/{id}', 'index')->name('index');
                        Route::post('/', 'update')->name('update');
                    });
                });
            });

            Route::controller(BanController::class)->group(function ()
            {
                Route::group(['as' => 'users.ban.', 'prefix' => 'ban'], function() {
                    Route::get('/{id}', 'index')->name('index');
                    Route::post('/', 'create')->name('create');
                });
            });

            Route::group(['as' => 'manage.', 'prefix' => 'manage'], function() {
                Route::group(['as' => 'forum_topics.', 'prefix' => 'forum-topics'], function() {
                    Route::controller(ForumsController::class)->group(function ()
                    {
                        Route::get('/', 'index')->name('index');;
                        Route::get('/new', 'new')->name('new');
                        Route::post('/create', 'create')->name('create');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::post('/edit', 'update')->name('update');
                        Route::get('/delete/{id}', 'confirmDelete')->name('confirm_delete');
                        Route::post('/delete', 'delete')->name('delete');
                    });
                });
            
                Route::group(['as' => 'staff.', 'prefix' => 'staff'], function() {
                    Route::controller(StaffController::class)->group(function ()
                    {
                        Route::get('/', 'index')->name('index');
                        Route::get('/new', 'new')->name('new');
                        Route::post('/create', 'create')->name('create');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::post('/update', 'update')->name('update');
                    });
                });
            
                Route::group(['as' => 'site.', 'prefix' => 'site'], function() {
                    Route::controller(SiteController::class)->group(function ()
                    {
                        Route::get('/', 'index')->name('index');
                        Route::post('/update', 'update')->name('update');
                    });
                });
            });
        });
    });
});

/* APIs */
/* This section will be opened and improved upon later once we start doing public APIs, for now it just holds the Avatars and basic things to call upon elsewhere */
Route::domain('avatar.bloxcity.com')->group(function() {
    Route::controller(AvatarsController::class)->group(function ()
    {
        Route::get('/', 'index');
        /*
        * BLOX City Avatar APIs v1.0.0; originally created April 18th, 2021 at 4:30AM for BLOXCity.com
        */
        Route::group(['prefix' => 'v1'], function() {
            Route::get('/', 'v1');
            Route::get('/render/{user}', 'render')->middleware('throttle:render');
            Route::get('/headshot/{user}', 'headshot')->middleware('throttle:render');
            Route::get('/market/{item}', 'market')->middleware('throttle:render');
        });
        
    });
    
});

Route::domain('users.bloxcity.com')->group(function() {
    Route::controller(UsersApiController::class)->group(function ()
    {
        Route::get('/', 'index');
        Route::group(['prefix' => 'v1'], function() {
            Route::get('/', 'v1');
            Route::get('/discord/{discord}', 'discord');
        });
    });
});