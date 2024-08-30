<?php


use App\Models\Item;

return [
    'item_notifier' => [
        'enabled' => true,
        'webhook' => 'https://discord.com/api/webhooks/1247422520349556757/3z94KZcF0h4n7P2gpXmSvVp-5B1opjc1rlwpMlaCGx_5QHfqZMvgvHkRNz6p1EJx282M',
    ],
    'domains' => [
        'blog' => 'https://blog.bloxcity.com',
        'corp' => 'https://corp.bloxcity.com'
    ],
    'emails' => [
        'support' => 'hello@bloxcity.com',
        'moderation' => 'moderation@bloxcity.com',
        'careers' => 'jobs@bloxcity.com',
        'payments' => 'payments@bloxcity.com'
    ],
    'socials' => [
        'discord' => 'https://discord.gg/6GFAa2faMg',
        'twitter' => 'https://x.com/joinbloxcity',
        'youtube' => 'https://www.youtube.com/u/BLOXCity',
        'instagram' => 'https://www.instagram.com/bloxcity/',
        'threads' => 'https://www.threads.net/@bloxcity',
    ],
    'maintenance_passcodes' => [
        '2cdfedb-297Af-4aa1-b6cedf'
    ],
    'achievements' => [
        /**
         * Special
         */
        'admin' => [
            'name' => 'Administrator',
            'description' => 'Players who possess this achievement are official administrators. Administrators are members of staff who oversee the website and keep it running smoothly.',
            'image' => '/img/badges/admins.png',
            'type' => 'special'
        ],
        'asset_helper' => [
            'name' => 'Needle Worker',
            'description' => 'Players who possess this achievement have had one or more of their items uploaded to the market. Their creation abilities are endorsed and recognized by staff.',
            'image' => '/img/badges/needleWorker.png',
            'type' => 'special'
        ],
        'endorsed' => [
            'name' => 'Endorsed Player',
            'description' => 'Players who possess this achievement are known by administrators for their helpfulness in the community. This achievement is only obtainable if an admin grants it to you and is not available upon request.',
            'image' => '/img/badges/endorsed.png',
            'type' => 'special'
        ],
        'beta' => [
            'name' => 'Beta Tester',
            'description' => 'These are not your everyday BLOX Citizens! They help us with finding bugs on our website and our games when we plan on releasing a new update! Please note that these players are not staff members.',
            'image' => '/img/badges/beta.png',
            'type' => 'special'
        ],
        /**
         * Membership
         */
        'VIP_1' => [
            'name' => 'Bronze VIP Membership',
            'description' => 'Players who possess this achievement have a Bronze VIP Membership. To view details about this special package or other account upgrades, visit our <a href="/upgrade">upgrade page</a>.',
            'image' => '/img/badges/bronzeVip.png',
            'type' => 'membership'
        ],
        'VIP_2' => [
            'name' => 'Silver VIP Membership',
            'description' => 'Players who possess this achievement have a Silver VIP Membership. To view details about this special package or other account upgrades, visit our <a href="/upgrade">upgrade page</a>.',
            'image' => '/img/badges/silverVip.png',
            'type' => 'membership'
        ],
        'VIP_3' => [
            'name' => 'Gold VIP Membership',
            'description' => 'Players who possess this achievement have a Gold VIP Membership. To view details about this special package or other account upgrades, visit our <a href="/upgrade">upgrade page</a>.',
            'image' => '/img/badges/goldVip.png',
            'type' => 'membership'
        ],
        'VIP_ELITE' => [
            'name' => 'Elite Order',
            'description' => 'Players who possess this achievement have owned all three tiers of membership. To view details about this special package or other account upgrades, visit our <a href="/upgrade">upgrade page</a>.',
            'image' => '/img/badges/eliteVip.png',
            'type' => 'membership'
        ],
        /**
         * General
         */
        'collector' => [
            'name' => 'Collector',
            'description' => 'Players who possess this achievement have obtained at least ten collectible items on their account and are generally pretty experienced within the marketplace.',
            'image' => '/img/badges/secret_badge_of_secrecy.png',
            'type' => 'general'
        ],
        'rich' => [
            'name' => 'Gold Mine',
            'description' => 'Players who possess this achievement have reached at least $5,000 cash on their account. These users are often experienced in the stock market.',
            'image' => '/img/badges/secret_badge_of_secrecy.png',
            'type' => 'general'
        ],
        'stockpiler' => [
            'name' => 'Stockpiler',
            'description' => 'Players who possess this achievement have thirty or more shop items, collectible or non-collectible, in their inventory.',
            'image' => '/img/badges/secret_badge_of_secrecy.png',
            'type' => 'general'
        ],
        'inviter' => [
            'name' => 'Inviter',
            'description' => 'Players who possess this achievement have referred at least five users to that have signed up with their referral code.',
            'image' => '/img/badges/inviter.png',
            'type' => 'general'
        ],
        'forumer' => [
            'name' => 'Forumer',
            'description' => 'Players who possess this achievements have made 500 or more forum posts.',
            'image' => '/img/badges/secret_badge_of_secrecy.png',
            'type' => 'general'
        ],
        'pro-forumer' => [
            'name' => 'Pro Forumer',
            'description' => 'Players who possess this achievement have made 1,000 or more forum posts.',
            'image' => '/img/badges/secret_badge_of_secrecy.png',
            'type' => 'general'
        ],
        'veteran' => [
            'name' => 'Oldieblox',
            'description' => 'Players who possess this achievement have been a part of for at least one year. These players are often experienced and have semi-long beards of wiseness.',
            'image' => '/img/badges/veteran.png',
            'type' => 'general'
        ],
    ],
];