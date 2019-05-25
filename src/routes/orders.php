<?php
namespace src\routes;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\QueryException;
use \App\Models\Order;
use \App\Models\Item;

// List all orders
$app->get('/v1/orders', function(Request $resquest, Response $response){
    $order = Order::all();
    return $response->withJson($order);
});

// Insert a new order
$app->post('/v1/orders', function(Request $request, Response $response){
    $requestedData = $request->getParsedBody();
    $validation = orderValidation($requestedData);
    if($validation['status']){
        $customerId = $requestedData['buyer']['id'];
        try{
            $order = new Order;
            $order->status = $requestedData['status'];
            $order->total = $requestedData['total'];
            $order->customer_id = $customerId;
            $order->save();
    
            // Insert order_items for the Order
            foreach($requestedData["items"] as $requestItem){
                $item = new Item;
        
                $item->amount = $requestItem['amount'];
                $item->price_unit = $requestItem['price_unit'];
                $item->total = $requestItem['total'];
                $item->product_id = $requestItem['product']['id'];
                $item->order_id = $order->id;
                $item->save();                
            }
            return $response->withJson(["msg" => "Order registered successfully"]);
        }catch(QueryException $e){
            return $response->withJson(["errors" => $e->getMessage()]);
        }
    }
    return $response->withJson(["errors" => $validation['msg']]);
});

// Cancel an order
$app->put('/v1/orders/{id}', function(Request $request, Response $response){
    $requestedData = $request->getParsedBody();
    $validation = orderCancelValidation($requestedData);
    if($validation['status']){
        $orderId = $requestedData['order_id'];
        $orderStatus = $requestedData['status'];
        try{
            $order = Order::find($orderId);
            $order->status = $orderStatus;
            $order->update();
            return $response->withJson(["msg" => "Order canceled successfully"]);
        }catch(QueryException $e){
            return $response->withJson(["errors" => $e->getMessage()]);
        }
    }
    return $response->withJson(["errors" => $validation['msg']]);
});