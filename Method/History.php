<?php
namespace GDO\Payment\Method;

use GDO\Payment\Order;
use GDO\Table\MethodQueryList;
use GDO\User\User;
/**
 * Table of orders for staff.
 * @author gizmore
 * @version 5.0
 */
final class History extends MethodQueryList
{
	public function isUserRequired() { return true; }
	
	public function gdoTable() { return Order::table(); }
	
	public function getQuery()
	{
		return Order::table()->select()->where('order_by='.User::current()->getID());
	}
}
