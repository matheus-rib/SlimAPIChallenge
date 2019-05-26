<?php
namespace src\routes;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\QueryException;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use \App\Models\Order;
use \App\Models\Item;

// List all orders
$app->get('/v1/orders', function(Request $resquest, Response $response){
    // Create a top level instance
    $manager = new Manager();
    $manager->setSerializer(new DataArraySerializer());
    
    $orders = Order::all();
    
    // Pass this object (collection) into a resource, which will also have a "Transformer"
    $resource = new Fractal\Resource\Collection($orders, function($order){
        $items = [];
        foreach ($order->items as $item) {
            $item->product;
            $items[] = [
                'amount' => $item->amount,
                'price_unit' => $item->price_unit,
                'total' => $item->total,
                'product' =>[
                    'id' => $item->product->id,
                    'sku' => $item->product->sku,
                    'name' => $item->product->name
                ]
            ];
        }
        return [
            'id' => (int) $order->id,
            'created_at' => $order->created_at,
            'cancelDate' => $order->cancelDate ?? NULL,
            'status' => $order->status,
            'total' => (float) $order->total,
            'buyer' => [
                'id' => $order->customer->id,
                'name' => $order->customer->name,
                'cpf' => $order->customer->cpf,
                'email' => $order->customer->email
            ],
            'items' => $items
        ];
    });
    
    // Turn that into a structured array to be sent as json
    return $response->withJson($manager->createData($resource)->toArray()['data']);
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
            $order->cancelDate = null;
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
            $order->cancelDate = date('Y-m-d H:i:s');
            $order->update();
            return $response->withJson(["msg" => "Order canceled successfully"]);
        }catch(QueryException $e){
            return $response->withJson(["errors" => $e->getMessage()]);
        }
    }
    return $response->withJson(["errors" => $validation['msg']]);
});