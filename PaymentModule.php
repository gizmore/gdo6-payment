<?php
namespace GDO\Payment;

use GDO\Core\GDO_Module;
use GDO\DB\GDT_Decimal;
use GDO\Date\Time;
use GDO\UI\GDT_Button;

abstract class PaymentModule extends GDO_Module
{
	/**
	 * @var PaymentModule[]
	 */
	public static $paymentModules = [];
	/**
	 * @return PaymentModule[]
	 */
	public static function allPaymentModules() { return self::$paymentModules; }
	

	public $module_priority = 25;

	public function initModule()
	{
		self::$paymentModules[$this->getName()] = $this;
		return parent::initModule();
	}
	
	public function getConfig()
	{
		return array(
			GDT_Decimal::make('fee_buy')->digits(1, 4)->initial('0.0000'),
		);
	}
	
	public function cfgFeeBuy() { return $this->getConfigValue('fee_buy'); }
	
	public function getPrice($price, $isWithTax=true)
	{
		$price = round(($this->cfgFeeBuy() + 1.00) * floatval($price), 2);
		if (!$isWithTax)
		{
			$mwst = Module_Payment::instance()->cfgTaxFactor();
			$price += $price * $mwst;
		}
		return $price;
	}
	
	public function displayPaymentFee()
	{
		return sprintf('%.03f%%', $this->cfgFeeBuy());
	}
	
	/**
	 * @param string $href
	 * @return GDT_Button
	 */
	public function makePaymentButton($href)
	{
		return GDT_Button::make('buy_'.$this->getName())->href($href)->icon('money');
// 		return $this->templatePHP('button.php', ['href' => $href]);
	}
	
	public function renderOrderFragment(GDO_Order $order)
	{
		return '';
	}
	
	public function getFooterHTML()
	{
		return '';
	}
	
	public function displayPaymentMethodName()
	{
		return t('payment_'.strtolower($this->getName()));
	}
	
	/**
	 * Verwendungszweck / Transfer usage
	 * @param GDO_Order $order
	 * @return string
	 */
	public function getTransferPurpose(GDO_Order $order)
	{
		$year = Time::getYear($order->getCreated());
		return sprintf('%s-%s-%09d', sitename(), $year, $order->getID());
	}
	
}
