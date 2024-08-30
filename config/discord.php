<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application ID
    |--------------------------------------------------------------------------
    |
    | This is the ID of your Discord application.
    |
    */
    'client_id' => '693957848111251467',

    /*
    |--------------------------------------------------------------------------
    | Application Secret
    |--------------------------------------------------------------------------
    |
    | This is the secret of your Discord application.
    |
    */
    'client_secret' => 'ZfgvcKUUBNVDupesc6GrJ737bHf82mL6',

    /*
    |--------------------------------------------------------------------------
    | Grant Type
    |--------------------------------------------------------------------------
    |
    | This is the grant type of your Discord application. It must be set to "authorization_code"
    |
    */
    'grant_type' => 'authorization_code',

    /*
    |--------------------------------------------------------------------------
    | Redirect URI
    |--------------------------------------------------------------------------
    |
    | This is the URI that Discord will redirect to after the user authorizes your application.
    |
    */
    'redirect_uri' => env('APP_URL', 'http://localhost:8000') . '/discord/callback',

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | These are the OAuth2 scopes of your Discord application.
    |
    */
    'scopes' => 'identify',

    /*
    |--------------------------------------------------------------------------
    | OAuth2 Prompt - "none" or "consent"
    |--------------------------------------------------------------------------
    |
    | The prompt controls how the authorization flow handles existing authorizations.
    | If a user has previously authorized your application with the requested scopes and prompt is set to consent,
    | it will request them to re-approve their authorization.
    | If set to none, it will skip the authorization screen and redirect them back to your redirect URI without requesting their authorization.
    |
    */

    'prompt' => 'consent',

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    |
    | These are the error messages that will be display to the user if there is an error.
    |
    */
    'error_messages' => [
        'missing_code' => 'The authorization code is missing.',
        'invalid_code' => 'The authorization code is invalid.',
        'authorization_failed' => 'The authorization failed.',
        'user_already_linked' => 'You already have linked a Discord account. Please unlink and try again.',
        'discord_already_linked' => 'This Discord account is already linked to another user. Please unlink and try again.',
        'database_error' => 'There was an error with the database. Please try again later.',
    ],
];
