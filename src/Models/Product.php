<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    // Table's name of this model
    protected $table = 'products';
    
    // Add "created_at" and "updated at"
    public $timestamps = true;
}