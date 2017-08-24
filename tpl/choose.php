<?php
use GDO\Payment\Order;
use GDO\Payment\Orderable;
use GDO\Payment\PaymentModule;
use GDO\Template\GDO_Bar;
use GDO\User\User;

$user instanceof User;
$orderable instanceof Orderable;
$payment instanceof PaymentModule;
$order instanceof Order;

echo $orderable->renderCard();

echo $order->renderCard();

$bar = GDO_Bar::make();
$bar->addField($payment->makePaymentButton(href($payment->getName(), 'InitPayment')));
echo $bar->renderCell();
