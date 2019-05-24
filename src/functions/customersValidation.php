<?php
use \App\Models\Customer;

function validaCpf(){

}

function requestCustomersParamsValidation($data){
    $validation = array(
        "status" => true,
        "msg" => array()
    );
    /*foreach($data AS $param => $value){
        switch($param){
            case 'cpf':
                if(!is_float($value) || $value <= 0){
                    $validation['status'] = false;
                    $validation['msg'][] = "Please insert a monetary value and/or greater than 0.00";
                }
                break;

            case 'name':
                if(Customer::where('name', $value)){
                    $validation['status'] = false;
                    $validation['msg'][] = "Name already exists";
                }
                break;

            case 'email':
                if(Customer::where('email', $value)){
                    $validation['status'] = false;
                    $validation['msg'][] = "Email already exists";
                }
                break;
        }
    }*/
    
    return $validation;
}