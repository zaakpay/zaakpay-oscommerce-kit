
PAYMENT MODULE : ZAAKPAY
---------------------------
Allows you to use Zaakpay payment gateway with OSCommerce.

INSTALLATION PROCEDURE
--------------------------

Ensure you have a running version of oscommerce installed. This module was developed under OSCommerce 2.3.x

-	Extract the downloaded zip file , there are two files included with this package which are called "zaakpay.php" ,
-	Copy the file present in the folder "language" and paste it to (root_dir)\includes\languages\english\modules\payment\,
-	Copy the file present in the folder "payment" and paste it to (root_dir)\includes\modules\payment\.

CONFIGURATION
-----------------
OSCommerce Settings

-	Login to the administrator area of oscommerce,
-	Choose Payment under Modules in the top left panel and click Install Module at the top right of the payment window, 
-	Select zaakpay and install, Click the "edit" button and configure the following information,

- Enable ZAAKPAY Module: This MUST be "True" in order for the module to operate.

- Zaakpay Merchant ID: The Merchant Id provided by Zaakpay.

- Zaakpay Secret Key: Please note that get this key ,login to your Zaakpay merchant account 
and visit the "URL and Key's" section at the "Integration" tab and generate a Key.

- Transaction Mode: The mode you want to make transaction.  1.Test(Sandbox)	2.Live.

-	Click update/save .

Now you can make your payment securely through Zaakpay by selecting Zaakpay as the Payment Method at the Checkout stage.

