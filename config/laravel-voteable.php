<?php
//declare(strict_types=1);

return [

    // Model class
    'model' => \Keggermont\Voteable\Models\Vote::class,

    // Name of the table
    'table_name' => 'keg_votes',

    // Enable the API
    'enable_api' => true,

    // Route API
    'route_api' => '/api/votes',

    // For API => type for create a vote
    'allowType' => [
        "user" => \App\User::class,
        // "comment" => \Keggermont\Commentable\Models\Comment::class,   // If you using the keggermont/laravel-commentable, you can enable the voteable here
    ],

    // You can add middlewares for API routes (example : auth:api
    'api_middleware_get' => [],
    'api_middleware_create' => [],
    'api_middleware_delete' => [],

];