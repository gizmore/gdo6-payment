<?php
namespace GDO\Payment\Method;

use GDO\Core\Method;
use GDO\User\GDO_User;
use GDO\Payment\GDO_Order;
use GDO\DB\GDT_Object;

final class ViewOrder extends Method
{
	public function gdoParameters()
	{
		return array(
			GDT_Object::make('id')->table(GDO_Order::table())->notNull(),
		);
	}
	
	/**
	 * @return GDO_Order
	 */
	public function getOrder()
	{
		return $this->gdoParameterValue('id');
	}
	
	public function hasPermission(GDO_User $user)
	{
		return $this->getOrder()->getCreator() === $user;
	}
	
	public function execute()
	{
		$tVars = array(
			'order' => $this->getOrder(),
		);
		return $this->templatePHP('view_order.php', $tVars);
	}

	
}
