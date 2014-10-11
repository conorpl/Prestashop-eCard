<?php

class eCard extends PaymentModule {

    private $_html = '';

    public function __construct() {

        $this->name = 'ecard';
        $this->tab = 'Payment';
        $this->version = '0.2';

        //PLN Only
        $this->currencies = false;
        //$this->currencies_mode = 'radio';

        parent::__construct();

        $this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('eCard');
        $this->description = $this->l('Accepts payments by eCard');
    }

    public function install() {
        if (!parent::install() OR !Configuration::updateValue('ECARD_MERCHANTID', '2729393') OR !Configuration::updateValue('ECARD_PASS', 'e1c2a3') OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
            return false;

        return true;
    }

    public function uninstall() {
        if (!Configuration::deleteByName('ECARD_MERCHANTID') OR !Configuration::deleteByName('ECARD_PASS') OR !Configuration::deleteByName('ECARD_PENDING') OR !Configuration::deleteByName('ECARD_CLOSED') OR !Configuration::deleteByName('ECARD_DECLINED') OR !Configuration::deleteByName('ECARD_UNDEFINED') OR !parent::uninstall())
            return false;

        return true;
    }

    public function getContent() {
        $this->_html = '<h2>eCard</h2>';

        if (array_key_exists('sumbitEcardConf', $_POST)) {
            if (array_key_exists('ecard_merchantid', $_POST)) {

                $merchantId = pSQL($_POST['ecard_merchantid']);
                Configuration::updateValue('ECARD_MERCHANTID', $merchantId);
            }

            if (array_key_exists('ecard_pass', $_POST)) {

                $pass = pSQL($_POST['ecard_pass']);
                Configuration::updateValue('ECARD_PASS', $pass);
            }

            if (array_key_exists('ecard_order_state_pending', $_POST)) {

                $newOrderState = intval($_POST['ecard_order_state_pending']);
                Configuration::updateValue('ECARD_PENDING', $newOrderState);
            }

            if (array_key_exists('ecard_order_state_closed', $_POST)) {

                $newOrderState = intval($_POST['ecard_order_state_closed']);
                Configuration::updateValue('ECARD_CLOSED', $newOrderState);
            }

            if (array_key_exists('ecard_order_state_declined', $_POST)) {

                $newOrderState = intval($_POST['ecard_order_state_declined']);
                Configuration::updateValue('ECARD_DECLINED', $newOrderState);
            }

            if (array_key_exists('ecard_order_state_declined', $_POST)) {

                $newOrderState = intval($_POST['ecard_order_state_undefined']);
                Configuration::updateValue('ECARD_UNDEFINED', $newOrderState);
            }

            $this->displayConfirmation($this->l('Settings updated'));
        }

        $this->displayFormSetting();

        return $this->_html;
    }

    public function displayFormSetting() {
        include 'ecard_displayFormSetting.php';
    }

    public function hookPayment($params) {
        if (!$this->active)
            return;

        return $this->display(__FILE__, 'ecard.tpl');
    }

    public function validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method = 'Unknown', $message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null) {
        if (!$this->active)
            return;

        parent::validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method, $message, $extra_vars, $currency_special, $dont_touch_amount, $secure_key, $shop);
    }

    public function hookPaymentReturn($params) {
        if (!$this->active)
            return;

        return $this->display(__FILE__, 'confirmation.tpl');
    }

}
