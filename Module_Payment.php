<?php
namespace GDO\Payment;

use GDO\Core\GDO_Module;
use GDO\UI\GDT_Bar;
use GDO\Date\Time;

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
	
	public function onExecuteOrder(PaymentModule $module, GDO_Order $order)
	{
		$order->saveVars(array(
			'order_paid' => Time::getDate(),
		));
		$order->executeOrder();
		return $this->message('msg_order_execute');
	}

	public function onPendingOrder(PaymentModule $module, GDO_Order $order)
	{
		return $this->error('err_order_pending');
	}
}
