<?php
namespace GDO\Payment;

use GDO\DB\GDT_Decimal;

/**
 * A money column.
 * @author gizmore
 */
class GDT_Money extends GDT_Decimal
{
	public static $CURR = '€';
	public static $CURRENCY = 'EUR';
	public static $CURR_DIGITS = 2;
	
	public $icon = 'money';

	public $digitsBefore = 13;
	public $digitsAfter = 4;
	
	public function defaultLabel() { return $this->label('price'); }
	
	public function renderCell()
	{
		return self::renderPrice($this->getValue());
	}
	
	public static function renderPrice($price)
	{
		return $price === null ? '---' :
		  sprintf('%s%.0'.self::$CURR_DIGITS.'f', self::$CURR, $price);
	}
	
}
