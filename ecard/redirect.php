<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/ecard.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');

$ecard      = new eCard();
$cart       = new Cart(intval($cookie->id_cart));
$customer   = new Customer(intval($cart->id_customer));

//nr zamowienia

$orderNumber = intval($cart->id);
//$orderNumber = rand(10000, 999999999);

//opis

$orderDescription = (int)$orderNumber;

$hsUrl      = Configuration::get('ECARD_SERVERLETHS');
$merchantId = Configuration::get('ECARD_MERCHANTID');
$pass       = Configuration::get('ECARD_PASS');

//kwota

$amount     = $cart->getOrderTotal() * 100;
$currency   = 985;

//waluta

$cart_currency = Currency::getCurrency( $cart->id_currency );

$numericCurrency = array(
        'PLN' => 985,
        'USD' => 840,
        'GBP' => 826,
        'EUR' => 978
    );

$currency = $numericCurrency[ $cart_currency['iso_code'] ];

//adres

$address    = new Address(intval($cart->id_address_invoice));
$name       = $address->firstname;
$surname    = $address->lastname;

//inne

$autodeposit = 1;
$paymenttype = "ALL";

//linki do powrotu
$url         = Tools::getHttpHost(true, true). __PS_BASE_URI__; 

//$linkfail = $url;
//$linkok   = $url;


$linkfail    = $url . 'index.php';
$linkok      = $url . 'modules/' . $ecard->name . '/confirmation.php';

//hash

$hash = md5($merchantId         . 
            $orderNumber        .
            $amount             .
            $currency           .
            $orderDescription   .
            $name               .
            $surname            .
            $autodeposit        .
            $paymenttype        .
            $linkfail           .
            $linkok             .
            $pass
        );

/*
$hsarray = array(
        'orderNumber'       => $cart->id,
        'orderDescription'  => '',
        'amount'            => $amount,
        'merchantId'        => $merchantId,
        'password'          => $pass
    );

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $hsUrl);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $hsarray);
$result=curl_exec ($curl);

//if ( !$result ) exit('Unable to get hash');

//exit( 'Result: "' . $result . '"');
var_dump($result); var_dump($hsarray); exit();
*/

global $smarty;

$smarty->assign(array(
	'merchantId'    => $merchantId,
	'orderNumber'   => $orderNumber,
	'orderDesc'     => $orderDescription,
	'amount'        => $amount,
	'currency'      => $currency,
    'name'          => $name,
    'surname'       => $surname,
    'autodeposit'   => $autodeposit,
    'paymenttype'   => $paymenttype,
    'linkfail'      => $linkfail,
    'linkok'        => $linkok,
    'hash'          => $hash
));

if (is_file(_PS_THEME_DIR_.'modules/ecard/redirect.tpl'))
	$smarty->display(_PS_THEME_DIR_.'modules/'.$ecard->name.'/redirect.tpl');
else
	$smarty->display(_PS_MODULE_DIR_.$ecard->name.'/redirect.tpl');




include_once(dirname(__FILE__).'/../../footer.php');
