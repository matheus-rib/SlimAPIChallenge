<?php
use \App\Models\Product;
use \App\Models\Customer;
use \App\Models\Order;

// Check if the product exists
function productOrderValidation($productToValidate, $itemPosition){
    $productPrice = 0;
    // Basic validation return schema
    $validationResult = array(
        "status" => true,
        "productPrice" => $productPrice,
        "msg" => array()
    );

    // Required informed fields to find a product
    $requiredFields = array(
        "id" => "ID",
        "sku" => "SKU",
        "title" => "Title"
    );

    foreach($requiredFields as $key => $value){
        if(!array_key_exists($key, $productToValidate)){
            $validationResult['status'] = false;
            $validationResult['msg'][] = "Please inform a $value in item $itemPosition";
        }
    }

    // General Validations
    if($validationResult['status']){
        $productId = $productToValidate['id'];
        $productName = $productToValidate['title'];
        $productSku = $productToValidate['sku'];

        $product= Product::where([
            ['id', '=', $productId],
            ['name', '=', $productName],
            ['sku', '=', $productSku]
        ])->first();

        if(!$product){
            $validationResult['status'] = false;
            $validationResult['msg'][] = "Product not found in item $itemPosition, please check it's informations";
        }else{
            // Return product's price for a pricing validation
            $validationResult['productPrice'] = $product->price;
        }
    }
    return $validationResult;
}

// Check if the buyer exists
function orderBuyerValidation($buyer){
    // Basic validation return schema
    $validationResult = array(
        'status' => true,
        'msg' => array()
    );

    if(!is_array($buyer)){
        $validationResult['status'] = false;
        $validationResult['msg'][] = "Invalid data type for Buyer";
        return $validationResult;
    }

    // Required informed fields to find the customer
    $requiredFields = array(
        'id' => 'ID',
        'name' => 'Name',
        'cpf' => 'CPF',
        'email' => 'Email'
    );

    foreach($requiredFields as $key => $value){
        if(!array_key_exists($key, $buyer)){
            $validationResult['status'] = false;
            $validationResult['msg'][] = "Please inform a $value";
        }
    }

    // General Validations
    if($validationResult['status']){
        $customerId = $buyer['id'];
        $customerName = $buyer['name'];
        $customerCpf = $buyer['cpf'];
        $customerEmail = $buyer['email'];

        $customer = Customer::where([
            ['id', '=', $customerId],
            ['name', '=', $customerName],
            ['cpf', '=', $customerCpf],
            ['email', '=', $customerEmail]
        ])->first();

        if(!$customer){
            $validationResult['status'] = false;
            $validationResult['msg'][] = "Customer not found, please check it's informations";
        }
    }

    return $validationResult;
}

// Order's items validations
function orderItemValidation($items){
    // Basic validation return schema
    $validationResult = array(
        'status' => true,
        'totalPriceItem' => 0,
        'msg' => array()
    );

    if(!is_array($items)){
        $validationResult['status'] = false;
        $validationResult['msg'][] = "Invalid data type for Items";
        return $validationResult;
    }

    // Start loop to validate each item
    foreach($items as $itemIndex => $value){
        // Item Number used for messages
        $itemNumber = $itemIndex + 1;

        // Intialization
        $itemProduct["status"] = false;

        // Required fields to insert a order item
        $requiredFields = array(
            "amount" => "Amount",
            "price_unit" => "Price unit",
            "total" => "Total",
        );

        foreach($requiredFields as $key => $fieldValue){
            if(!array_key_exists($key, $value)){
                $validationResult['status'] = false;
                $validationResult['msg'][] = "Please inform a $fieldValue in item $itemNumber";
            }
        }

        // General number validations
        if(!is_int($value['amount']) || $value['amount'] < 1){
            $validationResult['status'] = false;
            $validationResult['msg'][] = "Please inform a number greater than 0";

        }

        if(!is_float($value['price_unit']) || $value['price_unit'] <= 0){
            $validationResult['status'] = false;
            $validationResult['msg'][] = "Please insert a price unit monetary value and/or greater than 0.00";

        }

        if(!is_float($value['total']) || $value['total'] <= 0){
            $validationResult['status'] = false;
            $validationResult['msg'][] = "Please insert a total monetary value and/or greater than 0.00";

        }

        if($validationResult['status']){
            // Product validation
            if(!$value['product']){
                $validationResult['status'] = false;
                $validationResult['msg'][] = "Please inform a Product in item $itemNumber";
            }elseif(!is_array($value['product'])){
                $validationResult['status'] = false;
                $validationResult['msg'][] = "Invalid data type for Product";
            }else{
                $itemProduct = productOrderValidation($value['product'], $itemNumber);
            }

            // Informed values validation to avoid incongruities
            if($validationResult['status'] && $itemProduct['status']){
                $itemTotal = $value['amount'] * $value['price_unit'];
                $productPrice = $itemProduct['productPrice'];
                if($value['price_unit'] != $productPrice){
                    $validationResult['status'] = false;
                    $validationResult['msg'][] = "Invalid price unit for product in item $itemNumber";
                }

                if($itemTotal != $value['total']){
                    $validationResult['status'] = false;
                    $validationResult['msg'][] = "Invalid total price for product in item $itemNumber";
                }else{
                    $validationResult['totalPriceItem'] += $itemTotal;
                }
            }elseif($validationResult['status'] && !$itemProduct['status']){
                $validationResult['status'] = false;
                $validationResult['msg'][] = $itemProduct['msg'];
            }
        }
    }
    return $validationResult;
}

