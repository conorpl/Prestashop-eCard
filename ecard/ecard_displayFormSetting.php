<?php

global $cookie;
        
$conf = Configuration::getMultiple(
        array('ECARD_MERCHANTID', 'ECARD_PASS', 'ECARD_PENDING', 'ECARD_CLOSED', 'ECARD_DECLINED', 'ECARD_UNDEFINED')
        );

$allowEmployeeFormLang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

if ($allowEmployeeFormLang && !$cookie->employee_form_lang)
    $cookie->employee_form_lang = intval(Configuration::get('PS_LANG_DEFAULT'));
$useLangFromCookie = false;
$languages = Language::getLanguages();

if ($allowEmployeeFormLang)
    foreach ($languages AS $lang)
        if ($cookie->employee_form_lang == $lang['id_lang'])
            $useLangFromCookie = true;
if (!$useLangFromCookie)
    $language = intval(Configuration::get('PS_LANG_DEFAULT'));
else
    $language = intval($cookie->employee_form_lang);

$orderStates    = OrderState::getOrderStates(intval($language));

$merchantId         = array_key_exists('ECARD_MERCHANTID', $conf) ? $conf['ECARD_MERCHANTID'] : '';
$pass               = array_key_exists('ECARD_PASS', $conf) ? $conf['ECARD_PASS'] : '';
$newOrderState      = array_key_exists('ECARD_PENDING', $conf) ? $conf['ECARD_PENDING'] : '';
$status_closed      = array_key_exists('ECARD_CLOSED', $conf) ? $conf['ECARD_CLOSED'] : '';
$status_declined    = array_key_exists('ECARD_DECLINED', $conf) ? $conf['ECARD_DECLINED'] : '';
$status_undefined   = array_key_exists('ECARD_UNDEFINED', $conf) ? $conf['ECARD_UNDEFINED'] : '';


//$serverletHs    = array_key_exists('ECARD_SERVERLETHS', $conf) ? $conf['ECARD_SERVERLETHS'] : '';
//$serverletPs    = array_key_exists('ECARD_SERVERLETPS', $conf) ? $conf['ECARD_SERVERLETPS'] : '';

$this->_html .= '<form action="' . $_SERVER['REQUEST_URI'] . '" method="POST" style="clear: both">
    <p>
    <fieldset>
        <legend> <img src="' . _MODULE_DIR_ . $this->name . '/logo.gif" />' . $this->l('Settings') . '</legend>
        <label> ' . $this->l('Merchant ID') . '</label>
        <div class="margin-form">
            <input type="text" size="33" name="ecard_merchantid" value="'.htmlentities($merchantId, ENT_COMPAT, 'UTF-8').'" />
        </div>
        <label> ' . $this->l('Password') . '</label>
        <div class="margin-form">
            <input type="password" size="33" name="ecard_pass" value="'.htmlentities($pass, ENT_COMPAT, 'UTF-8').'" />
        </div>
    </fieldset>
    </p>
    <p>
    <fieldset>
        <legend> <img src="' . _MODULE_DIR_ . $this->name . '/logo.gif" />' . $this->l('Statuses') . '</legend>
        <label> ' . $this->l('New order') . '</label>
        <div class="margin-form">
            <select name="ecard_order_state_pending">';

//default option in case of sitation when new order state is not set
$this->_html .= '<option value="0">&nbsp;</option>';

foreach( $orderStates as $orderState )
{
    $this->_html .= '<option value="' . $orderState['id_order_state'] . '" ' . 
            ( $orderState['id_order_state'] ==  $newOrderState ? ' selected="selected"' : '') . 
            '>' . $orderState['name'] . 
            '</option>';
}

$this->_html .= '</select>
                 </div>
                 <label>' . $this->l('Successed') . '</label>
                 <div class="margin-form">
                 <select name="ecard_order_state_closed">
                    <option value="0">&nbsp;</option>';

foreach( $orderStates as $orderState )
{
    $this->_html .= '<option value="' . $orderState['id_order_state'] . '" ' . 
            ( $orderState['id_order_state'] ==  $status_closed ? ' selected="selected"' : '') . 
            '>' . $orderState['name'] . 
            '</option>';
}

$this->_html .= '</select>
                 </div>
                 <label>' . $this->l('Declined/canceled') . '</label>
                 <div class="margin-form">
                 <select name="ecard_order_state_declined"><option value="0">&nbsp;</option>';

foreach( $orderStates as $orderState )
{
    $this->_html .= '<option value="' . $orderState['id_order_state'] . '" ' . 
            ( $orderState['id_order_state'] ==  $status_declined ? ' selected="selected"' : '') . 
            '>' . $orderState['name'] . 
            '</option>';
}

$this->_html .= '</select>
                 </div>
                 <label>' . $this->l('Unknown') . '</label>
                 <div class="margin-form">
                 <select name="ecard_order_state_undefined">
                    <option value="0">&nbsp;</option>';

foreach( $orderStates as $orderState )
{
    $this->_html .= '<option value="' . $orderState['id_order_state'] . '" ' . 
            ( $orderState['id_order_state'] ==  $status_undefined ? ' selected="selected"' : '') . 
            '>' . $orderState['name'] . 
            '</option>';
}

$this->_html .= '</select></div><center><input type="submit" name="sumbitEcardConf" value="'.$this->l('Update settings').'" class="button" /></center>
    </fieldset></p></form>';