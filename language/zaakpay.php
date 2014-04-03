<?php
/*
  

  OSCommerce  

 
*/

  define('MODULE_PAYMENT_ZAAKPAY_TEXT_TITLE', 'Zaakpay');
  if (MODULE_PAYMENT_ZAAKPAY_STATUS == 'True') {
    define('MODULE_PAYMENT_ZAAKPAY_TEXT_DESCRIPTION', '<a target="_blank" href="https://www.zaakpay.com/login-page">Zaakpay Merchant Login - Click here.</a> <br>') ;
  } else {
    define('MODULE_PAYMENT_ZAAKPAY_TEXT_DESCRIPTION', '<a target="_blank" href="https://www.zaakpay.com/sign-up">Click Here to Sign Up for a Zaakpay Account</a><br /><br /><a target="_blank" href="https://www.zaakpay.com/login-page">Click to Login to the Zaakpay Merchant Area</a><br /><br /><strong>Requirements:</strong><br /><hr />*<strong>Zaakpay Account</strong> (see link above to signup)<br />*<strong>Zaakpay Merchant Id and Secret key</strong> available from your Merchant Integration Area');
  }
  
  define('MODULE_PAYMENT_ZAAKPAY_TEXT_TYPE', 'Type:');
  define('MODULE_PAYMENT_ZAAKPAY_BUTTON_IMG', 'http://www.zaakpay.com/images/zaakpay_logo4.gif');
  define('MODULE_PAYMENT_ZAAKPAY_BUTTON_ALT', 'Checkout with Zaakpay');
  define('MODULE_PAYMENT_ZAAKPAY_ABOUT_TEXT', 'Simplifying Payments in India.<br />Pay Securely without sharing your financial information.');

  define('MODULE_PAYMENT_ZAAKPAY_TEXT_LOGO', '<img src="' . MODULE_PAYMENT_ZAAKPAY_BUTTON_IMG . '" alt="' . MODULE_PAYMENT_ZAAKPAY_BUTTON_ALT . '" title="' . MODULE_PAYMENT_ZAAKPAY_BUTTON_ALT . '" width="70" height="28"/> &nbsp;' .
                                                    '<span class="smallText">' . MODULE_PAYMENT_ZAAKPAY_ABOUT_TEXT . '</span>');
  define('MODULE_PAYMENT_ZAAKPAY_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_ZAAKPAY_TEXT_CREDIT_CARD_OWNER_FIRST_NAME', 'Credit Card Owner First Name:');
  define('MODULE_PAYMENT_ZAAKPAY_TEXT_CREDIT_CARD_OWNER_LAST_NAME', 'Credit Card Owner Last Name:');
  define('MODULE_PAYMENT_ZAAKPAY_TEXT_ERROR', 'Credit Card Error!');
  define('MODULE_PAYMENT_ZAAKPAY_TEXT_ERROR_MESSAGE', 'There has been an error while processing your credit card. Please try again.');
  define('MODULE_PAYMENT_ZAAKPAY_ALERT_ERROR_MESSAGE', 'Security Error ..! Illegal payment deducted.');
?>