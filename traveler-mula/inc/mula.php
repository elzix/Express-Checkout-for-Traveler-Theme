<?php
/**
 * Created by Erix Kivuti
 * Date: 18-06-2019
 * Time: 7:08 AM
 * Sandbox Success: 4111 1111 1111 1111
 * Sandbox Failure: 3782 8224 6310 005
 */

if (!class_exists('ST_Mula_Payment_Gateway')) {
	class ST_Mula_Payment_Gateway extends STAbstactPaymentGateway
	{
		public static $_ints;
		private $default_status = TRUE;

		private $_gatewayObject = null;

		private $_gateway_id = 'st_mula';

		function __construct()
		{
			add_filter('st_payment_gateway_st_mula', array($this, 'get_name'));
			
		}

		function get_option_fields()
		{
			return array(
				array(
					'id'		=> 'mula_enable_live',
					'label'	 => __('Enable Live Mode', 'traveler-mula'),
					'type'	  => 'on-off',
					'section'   => 'option_pmgateway',
					'std'	   => 'off',
					'desc'	  => __('Allow you to enable live mode for use', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on)'
				),
				array(
					'id'		=> 'mula_enable_express',
					'label'	 => __('Enable Express checkout', 'traveler-mula'),
					'type'	  => 'on-off',
					'section'   => 'option_pmgateway',
					'std'	   => 'on',
					'desc'	  => __('Allow you to enable live mode for use', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on)'
				),
				array(
					'id'		=> 'mula_service_code',
					'label'	 => __('Service Code', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => get_bloginfo('name').__("'s service code", 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(on)'
				),
				array(
					'id'		=> 'mula_client_code',
					'label'	 => __('Client Code', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('Your business client code', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(on)'
				),
				array(
					'id'		=> 'mula_iv_key',
					'label'	 => __('IV Key', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('This is one of the pair of keys you need to encrypt your data', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(on),mula_enable_express:is(on)'
				),
				array(
					'id'		=> 'mula_secret_key',
					'label'	 => __('Secret Key', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('This is one of the pair of keys you need to encrypt your data', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(on),mula_enable_express:is(on)'
				),
				array(
					'id'		=> 'mula_access_key',
					'label'	 => __('Access Key', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('Pass this key when you are sending the parameters to checkout', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(on),mula_enable_express:is(on)'
				),
				array(
					'id'		=> 'mula_client_id',
					'label'	 => __('Client ID', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('You use this as one of the pair of keys you need to generate an access token', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(on),mula_enable_express:is(off)'
				),
				array(
					'id'		=> 'mula_client_secret',
					'label'	 => __('Client Secret', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('This is one of the pair of keys you need to encrypt your data', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(on),mula_enable_express:is(off)'
				),
				array(
					'id'		=> 'mula_test_service_code',
					'label'	 => __('Sandbox Service Code', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => get_bloginfo('name').__("'s service code", 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(off)'
				),
				array(
					'id'		=> 'mula_test_client_code',
					'label'	 => __('Sandbox Client Code', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('Your business client code', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(off)'
				),
				array(
					'id'		=> 'mula_test_iv_key',
					'label'	 => __('Sandbox IV Key', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('This is one of the pair of keys you need to encrypt your data', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(off),mula_enable_express:is(on)'
				),
				array(
					'id'		=> 'mula_test_secret_key',
					'label'	 => __('Sandbox Secret Key', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('This is one of the pair of keys you need to encrypt your data', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(off),mula_enable_express:is(on)'
				),
				array(
					'id'		=> 'mula_test_access_key',
					'label'	 => __('Sandbox Access Key', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('Pass this key when you are sending the parameters to checkout', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(off),mula_enable_express:is(on)'
				),
				array(
					'id'		=> 'mula_test_client_id',
					'label'	 => __('Sandbox Client ID', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('You use this as one of the pair of keys you need to generate an access token', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(off),mula_enable_express:is(off)'
				),
				array(
					'id'		=> 'mula_test_client_secret',
					'label'	 => __('Sandbox Client Secret', 'traveler-mula'),
					'type'	  => 'text',
					'section'   => 'option_pmgateway',
					'desc'	  => __('This is one of the pair of keys you need to encrypt your data', 'traveler-mula'),
					'condition' => 'pm_gway_st_mula_enable:is(on),mula_enable_live:is(off),mula_enable_express:is(off)'
				),
			);
		}

		/**
		* Every request made to Mula Checkout API is authorised using an
		* access_token which is obtained by sending 
		* a client_secret and client_id to the authentication route.
		* 
		* For security purposes, each access token is valid for 1 minute
		* and so each request should follow a cycle of authentication
		* and authorization (i.e. sending access_token as part of
		* Authorization header ie: Authorization Bearer access_token)
		*
		* @param $client_id Needed to generate an access token.
		* @param $client_secret Needed to authenticate client_id.
		* @return string
		*/
		public function authenticate()
		{
			$url = 'https://beep2.cellulant.com:9212/checkout/v2/custom/oauth/token';
			$client_id = (st()->get_option('mula_enable_live', 'off') == 'off')?
				st()->get_option('mula_test_client_id') : st()->get_option('mula_client_id');
			$client_secret = (st()->get_option('mula_enable_live', 'off') == 'off')?
				st()->get_option('mula_test_client_secret') : st()->get_option('mula_client_secret');
			$response = WP_Http_Curl::request($url,
				[
				'form_params' => [
					'grant_type' => 'client_credentials',
					'client_id' => $client_id,
					'client_secret' => $client_secret
				]
			]);
			$access_token = json_decode((string) $response->getBody(), true)['access_token'];
			return $access_token;
		}

		/**
		* Encrypt the string of customer details with the IV and secret key.
		*
		* @param $payload Pass in the array of parameters to be pass to express checkout.
		* @return string
		*/
		private function encryptData($payload = []) {
			//The encryption method to be used
			$encrypt_method = "AES-256-CBC";
			// Hash the secret key
			$key = (st()->get_option('mula_enable_live', 'off') == 'off')?
				hash('sha256', st()->get_option('mula_test_secret_key')) : 
				hash('sha256', st()->get_option('mula_secret_key'));
			// Hash the iv - encrypt method AES-256-CBC expects 16 bytes
			$iv = (st()->get_option('mula_enable_live', 'off') == 'off')?
				substr(hash('sha256', st()->get_option('mula_test_iv_key')), 0, 16) : 
				substr(hash('sha256', st()->get_option('mula_iv_key')), 0, 16);
			$encrypted = openssl_encrypt(
				json_encode($payload, true),
				$encrypt_method,
				$key,
				0,
				$iv
			);
			//Base 64 Encode the encrypted payload
			$encrypted = base64_encode($encrypted);
			return array(
				'params' => $encrypted,
				'accessKey' => $payload['accessKey'],
				'countryCode' => $payload['countryCode']
			);
		}


		function _pre_checkout_validate()
		{
			$validate=new STValidate();
			$enable_express = st()->get_option('mula_enable_express', 'on');
			// (req) string: The customer's first name.
			$validate->set_rules('st_first_name',__("First Name",'traveler-mula'),'required');
			// (req) string: The customer's last name.
			$validate->set_rules('st_last_name',__("Last Name",'traveler-mula'),'required');
			// (req) string: The customer's email.
			$validate->set_rules('st_email',__("Email",'traveler-mula'),'required');
			// (req) integer: The customer's country phone code.
			$validate->set_rules('st_country_code',__("Country Code",'traveler-mula'),'required');
			// (req) integer: The customer's mobile number.
			$validate->set_rules('st_phone',__("Phone Number",'traveler-mula'),'required');

			if(!$validate->run())
			{
				STTemplate::set_message($validate->error_string());
				return FALSE;
			}

			return true;
		}

        public function before_payment( $order_id )
        {
        	$cart = STCart::get_items();
        	$cart = array_keys($cart);
            $item = STCart::find_item($cart[0]);
			$checkin = new DateTime($item['data']['check_in']);
			$checkout = new DateTime($item['data']['check_out']);
            switch ($item['data']['st_booking_post_type']) {
            	case 'st_car':
				    $price_with_tax = (float)$item['data']['price_with_tax'];
				    $price_coupon = floatval(STCart::get_coupon_amount());
	                if ($price_coupon < 0) $price_coupon = 0;
	                $price_with_tax -= $price_coupon;
            		break;
            	default:
				    $extra_price = isset($item['data']['extra_price']) ?
				    	floatval($item['data']['extra_price']) : 0;
				    $price_coupon = floatval(STCart::get_coupon_amount());
				    $price_with_tax = STPrice::getPriceWithTax(
				    	$item['price'] + $extra_price);
				    $price_with_tax -= $price_coupon;
            		break;
            }

            $productCode = $cart[0];
            $item = get_post($productCode);
			$accessKey = (st()->get_option('mula_enable_live') == 'off')?
				st()->get_option('mula_test_access_key') : st()->get_option('mula_access_key');
			$serviceCode = (st()->get_option('mula_enable_live') == 'off')?
				st()->get_option('mula_test_service_code') : st()->get_option('mula_service_code');
			$phone = intval(STInput::post('st_country_code').STInput::post('st_phone'));

			$total = $price_with_tax;
			$total = round((float)$total, 2);
			$currency = TravelHelper::get_current_currency('name');
			$descr = $checkin->format('d M').' - '.
					$checkout->format('d M').': '.$item->post_title;

			if (isset($_SESSION['st_current_language'])){
				$current_language = $_SESSION['st_current_language'];
			} elseif (isset($_SESSION['st_current_language_1'])){
				$current_language = $_SESSION['st_current_language_1'];
			} elseif (defined('ICL_LANGUAGE_CODE')) {
				$current_language = ICL_LANGUAGE_CODE;
			} elseif (function_exists('qtrans_getLanguage')) {
				$current_language = qtrans_getLanguage();
			}
			$current_language = ($current_language != '')?
				$current_language : 'en';

			// Check deadline
			$today = new DateTime('today');
			$tomorrow = new DateTime('tomorrow');
			$interval = $tomorrow->diff($checkin);
			$dueDate = ($interval->invert > 0)?
				$checkin->format('Y-m-d H:i:s'):$tomorrow->format('Y-m-d H:i:s');

			$blogURL = parse_url(get_bloginfo('url'));
			$purchase = array(
				'merchantTransactionID' => $order_id, // (req) string: The merchant's unique transaction identifier.
				'customerFirstName' => STInput::post('st_first_name'), // (req) string: The customer's first name.
				'customerLastName' => STInput::post('st_last_name'), // (req) string: The customer's last name.
				'customerEmail' => STInput::post('st_email'), // (req) string: The customer's email.
				'amount' => (double)$total, // (req) double: The total amount that the customer is going to pay.
				'accountNumber' => $blogURL['host'], // (req) string: The account number/reference number for the transaction.
				'currencyCode' => $currency, // (req) string: The currency the amount passed is in.
				'languageCode' => $current_language, // (null) string: The merchant's language language code.
				'serviceDescription' => $descr, // (null) string: The transaction's narrative.
				'transactionID' => $today->format('Ymd-').$order_id, // not documented
				'serviceCode' => $serviceCode, // (req) string: The merchant's service code.
				'productCode' => $productCode, // not documented
				// 'payerClientCode' => $payerClientCode, // not documented
				'MSISDN' => $phone, // (req) integer: The customer's mobile number.
				'countryCode' => 'KE', // (req) string: The merchant's default country country code.
				'accessKey' => $accessKey, // (req) string: Pass this key when you are sending the parameters to checkout
				'dueDate' => $dueDate, // (req) string: The transaction's due date in the format YYYY-MM-DD HH:mm:ss. This should be in UTC time.
				'successRedirectUrl' => $this->get_return_url($order_id),
				'failRedirectUrl' => get_bloginfo('url').'/checkout/',
				'paymentWebhookUrl' => $this->get_return_url($order_id).'&callback='.md5($serviceCode.$order_id),
			);

			return $this->encryptData($purchase);
        }

		function do_checkout($order_id)
		{
			$library = (st()->get_option('mula_enable_live') == 'off')?
				'https://beep2.cellulant.com:9212/checkout/v2/mula-checkout.js' : 
				'https://beep2.cellulant.com:9212/checkout/v2/mula-checkout.js'; // In case it's different
            $encrypted = $this->before_payment( $order_id );
            $file = fopen($library,"r");
            $mulaURL = fgets($file);
			fclose($file);
			preg_match('/(?:EXPRESS_CHECKOUT_URL:")(.*)(?:",MUL)/', $mulaURL, $payURL);
            $mulaURL = add_query_arg( $encrypted, $payURL[1] );

            return [
                'redirect' => $mulaURL,
                'status'   => true
            ];
            
		}

        function complete_purchase( $order_id )
        {
            return true;
        }

        function check_complete_purchase( $order_id )
        {
			$serviceCode = (st()->get_option('mula_enable_live') == 'off')?
				st()->get_option('mula_test_service_code') : st()->get_option('mula_service_code');

			if(isset($_GET[ 'callback' ])){
				if($_GET[ 'callback' ] == md5($serviceCode.$order_id)){
					if(isset($_POST[ 'payerTransactionID' ])){
						if($_POST[ 'requestStatusCode' ] == 178){
							return ['status'=>true];
						} else {
							return ['status'=>false];
						}
					} else {
						return ['status'=>false];
					}
				}
			} else {
				$status = get_post_meta($order_code, 'status', true);
				return ['status'=>$status];
			}
        }

		function get_name()
		{
			return __('Mula', 'traveler-mula');
		}

		function get_default_status()
		{
			return $this->default_status;
		}

		function is_available($item_id = FALSE)
		{
			if (st()->get_option('pm_gway_st_mula_enable') == 'off') {
				return FALSE;
			}

			$mula_enable_live = st()->get_option('mula_enable_live');
			$mula_enable_express = st()->get_option('mula_enable_express');

			$mula_secret_key = st()->get_option('mula_secret_key');
			$mula_test_secret_key = st()->get_option('mula_test_secret_key');

			$mula_iv_key = st()->get_option('mula_iv_key');
			$mula_test_iv_key = st()->get_option('mula_test_iv_key');

			$mula_client_secret = st()->get_option('mula_client_secret');
			$mula_test_client_secret = st()->get_option('mula_test_client_secret');

			$mula_client_id = st()->get_option('mula_client_id');
			$mula_test_client_id = st()->get_option('mula_test_client_id');

			if ($mula_enable_live == 'off') {
				if($mula_enable_express == 'off'){
					if (!$mula_test_client_secret || !$mula_test_client_id) return FALSE;
				} else {
					if (!$mula_test_secret_key || !$mula_test_iv_key) return FALSE;
				}
			} else {
				if($mula_enable_express == 'off'){
					if (!$mula_client_secret || !$mula_client_id) return FALSE;
				} else {
					if (!$mula_secret_key || !$mula_iv_key) return FALSE;
				}
			}

			if ($item_id) {
				$meta = get_post_meta($item_id, 'is_meta_payment_gateway_st_mula', TRUE);
				if ($meta == 'off') {
					return FALSE;
				}
			}

			return TRUE;
		}

		function getGatewayId()
		{
			return $this->_gateway_id;
		}

        public function stop_change_order_status(){
            return false;
        }

		function is_check_complete_required()
		{
			return true;
		}

		function get_logo()
		{
			return Traveler_Mula_Payment::get_inst()->pluginUrl . 'assets/img/mula.png';
		}

		function html()
		{
			echo Traveler_Mula_Payment::get_inst()->loadTemplate('mula',
				['msg'=>__("Important: You will be redirected to Mula's website to securely complete your payment.",'traveler-mula')]);
		}

		static function instance() {
			if ( ! self::$_ints ) {
				self::$_ints = new self();
			}

			return self::$_ints;
		}

		static function add_payment( $payment ) {
			$payment['st_mula'] = self::instance();

			return $payment;
		}

	}

	add_filter( 'st_payment_gateways', array( 'ST_Mula_Payment_Gateway', 'add_payment' ) );
}