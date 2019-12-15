<?php
namespace GDO\Payment;

use GDO\Core\Method;
use GDO\User\GDO_Session;

abstract class MethodPayment extends Method
{
	public function isAlwaysTransactional() { return true; }
	
	/**
	 * @return GDO_Order
	 */
	public function getOrder()
	{
		return GDO_Session::get('gdo_order');
	}

	/**
	 * @return GDO_Order
	 */
	public function getOrderPersisted()
	{
		if ($this->order = $this->getOrder())
		{
			if ($this->order instanceof GDO_Order)
			{
				if (!$this->order->isPersisted())
				{
					$this->order->insert();
					GDO_Session::set('gdo_order', $this->order);
				}
			}
		}
		return $this->order;
	}
	
	public function renderOrder(GDO_Order $order)
	{
		return $order->responseCard();
	}
	
}
