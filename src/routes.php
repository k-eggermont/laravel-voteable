<?php

// Route list

Route::get(config('laravel-voteable.route_api').'/{type}/{id}', 'Keggermont\Voteable\Http\VoteableController@get')->name("api.voteable.get")->where("type", "[a-zA-Z]+")->where("id","[0-9]+")->middleware(config('laravel-voteable.api_middleware_get'));
Route::post(config('laravel-voteable.route_api').'/create/{type}/{id}', 'Keggermont\Voteable\Http\VoteableController@create')->name("api.voteable.create")->where("type", "[a-zA-Z]+")->where("id","[0-9]+")->middleware(config('laravel-voteable.api_middleware_create'));
Route::delete(config('laravel-voteable.route_api').'/{id}', 'Keggermont\Voteable\Http\VoteableController@delete')->name("api.voteable.delete")->where("id","[0-9]+")->middleware(config('laravel-voteable.api_middleware_delete'));
