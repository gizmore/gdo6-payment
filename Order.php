<?php
namespace GDO\Payment;

use GDO\Core\Website;
use GDO\DB\GDO;
use GDO\DB\GDO_AutoInc;
use GDO\DB\GDO_CreatedAt;
use GDO\DB\GDO_CreatedBy;
use GDO\Date\GDO_DateTime;
use GDO\Date\Time;
use GDO\Template\GDO_Template;
use GDO\Template\Message;
use GDO\Type\GDO_Serialize;
use GDO\Type\GDO_String;
use GDO\User\User;
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
 * @see GDO_Money
 * @see Currency
 * @see PaymentModule
 */
final class Order extends GDO
{
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('order_id'),
			GDO_String::make('order_xtoken')->ascii()->caseS()->max(64),
			GDO_String::make('order_title_en'),
			GDO_String::make('order_title'),
			GDO_Money::make('order_price'),
			GDO_Serialize::make('order_item'),
			GDO_PaymentModule::make('order_module')->editable(false),
			GDO_CreatedBy::make('order_by'),
			GDO_CreatedAt::make('order_at'),
			GDO_DateTime::make('order_paid')->editable(false)->label('paid_at'),
			GDO_DateTime::make('order_executed')->editable(false)->label('executed_at'),
		);
	}
	
	public function href_edit() { return href('Payment', 'Order', '&id='.$this->getID()); }
	public function href_view() { return href('Payment', 'ViewOrder', '&id='.$this->getID()); }
	public function href_failure() { return $this->getOrderable()->getOrderCancelURL(User::current()); }
	public function href_success() { return $this->getOrderable()->getOrderSuccessURL(User::current()); }

	public function redirectFailure() { return Website::redirectMessage($this->href_failure()); }
	public function redirectSuccess() { return Website::redirectMessage($this->href_success()); }
	
	public function getCreator() { return $this->getValue('order_by'); }
	public function getCreatorID() { return $this->getVar('order_by');  }
	public function isCreator(User $user) { return $this->getCreatorID() === $user->getID(); }
	
	public function getXToken() { return $this->getVar('order_xtoken'); }
	public function isPaid() { return $this->getPaid() !== null; }
	public function getPaid() { return $this->getVar('order_paid'); }
	public function isExecuted() { return $this->getExecuted() !== null; }
	public function getExecuted() { return $this->getVar('order_executed'); }
	
	/**
	 * @return User
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
		return GDO_Template::php('Payment', 'card/order.php', ['gdo' => $this]);
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
		
		return Message::message('msg_order_execute')->add($response)->add($this->redirectSuccess());
	}
}
