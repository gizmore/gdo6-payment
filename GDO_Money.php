<?php
namespace GDO\Payment;

use GDO\Type\GDO_Decimal;

class GDO_Money extends GDO_Decimal
{
	public static $CURR = '€';
	public static $CURRENCY = 'EUR';
	
	public $digitsBefore = 13;
	public $digitsAfter = 2;
	
	public function defaultLabel() { return $this->label('price'); }
	
	public function renderCell()
	{
		if (null === ($value = $this->getValue()))
		{
			return '---';
		}
		return sprintf('%s%.02f', self::$CURR, $value);
	}
}
