<?php
use \App\Models\Product;

function requestParamValidation($data){
    $validation = array(
        "status" => true,
        "msg" => array()
    );
    foreach($data AS $param => $value){
        switch($param){
            case 'price':
                if(!is_float($value) || $value <= 0){
                    $validation['status'] = false;
                    $validation['msg'][] = "Please insert a monetary value and/or greater than 0.00";
                }
                break;

            case 'sku':
                if(Product::where('sku', $value)){
                    $validation['status'] = false;
                    $validation['msg'][] = "SKU already exists";
                }
                break;

            case 'name':
                if(Product::where('name', $value)){
                    $validation['status'] = false;
                    $validation['msg'][] = "Name already exists";
                }
                break;
        }
    }
    
    return $validation;
}