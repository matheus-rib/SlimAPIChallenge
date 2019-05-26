<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model{
    // Table's name of this model
    protected $table = 'order_items';

    // Don't add "created_at" and "updated at"
    public $timestamps = false;

    // Relationship
    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
}