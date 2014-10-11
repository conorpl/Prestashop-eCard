<?php

class ecardConfirmationModuleFrontController extends ModuleFrontController {

    public $ssl = true;
    public $display_column_left = false;

    public function initContent() {
        parent::initContent();

        if (!Context::getContext()->customer->isLogged()) {
            Tools::redirect('authentication.php?back=order.php');
        }

        $cart = new Cart(intval(Context::getContext()->cookie->id_cart));
        $customer = new Customer(intval($cart->id_customer));

        $id_cart = (int) $cart->id;
        $order_state = Configuration::get('ECARD_PENDING');
        $total = floatval(Tools::ps_round(floatval($cart->getOrderTotal(true, 3)), 2));

        $this->module->validateOrder($id_cart, $order_state, $total, $this->module->displayName, '');

        Tools::redirect('order-confirmation.php?key=' . $customer->secure_key . '&' .
                'id_cart=' . intval($cart->id) . '&' .
                'id_module=' . intval($this->module->id) . '&slowvalidation');
    }

}
