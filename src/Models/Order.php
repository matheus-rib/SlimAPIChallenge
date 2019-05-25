<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model{
    // Table's name of this model
    protected $table = 'orders';
    
    // Add "created_at" and "updated at"
    public $timestamps = true;
}