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

return;
?>



<md-card class="gdo-downloadtoken">
  <md-card-title>
	<md-card-title-text>
	  <span class="md-headline">
		<div><?= t('card_title_order'); ?></div>
		<div class="gdo-card-subtitle"><?= html($gdo->getTitle()); ?></div>
	  </span>
	</md-card-title-text>
  </md-card-title>
  <gdo-div></gdo-div>
  <md-card-content flex>
	<div><?= t('price'); ?>: <?= GDT_Money::make()->value($gdo->getOrderable()->getOrderPrice())->renderCell(); ?></div>
	<div><?= t('payment'); ?>: <?= html($payment->gdoHumanName()); ?></div>
	<div><?= t('payment_fee'); ?>: <?= html($payment->displayPaymentFee()); ?></div>
	<div><?= t('total'); ?>: <?= $gdo->displayPrice(); ?></div>
	<?= $payment->renderOrderFragment($gdo); ?>
  </md-card-content>
</md-card>
