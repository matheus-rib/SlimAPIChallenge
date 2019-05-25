<?php
use \App\Models\Customer;

// CPF Validation found in internet
function CPFValidation($cpf){
    if(empty($cpf)) {
        return false;
    }
    $invalidCombinations = array(
        '00000000000',
        '11111111111',
        '22222222222',
        '33333333333',
        '44444444444',
        '55555555555',
        '66666666666',
        '77777777777',
        '88888888888',
        '99999999999'
    );
    $cpf = preg_replace("/[^0-9]/", "", $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    if(strlen($cpf) != 11){
        return false;
    }elseif(in_array($cpf, $invalidCombinations)){
        return false;
    }else{   
        for ($t = 9; $t < 11; $t++) {
            for($d = 0, $c = 0; $c < $t; $c++){
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if($cpf{$c} != $d){
                return false;
            }
        }
        return true;
    }
}

function customerValidation($data){
    // Basic validation return schema
    $validation = array(
        "status" => true,
        "msg" => array()
    );

    // Required fields to insert a new customer
    $requiredFields = array(
        'cpf' => 'CPF',
        'name' => 'Name',
        'email' => 'Email'
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
            case 'cpf':
                if(!CPFValidation($value)){
                    $validation['status'] = false;
                    $validation['msg'][] = "Please insert a valid CPF";
                }

                if(Customer::where('cpf', $value)->first()){
                    $validation['status'] = false;
                    $validation['msg'][] = "CPF already exists";
                }
                break;

            case 'name':
                if(Customer::where('name', $value)->first()){
                    $validation['status'] = false;
                    $validation['msg'][] = "Name already exists";
                }
                break;

            case 'email':
                if(Customer::where('email', $value)->first()){
                    $validation['status'] = false;
                    $validation['msg'][] = "Email already exists";
                }

                if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $validation['status'] = false;
                    $validation['msg'][] = "Please insert a valid email";
                }
                break;
        }
    }
    
    return $validation;
}