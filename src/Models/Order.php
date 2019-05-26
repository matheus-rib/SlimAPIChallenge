<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model{
    // Table's name of this model
    protected $table = 'orders';
    
    // Add "created_at" and "updated at"
    public $timestamps = true;

    // Relationship
    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
    
    public function Items(){
        return $this->hasMany('App\Models\Item');
    }
}