<?php

namespace Burgers;

//ini_set('display_errors', 1);
//ini_set('error_reporting', E_ALL | E_NOTICE);

require_once "./class/Order.php";

$order = new Order($_REQUEST);
if ($order->getMessageError()) {
    die($order->getMessageError());
}
echo $order->create();
