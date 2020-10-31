<?php
namespace GDO\Payment;

use GDO\Core\GDO_Module;
use GDO\UI\GDT_Bar;
use GDO\Date\Time;
use GDO\DB\GDT_Decimal;
use GDO\DB\GDT_String;
use GDO\Mail\GDT_Email;
use GDO\Language\GDT_Language;
use GDO\Address\Module_Address;
use GDO\UI\GDT_Divider;
use GDO\Date\GDT_Duration;
use GDO\DB\GDT_Checkbox;

final class Module_Payment extends GDO_Module
{
	public $module_priority = 15;
	public function getDependencies() { return array('Address', 'TCPDF'); }

	public function href_administrate_module() { return href('Payment', 'Orders'); }
	
	public function getClasses() { return ['GDO\Payment\GDO_Order']; }
	public function onLoadLanguage() { $this->loadLanguage('lang/payment'); }
	
	public function getConfig()
	{
		return array(
			GDT_String::make('company_name')->initial(sitename()),
			GDT_Decimal::make('tax_mwst')->digits(3, 1)->initial("16.0"),
			GDT_String::make('vat')->max(24)->initial('0000000000'),
			GDT_String::make('vat_office')->initial(Module_Address::instance()->cfgCity()),
			GDT_Duration::make('pay_time')->initial('14d'),
			GDT_Divider::make('div_billing_mails'),
			GDT_Language::make('billing_mail_language')->notNull()->initial(GWF_LANGUAGE),
			GDT_Email::make('billing_mail_sender')->initial(GWF_BOT_EMAIL),
			GDT_Email::make('billing_mail_reciver'),
			GDT_Checkbox::make('payment_feature_vat_no_tax')->initial('1'),
		);
	}
	
	public function cfgCompanyName() { return $this->getConfigVar('company_name'); }
	public function cfgTax() { return $this->getConfigValue('tax_mwst'); }
	public function cfgTaxFactor() { return $this->cfgTax() / 100.0; }
	public function cfgVat() { return $this->getConfigVar('vat'); }
	public function cfgVatNoTax() { return $this->getConfigValue('payment_feature_vat_no_tax'); }
	public function cfgVatOffice() { return $this->getConfigVar('vat_office'); }
	public function cfgPayTime() { return $this->getConfigValue('pay_time'); }
	public function cfgMailLanguage() { return $this->getConfigVar('billing_mail_language'); }
	public function cfgMailTo() { return $this->getConfigVar('billing_mail_reciver'); }
	public function cfgMailFrom() { return $this->getConfigVar('billing_mail_sender'); }
	
	public function hookRightBar(GDT_Bar $navbar)
	{
		$this->templatePHP('right_sidebar.php', ['bar' => $navbar]);
	}
	
	public function onExecuteOrder(PaymentModule $module, GDO_Order $order)
	{
		$order->saveVars(array(
			'order_paid' => Time::getDate(),
		));
		$order->executeOrder();
		BillingMails::sendBillPaidMails($order);
		return $this->message('msg_order_execute');
	}

	public function onPendingOrder(PaymentModule $module, GDO_Order $order)
	{
		return $this->error('err_order_pending');
	}
	
}
