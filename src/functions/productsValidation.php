<?php
use \App\Models\Product;

function productValidation($data){
    // Basic validation return schema
    $validation = array(
        "status" => true,
        "msg" => array()
    );
    
    // Required fields to insert a new product
    $requiredFields = array(
        'price' => 'Price',
        'sku' => 'SKU',
        'name' => 'Name'
    );

    foreach($requiredFields as $key => $value){
        if(!array_key_exists($key, $data)){
            $validation['status'] = false;
            $validation['msg'][] = "Please inform a $value";
        }
    }

    // General Validations
    foreach($data as $param => $value){
        switch($param){
            case 'price':
                if(!is_float($value) || $value <= 0){
                    $validation['status'] = false;
                    $validation['msg'][] = "Please insert a monetary value and/or greater than 0.00";
                }
                break;

            case 'sku':
                if(Product::where('sku', $value)->first()){
                    $validation['status'] = false;
                    $validation['msg'][] = "SKU already exists";
                }
                break;

            case 'name':
                if(Product::where('name', $value)->first()){
                    $validation['status'] = false;
                    $validation['msg'][] = "Name already exists";
                }
                break;
        }
    }

    return $validation;
}