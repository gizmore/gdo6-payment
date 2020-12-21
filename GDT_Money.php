<?php
namespace GDO\Payment;

use GDO\DB\GDT_Decimal;

class GDT_Money extends GDT_Decimal
{
	public static $CURR = '€';
	public static $CURRENCY = 'EUR';
	
	public $icon = 'money';

	public $digitsBefore = 13;
	public $digitsAfter = 4;
	
	public function defaultLabel() { return $this->label('price'); }
	
	public function __construct()
	{
	    $this->initial('0.0');
	}
	
	public function renderCell()
	{
		return self::renderPrice($this->getValue());
	}
	
	public static function renderPrice($price)
	{
		return $price === null ? '---' : sprintf('%s%.02f', self::$CURR, $price);
	}
	
}
