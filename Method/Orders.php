<?php
namespace GDO\Payment\Method;

use GDO\Payment\GDO_Order;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDT_Button;
use GDO\UI\GDT_EditButton;
/**
 * Table of orders for staff.
 * @author gizmore
 * @version 5.0
 */
final class Orders extends MethodQueryTable
{
	public function getPermission() { return 'staff'; }
	public function getQuery()
	{
	    return GDO_Order::table()->select();
	}
	
	public function getHeaders()
	{
	    $gdo = GDO_Order::table();
		return array(
			GDT_EditButton::make(),
			$gdo->gdoColumn('order_id'),
			$gdo->gdoColumn('order_at'),
			$gdo->gdoColumn('order_by'),
			$gdo->gdoColumn('order_title'),
			$gdo->gdoColumn('order_price'),
			$gdo->gdoColumn('order_paid'),
			$gdo->gdoColumn('order_executed'),
			GDT_Button::make('view'),
		);
	}
}
