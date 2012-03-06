<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/ecard.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');

$ecard      = new eCard();
$cart       = new Cart(intval($cookie->id_cart));

