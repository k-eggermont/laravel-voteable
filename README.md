# Laravel Voteable Trait

## Installation

Use [Composer](https://getcomposer.org/) :

``` bash
$ composer require k-eggermont/laravel-voteable
```

Publish the vendor assets:

```
php artisan vendor:publish --provider="Keggermont\Voteable\VoteableServiceProvider" 
php artisan migrate
```

## Configuration

You can configure the package on /config/laravel-voteable.php

## Usage

### Api

By default, the api is accessible at /api/votes/. You have 3 routes :
* GET /api/votes/{type}/{id} : Get votes list, and overall rating
* POST /api/votes/create/{type}/{id} : Create a new vote (or update existing vote). Data required : "rate" (integer, between 0 up to 5 (/5) )
* DELETE /api/votes/{vote_id} : Delete the vote


### Include trait for your model
``` php
<?php

namespace App;

use Keggermont\Voteable\Traits\Voteable;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    use Voteable;
}
```


### Configure the config/laravel-voteable.php
```
<?php
$allowType = [
"mymodel" => App\MyModel::class
]
```



### Create a vote (5/5) from a model or controller
``` php
$object = MyModel::first();

$vote = $post->createVote([
    'rate' => 5
], Auth::user());

```
 


## License

[MIT](LICENSE)