<?php

class ecardRedirectModuleFrontController extends ModuleFrontController {

    public $ssl = true;
    public $display_column_left = false;

    public function initContent() {
        parent::initContent();

        if (!Context::getContext()->customer->isLogged()) {
            Tools::redirect('authentication.php?back=order.php');
        }
        
        $cart = new Cart(intval(Context::getContext()->cookie->id_cart));
        $customer = new Customer(intval($cart->id_customer));

        $orderNumber = intval($cart->id);
//        $orderNumber = rand(10000, 999999999);

        $orderDescription = (int) $orderNumber;

        $hsUrl = Configuration::get('ECARD_SERVERLETHS');
        $merchantId = Configuration::get('ECARD_MERCHANTID');
        $pass = Configuration::get('ECARD_PASS');

        //kwota
        $amount = $cart->getOrderTotal() * 100;
        $currency = 985;

        //waluta
        $cart_currency = Currency::getCurrency($cart->id_currency);

        $numericCurrency = array(
            'PLN' => 985,
            'USD' => 840,
            'GBP' => 826,
            'EUR' => 978
        );

        $currency = $numericCurrency[$cart_currency['iso_code']];

        //adres
        $address = new Address(intval($cart->id_address_invoice));
        $name = $address->firstname;
        $surname = $address->lastname;

        //inne
        $autodeposit = 1;
        $paymenttype = "ALL";

        //linki do powrotu
        $url = Tools::getHttpHost(true, true) . __PS_BASE_URI__;
        $linkfail = $url . 'module/' . $this->module->name . '/fail';
        $linkok = $url . 'module/' . $this->module->name . '/confirmation';

        //hash
        $hash = md5($merchantId .
                $orderNumber .
                $amount .
                $currency .
                $orderDescription .
                $name .
                $surname .
                $autodeposit .
                $paymenttype .
                $linkfail .
                $linkok .
                $pass
        );

        $this->context->smarty->assign(array(
            'merchantId' => $merchantId,
            'orderNumber' => $orderNumber,
            'orderDesc' => $orderDescription,
            'amount' => $amount,
            'currency' => $currency,
            'name' => $name,
            'surname' => $surname,
            'autodeposit' => $autodeposit,
            'paymenttype' => $paymenttype,
            'linkfail' => $linkfail,
            'linkok' => $linkok,
            'hash' => $hash
        ));

        $this->setTemplate('redirect.tpl');
    }

}
