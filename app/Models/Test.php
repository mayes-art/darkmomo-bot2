<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Test extends Model
{
    protected $collection = 'test';

    protected $guarded = [];
}
