<?php
namespace GDO\Payment\Method;

use GDO\Core\Application;
use GDO\Core\Method;
use GDO\DB\GDO;
use GDO\Language\Trans;
use GDO\Payment\Order;
use GDO\Payment\Orderable;
use GDO\Payment\PaymentModule;
use GDO\User\User;
use GDO\Util\Common;
use GDO\Core\ModuleLoader;
use GDO\Type\GDO_Serialize;
/**
 * Step 1 – Choose a payment processor
 * @author gizmore
 */
final class Choose extends Method
{
	/**
	 * @var User
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
	 * @var Order
	 */
	private $order;
	
	/**
	 * @return Orderable|GDO
	 */
	public function getOrderable()
	{
		return User::current()->tempGet('gwf_orderable');
	}
	
	public function execute()
	{
		$this->user = User::current();
		if (!($this->orderable = $this->getOrderable()))
		{
			return $this->error('err_orderable');
		}
		$moduleName = Common::getRequestString('payment');
		if (!($this->paymentModule = ModuleLoader::instance()->getModule($moduleName)))
		{
			return $this->error('err_module', [html($moduleName)]);
		}
		$this->order = Order::blank(array(
			'order_title_en' => $this->orderable->getOrderTitle('en'),
			'order_title' => $this->orderable->getOrderTitle(Trans::$ISO),
			'order_price' => $this->paymentModule->getPrice($this->orderable->getOrderPrice()),
			'order_item' => GDO_Serialize::serialize($this->orderable),
			'order_module' => $this->paymentModule->getID(),
		));
		$this->user->tempSet('gwf_order', $this->order);
		$this->user->recache();
		
		$tVars = array(
			'user' => $this->user,
			'orderable' => $this->orderable,
			'payment' => $this->paymentModule,
			'order' => $this->order,
		);
		return $this->templatePHP('choose.php', $tVars);
	}
}
