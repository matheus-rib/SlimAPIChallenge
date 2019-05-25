<?php
namespace src\routes;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\QueryException;
use \App\Models\Customer;

// List all Customers
$app->get('/v1/customers', function(Request $resquest, Response $response){
    $product = Customer::all();
    return $response->withJson($product);
});

// Insert a new customer
$app->post('/v1/customers', function(Request $request, Response $response){
    $requestedData = $request->getParsedBody();
    $validation = customerValidation($requestedData);
    if($validation['status']){
        try{
            $customer = new Customer();
            $customer->name = $requestedData['name'];
            $customer->cpf = $requestedData['cpf'];
            $customer->email = $requestedData['email'];
            $customer->save();
            return $response->withJson(["msg" => "Customer registered successfully"]);
        }catch(QueryException $e){
            return $response->withJson(["errors" => $e->getMessage()]);
        }
    }
    return $response->withJson(["errors" => $validation['msg']]);
});