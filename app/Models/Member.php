<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Member extends Model
{
    protected $collection = 'member';

    protected $guarded = [];
}
