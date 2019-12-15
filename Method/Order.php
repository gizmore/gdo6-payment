<?php
namespace GDO\Payment\Method;

use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\DB\GDT_Object;
use GDO\Payment\GDO_Order;
use GDO\Form\GDT_AntiCSRF;
use GDO\UI\GDT_HTML;
use GDO\Form\GDT_Submit;
use GDO\Date\Time;

final class Order extends MethodForm
{
	public function getPermission() { return 'staff'; }
	
	public function gdoParameters()
	{
		return array(
			GDT_Object::make('id')->table(GDO_Order::table())->notNull(),
		);
	}
	
	/**
	 * @return GDO_Order
	 */
	public function getOrder()
	{
		return $this->gdoParameterValue('id');
	}
	
	public function createForm(GDT_Form $form)
	{
		$order = $this->getOrder();
		$form->addField();
		$form->addFields(array(
			GDT_HTML::withHTML($order->getOrderable()->renderCard()),
			GDT_AntiCSRF::make(),
			GDT_Submit::make('btn_execute')->disabled($order->isPaid()),
		));
	}
	
	public function onSubmit_btn_execute()
	{
		$this->getOrder()->saveVar('order_paid', Time::getDate());
		$this->getOrder()->executeOrder();
		return $this->message('msg_order_execute');
	}

	
}
