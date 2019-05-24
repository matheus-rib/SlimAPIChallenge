<?php
namespace src\routes;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\QueryException;
use \App\Models\Product;

$app->get('/v1/products', function(Request $resquest, Response $response){
    $product = Product::all();
    return $response->withJson($product);
});

$app->post('/v1/products', function(Request $request, Response $response){
    $requestedData = $request->getParsedBody();
    $validation = requestProductParamsValidation($requestedData);
    if($validation['status']){
        try{
            $product = new Product();
            $product->sku = $requestedData['sku'];
            $product->name = $requestedData['name'];
            $product->price = $requestedData['price'];
            $product->save();
            return $response->withJson(["msg" => "Product registered successfully"]);
        }catch(QueryException $e){
            return $response->withJson(["errors" => $e->getMessage()]);
        }
    }
    return $response->withJson(["errors" => $validation['msg']]);
});