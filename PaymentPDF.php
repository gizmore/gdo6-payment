<?php
namespace GDO\Payment;

use GDO\TCPDF\GDOTCPDF;
use GDO\User\GDO_User;
use GDO\File\GDO_File;

class PaymentPDF extends GDOTCPDF
{
	public function __construct()
	{
		parent::__construct('L', 'mm', 'A4', true, 'UTF-8', false, false);
	}
	
	/**
	 * 
	 * @param GDO_User $user
	 * @param GDO_Order $order
	 * @return GDO_File
	 */
	public static function generate(GDO_User $user, GDO_Order $order)
	{
		$pdf = new self();
		
	}
	
}
