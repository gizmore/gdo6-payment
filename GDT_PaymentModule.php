<?php
namespace GDO\Payment;

use GDO\DB\GDT_ObjectSelect;
use GDO\Core\Module;

class GDT_PaymentModule extends GDT_ObjectSelect
{
    public function __construct()
    {
        $this->table(Module::table());
    }
	
	public function initChoices()
	{
		return $this->choices ? $this : $this->choices(PaymentModule::allModules());
	}
}
