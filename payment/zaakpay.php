<?php

  class zaakpay {
    var $code, $title, $description, $enabled;

// class constructor
    function zaakpay() {
      $this->code = 'zaakpay';
      $this->title = MODULE_PAYMENT_ZAAKPAY_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_ZAAKPAY_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_ZAAKPAY_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_ZAAKPAY_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_ZAAKPAY_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_ZAAKPAY_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

$this->form_action_url = 'https://api.zaakpay.com/transact';
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_ZAAKPAY_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_ZAAKPAY_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }

// disable the module if the order only contains virtual products
      if ($this->enabled == true) {
        if ($order->content_type == 'virtual') {
          $this->enabled = false;
        }
      }
    }
    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
  return false;
  
    }

   function process_button() {
    global $order, $currencies,$customer_id, $MerchantId, $Amount, $OrderId, $Url, $SecretKey, $checksum
		;

  $MerchantId = MODULE_PAYMENT_ZAAKPAY_MERCHANT_ID;
  $Amount = 100 * $order->info['total']; 		//Should be in paisa
 
 $OrderId = date('Ymdhis');					//$customer_id . '-' . date('Ymdhis');
  $Url = tep_href_link(FILENAME_CHECKOUT_PROCESS,'','SSL');
  $pattern='http://www\.';

    if(!(Eregi($pattern,$Url,$reg)))
        eregi_replace('http://', $pattern, $Url);
  $desc = "Payment for ". STORE_NAME ." ".$OrderId;
  $SecretKey = MODULE_PAYMENT_ZAAKPAY_SECRET_KEY;
  $mod = MODULE_PAYMENT_ZAAKPAY_MODE;
  if($mod == "Test")
  $mode = 0;
  else
  $mode = 1;
  
  $post_variables = array(
		
			"merchantIdentifier" => $MerchantId,
			"orderId" => $OrderId,
			"returnUrl" => $Url,
			"buyerEmail" => $order->customer['email_address'],
			"buyerFirstName" => $order->customer['firstname'],
			"buyerLastName" => $order->customer['lastname'],
			"buyerAddress" => $order->customer['street_address'],
			"buyerCity" => $order->customer['city'],
			"buyerState" => $order->customer['state'],
			"buyerCountry" => $order->customer['country']['title'],
			"buyerPincode" =>  $order->customer['postcode'],
			"buyerPhoneNumber" => $order->customer['telephone'],
			"currency" => "INR",		//$order->customer['currency']['code'],
			"amount" => $Amount,									
			"productDescription" => $desc,
		    "shipToAddress" => $order->delivery['street_address'],	
			"shipToCity" => $order->delivery['city'],			
			"shipToState" => $order->delivery['state'],
			"shipToCountry" => $order->delivery['country']['title'],
		    "shipToPincode" => $order->delivery['postcode'],
		    "shipToPhoneNumber" => $order->customer['telephone'],
			"shipToFirstname" => $order->delivery['firstname'],
			"shipToLastname" => $order->delivery['lastname'],
			"txnType" => 1,
			"zpPayOption" => 1,
			"mode" => $mode,
			"merchantIpAddress" => "127.0.0.1",  	//Merchant Ip Address
			"purpose" => 1,
			"txnDate" => date('Y-m-d'),
  
  );
  
  $all = '';
		foreach($post_variables as $name => $value)	{
			if($name != 'checksum') {
				$all .= "'";
				if ($name == 'returnUrl') {
					$all .= Checksum::sanitizedURL($value);
				} else {				
					
					$all .= Checksum::sanitizedParam($value);
				}
				$all .= "'";
			}
		}
		
		$log = MODULE_PAYMENT_ZAAKPAY_LOG;
		if($log == "Yes")
		{
			error_log("All Params : ".$all);
			error_log("Zaakpay Secret Key : ".$SecretKey);
		}
		
		$checksum = Checksum::calculateChecksum($SecretKey,$all);
  

    //POST Parameters to Zaakpay
    $process_button_string = tep_draw_hidden_field('merchantIdentifier', $MerchantId) .
					   tep_draw_hidden_field('orderId',  Checksum::sanitizedParam($OrderId)) .
					   tep_draw_hidden_field('returnUrl', $Url) .
					   tep_draw_hidden_field('buyerEmail', $order->customer['email_address']) .
					   tep_draw_hidden_field('buyerFirstName', $order->customer['firstname']) .
					   tep_draw_hidden_field('buyerLastName', $order->customer['lastname']) .
					   tep_draw_hidden_field('buyerAddress', Checksum::sanitizedParam($order->billing['street_address'])) .
					   tep_draw_hidden_field('buyerCity', $order->billing['city']) .
					   tep_draw_hidden_field('buyerState', $order->billing['state']) .
					   tep_draw_hidden_field('buyerCountry', $order->billing['country']['title']) .
					   tep_draw_hidden_field('buyerPincode', $order->billing['postcode']) .
					   tep_draw_hidden_field('buyerPhoneNumber', $order->customer['telephone']) .
					   tep_draw_hidden_field('currency', "INR") .	
					   tep_draw_hidden_field('amount', $Amount) .
					   tep_draw_hidden_field('productDescription', Checksum::sanitizedParam($desc)) .
					   
					   tep_draw_hidden_field('shipToAddress', Checksum::sanitizedParam($order->delivery['street_address'])) .
                       tep_draw_hidden_field('shipToCity', $order->delivery['city']) .
                       tep_draw_hidden_field('shipToState', $order->delivery['state']) .
                       tep_draw_hidden_field('shipToCountry', $order->delivery['country']['title']) .
                       tep_draw_hidden_field('shipToPincode', $order->delivery['postcode']) .
                       tep_draw_hidden_field('shipToPhoneNumber', $order->customer['telephone']) .
					   tep_draw_hidden_field('shipToFirstname', $order->delivery['firstname']) .
                       tep_draw_hidden_field('shipToLastname', $order->delivery['lastname']) .
					   tep_draw_hidden_field('txnType', 1) .
					   tep_draw_hidden_field('zpPayOption', 1) .
					   tep_draw_hidden_field('mode', "$mode") .
					   tep_draw_hidden_field('merchantIpAddress', '127.0.0.1') .
					   tep_draw_hidden_field('purpose', 1) .
					   tep_draw_hidden_field('txnDate', date('Y-m-d')) .
                       tep_draw_hidden_field('checksum',$checksum) ;                      
	                       
      return $process_button_string;
    }

    function before_process() {
    global $HTTP_POST_VARS, $customer_id, $Order_id, $SecretKey, $checksum_recv, $res_code, $res_desc
;

  $order_id = $HTTP_POST_VARS['orderId'];
  $checksum_recv = $HTTP_POST_VARS['checksum'];
  $res_code = $HTTP_POST_VARS['responseCode'];
  $res_desc = $HTTP_POST_VARS['responseDescription'];

  $SecretKey = MODULE_PAYMENT_ZAAKPAY_SECRET_KEY;
   
   $all = ("'". $order_id ."''". $res_code ."''". $res_desc."'");
   $bool = 0;
	  $bool = Checksum::verifyChecksum($checksum_recv, $all, $SecretKey);


if($bool == '1'){
if($res_code != '100')
{
tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_ZAAKPAY_TEXT_ERROR_MESSAGE), 'SSL', true, false));
}
}

