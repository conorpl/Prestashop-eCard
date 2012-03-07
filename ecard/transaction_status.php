<?php

$get = var_export( $_GET, true );
$post = var_export( $_POST, true );
$file = dirname(__FILE__) . '/log.txt';

file_put_contents($file, '======================= ' . date('c') . " =======================\n\n", FILE_APPEND);
file_put_contents($file, 'IP: ' . $_SERVER['REMOTE_ADDR'] . ' Host: ' . $_SERVER['REMOTE_HOST'] . "\n\n", FILE_APPEND);
file_put_contents($file, "GET\n", FILE_APPEND);
file_put_contents($file, $get, FILE_APPEND);
file_put_contents($file, "\n\nPOST\n", FILE_APPEND);
file_put_contents($file, $post, FILE_APPEND);
file_put_contents($file, "\n\n======================= END =======================\n\n", FILE_APPEND);

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/ecard.php');

$id_cart        = (int)$_POST['ORDERNUMBER'];

//var_dump($_POST);
//var_dump($id_cart);

$current_state  = strip_tags($_POST['CURRENTSTATE']);

$order_id       = Order::getOrderByCartId($id_cart); 

//var_dump($order_id);

if ( !$order_id )
    exit('NO ORDER');

$ecard = new eCard();
$order = new Order($order_id);
$total = floatval(Tools::ps_round(floatval($cart->getOrderTotal(true, 3)), 2));

switch ( $current_state ) {
    
    //karty i paypal
    
    case 'payment_approved'  : 
        $transaction_status = Configuration::get('ECARD_CLOSED');
        break;
    case 'payment_deposited' :
        $transaction_status = Configuration::get('ECARD_CLOSED');
        break;
    case 'payment_closed' :
        $transaction_status = Configuration::get('ECARD_CLOSED');
        break;
    case 'payment_declined' :
        $transaction_status = Configuration::get('ECARD_DECLINED');
        break; 
    case 'payment_canceled' :
        $transaction_status = Configuration::get('ECARD_DECLINED');
        break;
    case 'payment_void' :
        $transaction_status = Configuration::get('ECARD_DECLINED');
        break;  
    case 'payment_pending' :
        $transaction_status = Configuration::get('ECARD_PENDING');
        break;  
    
    //przelewy online
    
    case 'transfer_pending' :
        $transaction_status = Configuration::get('ECARD_PENDING');
        break; 
    case 'transfer_accepted' :
        $transaction_status = Configuration::get('ECARD_CLOSED');
        break; 
    case 'transfer_declined' :
        $transaction_status = Configuration::get('ECARD_DECLINED');
        break; 
    case 'transfer_closed' :
        $transaction_status = Configuration::get('ECARD_CLOSED');
        break; 
    case 'transfer_canceled' :
        $transaction_status = Configuration::get('ECARD_DECLINED');
        break;
    
    //domyslne
    default:
        $transaction_status = Configuration::get('ECARD_UNDEFINED');
        break;
}

//$ecard->validateOrder($id_cart, $transaction_status, $total, $ecard->displayName, $post);

$history = new OrderHistory();
$history->id_order = $order_id;
//$history->id_employee = 1;
$history->changeIdOrderState(intval($transaction_status), intval($order_id));
$history->addWithemail(true, array());

$messageText = 'eCard IP: ' . $_SERVER['REMOTE_ADDR'] . "\n";

foreach( $_POST as $key => $value ) 
{
	$messageText .= "'{$key}' => '{$value}',\n";
}

$message = new Message();
$message->id_order = $order_id;
$message->private = true;
$message->message = $messageText;
$message->add();

exit('OK');