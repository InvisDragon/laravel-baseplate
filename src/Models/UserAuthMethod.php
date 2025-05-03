<?php

namespace InvisibleDragon\LaravelBaseplate\Models;

use Illuminate\Database\Eloquent\Model;

class UserAuthMethod extends Model {

    public $guarded = [];

    public $fillable = [ 'user_id', 'type', 'key_id', 'key', ];

}
