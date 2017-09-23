<?php
namespace GDO\Payment;

use GDO\Core\Website;
use GDO\DB\GDO;
use GDO\DB\GDT_AutoInc;
use GDO\DB\GDT_CreatedAt;
use GDO\DB\GDT_CreatedBy;
use GDO\Date\GDT_DateTime;
use GDO\Date\Time;
use GDO\Template\GDT_Template;
use GDO\Template\Message;
use GDO\Type\GDT_Serialize;
use GDO\Type\GDT_String;
use GDO\User\GDO_User;
use GDO\Core\ModuleLoader;
/**
 * Serializes an orderable.
 * Stores price and item description.
 * 
 * @author gizmore
 * @since 3.0
 * @version 5.0
 * 
 * @see Orderable
 * @see GDT_Money
 * @see GDO_Currency
 * @see PaymentModule
 */
final class GDO_Order extends GDO
{
	public function gdoColumns()
	{
		return array(
			GDT_AutoInc::make('order_id'),
			GDT_String::make('order_xtoken')->ascii()->caseS()->max(64),
			GDT_String::make('order_title_en'),
			GDT_String::make('order_title'),
			GDT_Money::make('order_price'),
			GDT_Serialize::make('order_item'),
			GDT_PaymentModule::make('order_module')->editable(false),
			GDT_CreatedBy::make('order_by'),
			GDT_CreatedAt::make('order_at'),
			GDT_DateTime::make('order_paid')->editable(false)->label('paid_at'),
			GDT_DateTime::make('order_executed')->editable(false)->label('executed_at'),
		);
	}
	
	public function href_edit() { return href('Payment', 'Order', '&id='.$this->getID()); }
	public function href_view() { return href('Payment', 'ViewOrder', '&id='.$this->getID()); }
	public function href_failure() { return $this->getOrderable()->getOrderCancelURL(GDO_User::current()); }
	public function href_success() { return $this->getOrderable()->getOrderSuccessURL(GDO_User::current()); }

	public function redirectFailure() { return Website::redirectMessage($this->href_failure()); }
	public function redirectSuccess() { return Website::redirectMessage($this->href_success()); }
	
	public function getCreator() { return $this->getValue('order_by'); }
	public function getCreatorID() { return $this->getVar('order_by');  }
	public function isCreator(GDO_User $user) { return $this->getCreatorID() === $user->getID(); }
	
	public function getXToken() { return $this->getVar('order_xtoken'); }
	public function isPaid() { return $this->getPaid() !== null; }
	public function getPaid() { return $this->getVar('order_paid'); }
	public function isExecuted() { return $this->getExecuted() !== null; }
	public function getExecuted() { return $this->getVar('order_executed'); }
	
	/**
	 * @return GDO_User
	 */
	public function getUser()
	{
		return $this->getValue('order_by');
	}
	
	/**
	 * @return Orderable
	 */
	public function getOrderable()
	{
		return $this->getValue('order_item');
	}
	
	/**
	 * @return PaymentModule
	 */
	public function getPaymentModule()
	{
		return ModuleLoader::instance()->getModuleByID($this->getVar('order_module'));
	}
	
	public function getPrice() { return $this->getVar('order_price'); }
	public function displayPrice() { return $this->gdoColumn('order_price')->renderCell(); }
	public function getTitle() { return $this->getVar('order_title'); }
	public function getTitleEN() { return $this->getVar('order_title_en'); }
	
	##############
	### Render ###
	##############
	public function renderCard()
	{
		return GDT_Template::responsePHP('Payment', 'card/order.php', ['gdo' => $this]);
	}
	
	###############
	### Execute ###
	###############
	public function executeOrder()
	{
		$user = $this->getUser();
		$orderable = $this->getOrderable();
		
		$response = $orderable->onOrderPaid();

		$this->saveVar('order_executed', Time::getDate());
		$this->saveValue('order_item', $orderable);
		
		return Message::message('msg_order_execute')->add($response)->add($this->redirectSuccess());
	}
}
