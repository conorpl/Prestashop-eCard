<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/ecard.php');


if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');

$ecard      = new eCard();
$cart       = new Cart(intval($cookie->id_cart));
$customer   = new Customer(intval($cart->id_customer));

$id_cart    = (int)$cart->id;
$order_state= Configuration::get('ECARD_PENDING');
$total      = floatval(Tools::ps_round(floatval($cart->getOrderTotal(true, 3)), 2));

$ecard->validateOrder($id_cart, $order_state, $total, $ecard->displayName, '');

Tools::redirect('order-confirmation.php?key=' . $customer->secure_key . '&'.
               'id_cart=' . intval($cart->id) . '&' .
               'id_module=' . intval($ecard->id) . '&slowvalidation');