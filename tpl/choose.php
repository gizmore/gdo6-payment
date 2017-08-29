<?php
use GDO\Payment\Order;
use GDO\Payment\Orderable;
use GDO\Payment\PaymentModule;
use GDO\Template\GDT_Bar;
use GDO\User\User;

$user instanceof User;
$orderable instanceof Orderable;
$payment instanceof PaymentModule;
$order instanceof Order;

echo $orderable->renderCard();

echo $order->renderCard();

$bar = GDT_Bar::make();
$bar->addField($payment->makePaymentButton(href($payment->getName(), 'InitPayment')));
echo $bar->renderCell();
