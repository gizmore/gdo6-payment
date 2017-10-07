<?php
use GDO\Payment\GDO_Order;
use GDO\Payment\Orderable;
use GDO\Payment\PaymentModule;
use GDO\UI\GDT_Bar;
use GDO\User\GDO_User;

$user instanceof GDO_User;
$orderable instanceof Orderable;
$payment instanceof PaymentModule;
$order instanceof GDO_Order;

echo $orderable->renderCard();

echo $order->renderCard();

$bar = GDT_Bar::make();
$bar->addField($payment->makePaymentButton(href($payment->getName(), 'InitPayment')));
echo $bar->render();
