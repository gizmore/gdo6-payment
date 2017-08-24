<?php
namespace GDO\Payment;

use GDO\Core\GDOError;
use GDO\DB\GDO;
use GDO\Form\GDO_Form;
use GDO\Form\MethodForm;
use GDO\Template\GDO_Panel;
use GDO\User\User;

abstract class Payment_Order extends MethodForm
{
	/**
	 * @return Orderable
	 */
	public abstract function getOrderable();
	
	public function isUserRequired() { return true; }
	
	public function formValidated(GDO_Form $form)
	{
		return $this->initOrderable($form);
	}
	
	public function initOrderable(GDO_Form $form=null)
	{
		$user = User::current();
		$orderable = $this->getOrderable();
		if (!($orderable instanceof GDO))
		{
			throw new GDOError('err_gdo_type', [$this->order->gdoClassName(), 'GDO']);
		}
		if (!($orderable instanceof Orderable))
		{
			throw new GDOError('err_gdo_type', [$this->order->gdoClassName(), 'Orderable']);
		}
		$user->tempSet('gwf_orderable', $orderable);
		$user->recache();
		
		return $this->renderOrderableForm($orderable);
	}
	
	public function renderOrderableForm(Orderable $orderable)
	{
		$form = new GDO_Form();
		$form->addField(GDO_Panel::make('card')->html($orderable->renderCard())); 
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
