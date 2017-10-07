<?php
namespace GDO\Payment;

use GDO\Core\GDT_Response;
use GDO\User\GDO_User;

interface Orderable
{
	public function getOrderCancelURL(GDO_User $user);
	public function getOrderSuccessURL(GDO_User $user);

	public function getOrderTitle($iso);
	public function getOrderPrice();

	public function canPayOrderWith(PaymentModule $module);
	
	/**
	 * @return GDT_Response
	 */
	public function onOrderPaid();
}
