<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model{
    // Table's name of this model
    protected $table = 'customers';

    // Add "created_at" and "updated at"
    public $timestamps = true;
}