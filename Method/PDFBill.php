<?php
namespace GDO\Payment\Method;

use GDO\Core\Method;
use GDO\DB\GDT_Object;
use GDO\Payment\GDO_Order;
use GDO\User\GDO_User;
use GDO\Payment\PaymentPDF;
use GDO\Net\Stream;
use GDO\Core\Website;
use GDO\Language\Trans;

/**
 * Download a PDF bill.
 * 
 * @author gizmore
 * @version 6.10
 * @since 6.10
 * @see PaymentPDF
 */
final class PDFBill extends Method
{
	/**
	 * @return GDO_Order
	 */
	public function getOrder()
	{
		return $this->gdoParameterValue('id');
	}
	
	public function gdoParameters()
	{
		return array(
			GDT_Object::make('id')->table(GDO_Order::table()),
		);
	}
	
	public function hasUserPermission(GDO_User $user)
	{
		if ($user->isStaff())
		{
			return true;
		}
		return $this->getOrder()->getCreator() === $user ? true : $this->error('err_order');
	}
	
	public function execute()
	{
		$order = $this->getOrder();
		
		$file = PaymentPDF::generate($order->getCreator(), $order, Trans::$ISO);

		if (Website::outputStarted())
		{
			return $this->error('err_cannot_stream_output_started');
		}
		
		Stream::serve($file, '', false);
	}
	
}
