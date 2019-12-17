<?php
use GDO\Payment\GDT_Money;
use GDO\Payment\GDO_Order;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_Label;
use GDO\DB\GDT_String;
use GDO\UI\GDT_Paragraph;

$gdo instanceof GDO_Order;
$payment = $gdo->getPaymentModule();

$card = GDT_Card::make();
$card->title(t('card_title_order'));
$card->subtitle($gdo->getTitle());

$card->addFields(array(
	GDT_Money::make('price')->value($gdo->getOrderable()->getOrderPrice()),
	GDT_String::make('payment')->val($payment->gdoHumanName()),
	GDT_String::make('payment_fee')->val($payment->displayPaymentFee()),
	GDT_Money::make('total')->value($gdo->getPrice()),
));

echo $gdo->getOrderable()->renderOrderCard();

echo $card->render();

echo $payment->renderOrderFragment($gdo);

return;
?>
