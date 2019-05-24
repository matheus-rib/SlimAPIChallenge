<?php
use \App\Models\Product;

function requestProductParamsValidation($data){
    $validation = array(
        "status" => true,
        "msg" => array()
    );

    if(!array_key_exists('price', $data)){
        $validation['status'] = false;
        $validation['msg'][] = "Please inform a Price";
    }

    if(!array_key_exists('sku', $data)){
        $validation['status'] = false;
        $validation['msg'][] = "Please inform a SKU";
    }

    if(!array_key_exists('name', $data)){
        $validation['status'] = false;
        $validation['msg'][] = "Please inform a Name";
    }
    
    
    foreach($data AS $param => $value){
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