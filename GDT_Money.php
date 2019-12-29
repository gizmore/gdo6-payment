<?php
namespace GDO\Payment;

use GDO\DB\GDT_Decimal;

class GDT_Money extends GDT_Decimal
{
	public static $CURR = '€';
	public static $CURRENCY = 'EUR';
	
	public $digitsBefore = 13;
	public $digitsAfter = 2;
	
	public function defaultLabel() { return $this->label('price'); }
	
	public function renderCell()
	{
		return self::renderPrice($this->getValue());
	}
	
	public static function renderPrice($price)
	{
		return $price === null ? '---' : sprintf('%s%.02f', self::$CURR, $price);
	}
	
}
