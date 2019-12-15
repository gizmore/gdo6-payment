<?phpuse GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;/** @var $bar GDT_Bar **/
$bar instanceof GDT_Bar;$bar->addField(GDT_Link::make('link_your_orders')->href(href('Payment', 'YourOrders')));