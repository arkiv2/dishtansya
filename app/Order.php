<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Order extends Pivot
{
    protected $table = 'orders';
}
