<?php
use GDO\Payment\GDT_Money;
use GDO\Payment\GDO_Order;
use GDO\UI\GDT_Card;
use GDO\DB\GDT_String;
use GDO\Payment\Module_Payment;
/**
 * @var $gdo GDO_Order
 */
$gdo instanceof GDO_Order;
$payment = $gdo->getPaymentModule();
$o = $gdo->getOrderable();

$card = GDT_Card::make();
$card->title(t('card_title_order'));
$card->subtitle($gdo->getTitle());

$card->addFields(array(
	GDT_Money::make('price')->value($o->getOrderPrice()),
	GDT_String::make('payment')->val($payment->displayPaymentMethodName()),
	GDT_String::make('payment_fee')->val($payment->displayPaymentFee()),
));

if (!$gdo->getTax())
{
	$card->addFields(array(
		GDT_Money::make('total_tax')->label('pdforder_sum_tax', ['0'])->value($gdo->getPriceMWST()),
		GDT_Money::make('total_brutto')->label('pdforder_sum_brutto')->value($gdo->getPrice()),
	));
}
else
{
	$tax = Module_Payment::instance()->cfgTax();
	$card->addFields(array(
		GDT_Money::make('total_netto')->label('pdforder_sum_netto')->value($gdo->getPriceNetto()),
		GDT_Money::make('total_tax')->label('pdforder_sum_tax', [$tax])->value($gdo->getPriceMWST()),
		GDT_Money::make('total_brutto')->label('pdforder_sum_brutto')->value($gdo->getPriceBrutto()),
	));
}

unset($_REQUEST['payment']);

echo $gdo->getOrderable()->renderOrderCard();

echo $card->render();

echo $payment->renderOrderFragment($gdo);
