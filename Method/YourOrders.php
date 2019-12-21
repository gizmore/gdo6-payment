<?php
namespace GDO\Payment\Method;

use GDO\Payment\GDO_Order;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDT_Button;
use GDO\User\GDO_User;

final class YourOrders extends MethodQueryTable
{
	public function isUserRequired() { return true; }
	
	public function getQuery()
	{
		return GDO_Order::table()->select()->where('order_by='.GDO_User::current()->getID());
	}
	
	public function getHeaders()
	{
		$gdo = GDO_Order::table();
		return array(
// 			GDT_EditButton::make(),
			$gdo->gdoColumn('order_id'),
			$gdo->gdoColumn('order_at'),
			$gdo->gdoColumn('order_title'),
			$gdo->gdoColumn('order_price'),
			$gdo->gdoColumn('order_paid'),
			$gdo->gdoColumn('order_executed'),
			GDT_Button::make('view'),
		);
	}
	
}