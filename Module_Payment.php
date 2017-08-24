<?php
namespace GDO\Payment;

use GDO\Core\Module;

include 'PaymentModule.php';
final class Module_Payment extends Module
{
	public $module_priority = 15;
	public function href_administrate_module() { return href('Payment', 'Orders'); }
	public function getClasses() { return ['GDO\Payment\Order']; }
	public function onLoadLanguage() { $this->loadLanguage('lang/payment'); }
}
