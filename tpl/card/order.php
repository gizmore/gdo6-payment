<?php
use GDO\Payment\GDO_Money;
use GDO\Payment\Order;

$gdo instanceof Order;
$payment = $gdo->getPaymentModule();
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
    <div><?= t('price'); ?>: <?= GDO_Money::make()->value($gdo->getOrderable()->getOrderPrice())->renderCell(); ?></div>
    <div><?= t('payment'); ?>: <?= html($payment->gdoHumanName()); ?></div>
    <div><?= t('payment_fee'); ?>: <?= html($payment->displayPaymentFee()); ?></div>
    <div><?= t('total'); ?>: <?= $gdo->displayPrice(); ?></div>
    <?= $payment->renderOrderFragment($gdo); ?>
  </md-card-content>
</md-card>
