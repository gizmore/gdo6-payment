<?php
namespace GDO\Payment;

use GDO\Core\GDOError;
use GDO\Core\GDO;
use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\Template\GDT_Panel;
use GDO\User\GDO_User;

abstract class Payment_Order extends MethodForm
{
	/**
	 * @return Orderable
	 */
	public abstract function getOrderable();
	
	public function isUserRequired() { return true; }
	
	public function formValidated(GDT_Form $form)
	{
		return $this->initOrderable($form);
	}
	
	public function initOrderable(GDT_Form $form=null)
	{
		$user = GDO_User::current();
		$orderable = $this->getOrderable();
		if (!($orderable instanceof GDO))
		{
			throw new GDOError('err_gdo_type', [$this->order->gdoClassName(), 'GDO']);
		}
		if (!($orderable instanceof Orderable))
		{
			throw new GDOError('err_gdo_type', [$this->order->gdoClassName(), 'Orderable']);
		}
		$user->tempSet('gdo_orderable', $orderable);
		$user->recache();
		
		return $this->renderOrderableForm($orderable);
	}
	
	public function renderOrderableForm(Orderable $orderable)
	{
		$form = new GDT_Form();
		$form->addField(GDT_Panel::make('card')->html($orderable->renderCard())); 
		foreach (PaymentModule::allPaymentModules() as $module)
		{
			if ($orderable->canPayOrderWith($module))
			{
				$form->addField($module->makePaymentButton(href('Payment', 'Choose', '&payment='.$module->getName())));
			}
		}
		return $form->render();
	}
	
}
