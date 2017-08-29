<?php
namespace GDO\Payment;

use GDO\Template\Response;
use GDO\User\GDO_User;

interface Orderable
{
	public function getOrderCancelURL(GDO_User $user);
	public function getOrderSuccessURL(GDO_User $user);

	public function getOrderTitle(string $iso);
	public function getOrderPrice();

	public function canPayOrderWith(PaymentModule $module);
	
	/**
	 * @return Response
	 */
	public function onOrderPaid();
}
