<?php
namespace GDO\Payment;

use GDO\Template\Response;
use GDO\User\User;

interface Orderable
{
	public function getOrderCancelURL(User $user);
	public function getOrderSuccessURL(User $user);

	public function getOrderTitle(string $iso);
	public function getOrderPrice();

	public function canPayOrderWith(PaymentModule $module);
	
	/**
	 * @return Response
	 */
	public function onOrderPaid();
}
