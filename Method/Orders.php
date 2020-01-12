<?php
namespace GDO\Payment\Method;

use GDO\Payment\GDO_Order;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDT_EditButton;
use GDO\Address\GDO_Address;

/**
 * Table of orders for staff.
 * 
 * @author gizmore
 * @version 6.10
 * @since 5.0
 */
final class Orders extends MethodQueryTable
{
	public function getPermission() { return 'staff'; }
	public function getQuery()
	{
		return GDO_Order::table()->select()->joinObject('order_address', 'LEFT JOIN');
	}
	
	public function getHeaders()
	{
		$gdo = GDO_Order::table();
		$add = GDO_Address::table();
		return array(
			GDT_EditButton::make(),
			$gdo->gdoColumn('order_id'),
			$gdo->gdoColumn('order_num'),
			$add->gdoColumn('address_vat'),
			$add->gdoColumn('address_company'),
			$add->gdoColumn('address_name'),
			$gdo->gdoColumn('order_by'),
			$gdo->gdoColumn('order_at'),
			$gdo->gdoColumn('order_title'),
			$gdo->gdoColumn('order_price'),
			$gdo->gdoColumn('order_paid'),
			$gdo->gdoColumn('order_executed'),
		);
	}
}
