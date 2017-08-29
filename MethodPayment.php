<?php
namespace GDO\Payment;

use GDO\Core\Method;
use GDO\User\GDO_User;
use GDO\Template\Response;

abstract class MethodPayment extends Method
{
	public function isAlwaysTransactional() { return true; }

	/**
	 * @return GDO_Order
	 */
	public function getOrderPersisted()
	{
		if ($this->order = GDO_User::current()->tempGet('gdo_order'))
		{
		    if ($this->order instanceof GDO_Order)
			{
				if (!$this->order->isPersisted())
				{
					$this->order->insert();
				}
			}
		}
		return $this->order;
	}
	
	public function renderOrder(GDO_Order $order)
	{
		return $order->renderCard();
	}
	
}
