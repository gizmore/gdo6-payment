<?php
namespace GDO\Payment;

use GDO\Core\GDO_Module;
use GDO\UI\GDT_Bar;

final class Module_Payment extends GDO_Module
{
	public $module_priority = 15;
	public function href_administrate_module() { return href('Payment', 'Orders'); }
	public function getClasses() { return ['GDO\Payment\GDO_Order']; }
	public function onLoadLanguage() { $this->loadLanguage('lang/payment'); }
	
	public function hookRightBar(GDT_Bar $navbar)
	{
		$this->templatePHP('right_sidebar.php', ['bar' => $navbar]);
	}
	
}
