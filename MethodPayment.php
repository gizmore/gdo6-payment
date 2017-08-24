<?php
namespace GDO\Payment;

use GDO\Core\Method;
use GDO\User\User;
use GDO\Template\Response;

abstract class MethodPayment extends Method
{
	public function isAlwaysTransactional() { return true; }

	/**
	 * @return Order
	 */
	public function getOrderPersisted()
	{
		if ($this->order = User::current()->tempGet('gwf_order'))
		{
			if ($this->order instanceof Order)
			{
				if (!$this->order->isPersisted())
				{
					$this->order->insert();
				}
			}
		}
		return $this->order;
	}
	
	public function renderOrder(Order $order)
	{
		return Response::make($order->renderCard());
	}
	
}
