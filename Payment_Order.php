<?php
namespace GDO\Payment;

use GDO\Core\GDOError;
use GDO\Core\GDO;
use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\UI\GDT_Panel;
use GDO\Core\GDT_Response;
use GDO\User\GDO_Session;
use GDO\Core\GDT_Hook;

abstract class Payment_Order extends MethodForm
{
	/**
	 * @return Orderable
	 */
	public abstract function getOrderable();
	
// 	public abstract function cancelOrder();
	
	public function isUserRequired() { return true; }
	
	public function execute()
	{
// 		if (isset($_REQUEST['cancel']))
// 		{
// 			GDT_Hook::callHook('CancelOrder');
// // 			$this->cancelOrder();
// 			return $this->message('msg_order_cancelled');
// 		}
		return parent::execute();
	}
	
	public function formValidated(GDT_Form $form)
	{
		return $this->initOrderable($form);
	}
	
	public function initOrderable(GDT_Form $form=null)
	{
// 		$user = GDO_User::current();
		$orderable = $this->getOrderable();
		if (!($orderable instanceof GDO))
		{
			throw new GDOError('err_gdo_type', [$this->order->gdoClassName(), 'GDO']);
		}
		if (!($orderable instanceof Orderable))
		{
			throw new GDOError('err_gdo_type', [$this->order->gdoClassName(), 'Orderable']);
		}
		
		GDO_Session::set('gdo_orderable', $orderable);
// 		$user->tempSet('gdo_orderable', $orderable);
// 		$user->recache();
		
		return $this->renderOrderableForm($orderable);
	}
	
	public function renderOrderableForm(Orderable $orderable)
	{
		$form = new GDT_Form();
		$form->addField(); 
		foreach (PaymentModule::allPaymentModules() as $module)
		{
			if ($orderable->canPayOrderWith($module))
			{
				$form->addField($module->makePaymentButton(href('Payment', 'Choose', '&payment='.$module->getName())));
			}
		}
		return GDT_Response::makeWith(GDT_Panel::withHTML($orderable->renderCard()))->add(GDT_Response::makeWith($form));
	}
	
}
