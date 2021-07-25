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
use GDO\Payment\Module_Payment;
use GDO\UI\GDT_Divider;
use GDO\Util\Common;

/**
 * Edit an order. Staff method.
 * @author gizmore
 * @version 6.10.1
 * @since 6.3.1
 */
final class Order extends MethodForm
{
	public function getPermission() { return 'staff'; }
	
	public function isTrivial() { return false; }	
	
	public function gdoParameters()
	{
		return [
			GDT_Object::make('id')->table(GDO_Order::table())->notNull(),
		];
	}
	
	/**
	 * @return GDO_Order
	 */
	public function getOrder()
	{
		return GDO_Order::table()->find(Common::getRequestString('id'));
	}
	
	public function createForm(GDT_Form $form)
	{
		$order = $this->getOrder();
		$address = $order->getAddress();
		$form->addField(GDT_HTML::withHTML($order->getOrderable()->renderCard()));
		$form->addField(GDT_Divider::make()->label('div_order_section'));
		$form->addFields($order->gdoColumnsExcept('order_item', 'order_title'));
		$form->addFields($address->gdoColumnsExcept('address_id'));
		$form->addField(GDT_AntiCSRF::make());
		$form->actions()->addField(GDT_Submit::make('btn_edit'));
		$form->actions()->addField(GDT_Submit::make('btn_execute')->disabled($order->isPaid()));
	}

	public function onSubmit_btn_execute()
	{
		$order = $this->getOrder();
		$order->saveVars([
			'order_paid' => Time::getDate(),
			'order_price_paid' => $order->getPrice(),
		]);
		Module_Payment::instance()->onExecuteOrder($order->getPaymentModule(), $order);
		return $this->message('msg_order_execute')->addField($this->renderPage());
	}

	public function onSubmit_btn_edit()
	{
		$form = $this->getForm();
		$order = $this->getOrder();
		$order->saveVars($form->getFormData());
		if ($address = $order->getAddress())
		{
			$address->saveVars($form->getFormData());
		}
		$this->resetForm();
		return $this->message('msg_order_edited')->addField($this->renderPage());
	}
	
}