// General validation to insert a new order
function orderValidation($data){
    // Basic validation return schema with grouped messages
    $validation = array(
        "status" => true,
        "msg" => array(
            "order" => array(),
            "buyer" => array(),
            "items" => array()
        )
    );
    
    // Required fields to insert a new order
    $requiredFields = array(
        'status' => 'Status',
        'total' => 'Total',
        'buyer' => 'Buyer',
        'items' => 'Items'
    );

    foreach($requiredFields as $key => $value){
        if(!array_key_exists($key, $data)){
            $validation['status'] = false;
            $validation['msg']["order"][] = "Please inform a $value";
        }
    }

    if($validation['status']){
        // Check if the customer exists
        $buyerValidation = orderBuyerValidation($data['buyer']);
        if(!$buyerValidation['status']){
            $validation['status'] = false;
            $validation['msg']['buyer'] = $buyerValidation['msg'];
        }

        if(is_float($data['total']) && $data['total'] > 0){
            // Items and Products Validations
            $itemValidation = orderItemValidation($data['items']);
            if(!$itemValidation['status']){
                $validation['status'] = false;
                $validation['msg']['items'] = $itemValidation['msg'];
            }

            if($data['total'] != $itemValidation['totalPriceItem']){
                $validation['msg']["order"][] = "Invalid total price in Order";
            }
            
        }else{
            $validation['status'] = false;
            $validation['msg']["order"][] = "Please insert a total monetary value and/or greater than 0.00";
        }
    }
   
    if($data['status'] != "CONCLUDED"){
        $validation['status'] = false;
        $validation['msg']["order"][] = "Please inform a CONCLUDED Order";
    }

    return $validation;
}

function orderCancelValidation($orderToCancel){
    // Basic validation return schema
    $validation = array(
        "status" => true,
        "msg" => array()
    );
    
    if(!is_array($orderToCancel)){
        $validation['status'] = false;
        $validation['msg'][] = "Invalid data type to cancel order";
        return $validation;
    }

    // Required fields to cancel an order
    $requiredFields = array(
        'order_id' => 'Order ID',
        'status' => 'Status',
    );

    foreach($requiredFields as $key => $value){
        if(!array_key_exists($key, $orderToCancel)){
            $validation['status'] = false;
            $validation['msg'][] = "Please inform a $value";
        }
    }

    // General Validations
    if($validation['status']){
        $orderId = $orderToCancel['order_id'];
        $orderStatus = $orderToCancel['status'];

        if(is_int($orderId)){
            $order = Order::find($orderId);

            if(!$order){
                $validation['status'] = false;
                $validation['msg'][] = "Order not found, please check it's informations";
            }else{
                if($order->status == "CANCELED"){
                    $validation['status'] = false;
                    $validation['msg'][] = "Order is already canceled";
                }
            }
        }else{
            $validation['status'] = false;
            $validation['msg'][] = "Please inform a number greater than 0";
        }

        if($orderStatus != "CANCELED"){
            $validation['status'] = false;
            $validation['msg'][] = "Invalid information for Status";
        }
    }

    return $validation;
}