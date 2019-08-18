<?php
namespace GDO\Payment\Method;

use GDO\Core\Method;
use GDO\Core\GDO;
use GDO\Language\Trans;
use GDO\Payment\GDO_Order;
use GDO\Payment\Orderable;
use GDO\Payment\PaymentModule;
use GDO\User\GDO_User;
use GDO\Util\Common;
use GDO\Core\ModuleLoader;
use GDO\Core\GDT_Serialize;
use GDO\User\GDO_Session;
/**
 * Step 1 – Choose a payment processor
 * @author gizmore
 */
final class Choose extends Method
{
	/**
	 * @var GDO_User
	 */
	private $user;
	
	/**
	 * @var Orderable
	 */
	private $orderable;
	
	/**
	 * @var PaymentModule
	 */
	private $paymentModule;
	
	/**
	 * @var GDO_Order
	 */
	private $order;
	
	/**
	 * @return Orderable|GDO
	 */
	public function getOrderable()
	{
		return GDO_Session::get('gdo_orderable');
// 		return GDO_User::current()->tempGet('gdo_orderable');
	}
	
	public function execute()
	{
		$this->user = GDO_User::current();
		if (!($this->orderable = $this->getOrderable()))
		{
			return $this->error('err_orderable');
		}
		$moduleName = Common::getRequestString('payment');
		if (!($this->paymentModule = ModuleLoader::instance()->getModule($moduleName)))
		{
			return $this->error('err_module', [html($moduleName)]);
		}
		$this->order = GDO_Order::blank(array(
			'order_title_en' => $this->orderable->getOrderTitle('en'),
			'order_title' => $this->orderable->getOrderTitle(Trans::$ISO),
			'order_price' => $this->paymentModule->getPrice($this->orderable->getOrderPrice()),
			'order_item' => GDT_Serialize::serialize($this->orderable),
			'order_module' => $this->paymentModule->getID(),
		));
		
		GDO_Session::set('gdo_order', $this->order);
// 		$this->user->tempSet('gdo_order', $this->order);
// 		$this->user->recache();
		
		$tVars = array(
			'user' => $this->user,
			'orderable' => $this->orderable,
			'payment' => $this->paymentModule,
			'order' => $this->order,
		);
		return $this->templatePHP('choose.php', $tVars);
	}
}
