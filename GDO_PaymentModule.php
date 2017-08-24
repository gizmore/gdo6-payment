<?php
namespace GDO\Payment;

use GDO\DB\GDO_ObjectSelect;
use GDO\Core\Module;

class GDO_PaymentModule extends GDO_ObjectSelect
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
