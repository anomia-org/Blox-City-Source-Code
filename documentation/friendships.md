## Friendships Documentation

#### Send a Friend Request
```php
$user->befriend($recipient);
```

#### Accept a Friend Request
```php
$user->acceptFriendRequest($sender);
```

#### Deny a Friend Request
```php
$user->denyFriendRequest($sender);
```

#### Remove Friend
```php
$user->unfriend($friend);
```

#### Block a Model (User)
```php
$user->blockFriend($friend);
```

#### Unblock a Model (User)
```php
$user->unblockFriend($friend);
```

#### Check if Model (User) is Friend with another Model (User)
```php
$user->isFriendWith($friend);
```


#### Check if Model (User) has a pending friend request from another Model (User)
```php
$user->hasFriendRequestFrom($sender);
```

#### Check if Model (User) has already sent a friend request to another Model (User)
```php
$user->hasSentFriendRequestTo($recipient);
```

#### Check if Model (User) has blocked another Model (User)
```php
$user->hasBlocked($friend);
```

#### Check if Model (User) is blocked by another Model (User)
```php
$user->isBlockedBy($friend);
```

#### Get a single friendship
```php
$user->getFriendship($friend);
```

#### Get a list of all Friendships
```php
$user->getAllFriendships();
```

#### Get a list of pending Friendships
```php
$user->getPendingFriendships();
```

#### Get a list of accepted Friendships
```php
$user->getAcceptedFriendships();
```

#### Get a list of denied Friendships
```php
$user->getDeniedFriendships();
```

#### Get a list of blocked Friendships
```php
$user->getBlockedFriendships();
```

#### Get a list of pending Friend Requests
```php
$user->getFriendRequests();
```

#### Get the number of Friends
```php
$user->getFriendsCount();
```
#### Get the number of Pending Friend Requests
```php
$user->getPendingsCount();
```

#### Get the number of mutual Friends with another user
```php
$user->getMutualFriendsCount($otherUser);
```

## Friends
To get a collection of friend models (User) use the following methods:
#### Get Friends
```php
$user->getFriends();
```

#### Get Friends Paginated
```php
$user->getFriends($perPage = 20);
```

#### Get Friends of Friends
```php
$user->getFriendsOfFriends($perPage = 20);
```

#### Get mutual Friends with another user
```php
$user->getMutualFriends($otherUser, $perPage = 20);
```

## Events
This is the list of the events fired by default for each action

|Event name            |Fired                            |
|----------------------|---------------------------------|
|friendships.sent      |When a friend request is sent    |
|friendships.accepted  |When a friend request is accepted|
|friendships.denied    |When a friend request is denied  |
|friendships.blocked   |When a friend is blocked         |
|friendships.unblocked |When a friend is unblocked       |
|friendships.cancelled |When a friendship is cancelled   |