else{

tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_ZAAKPAY_ALERT_ERROR_MESSAGE), 'SSL', true, false));
}


    }

    function after_process() {
      return false;
    }
	

    function output_error() {
   global $HTTP_GET_VARS;

   $output_error_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;<font color="#FF0000"><b>' . MODULE_PAYMENT_ZAAKPAY_TEXT_ERROR . '</b></font><br>&nbsp;' . MODULE_PAYMENT_ZAAKPAY_TEXT_ERROR_MESSAGE . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";

      return $output_error_string;
    }
	
	

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ZAAKPAY_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }
 function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable ZAAKPAY Module', 'MODULE_PAYMENT_ZAAKPAY_STATUS', 'True', 'Do you want to accept ZAAKPAY payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Identifier', 'MODULE_PAYMENT_ZAAKPAY_MERCHANT_ID', 'Zaakpay MerchantId', 'The Merchant Id given by Zaakpay', '6', '2', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('SecretKey', 'MODULE_PAYMENT_ZAAKPAY_SECRET_KEY', 'Zaakpay Secret Key', 'Zaakpay Merchant secret key.Please note that get this key ,login to your Zaakpay merchant account', '6', '2', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_ZAAKPAY_MODE', 'Test', 'Mode of transactions : Test(Sandbox) or Live ', '6', '0', 'tep_cfg_select_option(array(\'Test\',\'Live\'), ', now())");
      
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('ZAAKPAY Payment Zone', 'MODULE_PAYMENT_ZAAKPAY_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ZAAKPAY Sort order of  display.', 'MODULE_PAYMENT_ZAAKPAY_SORT_ORDER', '0', 'Sort order of ZAAKPAY display. Lowest is displayed first.', '6', '0', now())");
      
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('ZAAKPAY Set Order Status', 'MODULE_PAYMENT_ZAAKPAY_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Log', 'MODULE_PAYMENT_ZAAKPAY_LOG', 'No', 'Would you like to enable log? A detailed log of parameters which are used to calculate Checksum', '6', '0', 'tep_cfg_select_option(array(\'Yes\', \'No\'), ', now())");

    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }

       function keys() {
      return array('MODULE_PAYMENT_ZAAKPAY_STATUS', 'MODULE_PAYMENT_ZAAKPAY_MERCHANT_ID','MODULE_PAYMENT_ZAAKPAY_SECRET_KEY','MODULE_PAYMENT_ZAAKPAY_MODE','MODULE_PAYMENT_ZAAKPAY_ZONE','MODULE_PAYMENT_ZAAKPAY_SORT_ORDER', 'MODULE_PAYMENT_ZAAKPAY_ORDER_STATUS_ID', 'MODULE_PAYMENT_ZAAKPAY_LOG' );
    }
  }
  
  
  class Checksum {

	var $hash, $checksum, $secret_key, $all;
	
	
	function calculateChecksum($secret_key, $all) {
			
		
		$hash = hash_hmac('sha256', $all , $secret_key);
		$checksum = $hash;
		
		return $checksum;
	}
	
	 function verifyChecksum($checksum, $all, $secret) {
		
		$hash = hash_hmac('sha256', $all , $secret);
		$cal_checksum = $hash;
		$bool = 0;
		if($checksum == $cal_checksum)	{
			$bool = 1;
		}

		return $bool;
	}

	
	function sanitizedParam($param) {
			
			$pattern[0] = "%,%";
	        $pattern[1] = "%#%";
	        $pattern[2] = "%\(%";
       		$pattern[3] = "%\)%";
	        $pattern[4] = "%\{%";
	        $pattern[5] = "%\}%";
	        $pattern[6] = "%<%";
	        $pattern[7] = "%>%";
	        $pattern[8] = "%`%";
	        $pattern[9] = "%!%";
	        $pattern[10] = "%\\$%";
	        $pattern[11] = "%\%%";
	        $pattern[12] = "%\^%";
	        $pattern[13] = "%=%";
	        $pattern[14] = "%\+%";
	        $pattern[15] = "%\|%";
	        $pattern[16] = "%\\\%";
	        $pattern[17] = "%:%";
	        $pattern[18] = "%'%";
	        $pattern[19] = "%\"%";
	        $pattern[20] = "%;%";
	        $pattern[21] = "%~%";
	        $pattern[22] = "%\[%";
	        $pattern[23] = "%\]%";
	        $pattern[24] = "%\*%";
	        $pattern[25] = "%&%";
        	$sanitizedParam = preg_replace($pattern, "", $param);
		
		return $sanitizedParam;
	}
	
	function sanitizedURL($param) {
	
			$pattern[0] = "%,%";
	        $pattern[1] = "%\(%";
       		$pattern[2] = "%\)%";
	        $pattern[3] = "%\{%";
	        $pattern[4] = "%\}%";
	        $pattern[5] = "%<%";
	        $pattern[6] = "%>%";
	        $pattern[7] = "%`%";
	        $pattern[8] = "%!%";
	        $pattern[9] = "%\\$%";
	        $pattern[10] = "%\%%";
	        $pattern[11] = "%\^%";
	        $pattern[12] = "%\+%";
	        $pattern[13] = "%\|%";
	        $pattern[14] = "%\\\%";
	        $pattern[15] = "%'%";
	        $pattern[16] = "%\"%";
	        $pattern[17] = "%;%";
	        $pattern[18] = "%~%";
	        $pattern[19] = "%\[%";
	        $pattern[20] = "%\]%";
	        $pattern[21] = "%\*%";
        	$sanitizedParam = preg_replace($pattern, "", $param);
			
		return $sanitizedParam;
	}
	

	
}
?>