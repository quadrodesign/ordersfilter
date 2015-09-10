<?php
return array(
    'name' => 'Orders Filter',
    'shop_settings' => true,

    'handlers' =>
        array(
            'orders_collection' => 'ordersCollection',
            'backend_orders' => 'backendOrders',
            'backend_order' => 'backendOrder',
        ),
);
