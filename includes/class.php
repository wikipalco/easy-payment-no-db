<?php
session_start();

class WPAL_Easy_Pay
{
    public $protocol = 'http://';
	public $fields = array();

    public function steps()
    {

        $fields = array_filter($this->fields);

        if (isset($_POST['step-2'])) {

            $validate = $this->validate($fields);

            $error = $validate['error'];
            $alert = $validate['alert'];

            if (!empty($error) || !empty($alert))
                include 'includes/step-1.php';
            else
                include 'includes/step-2.php';


        } else if (isset($_POST['step-3'])) {

            $validate = $this->validate($fields);

            if (!empty($validate['error']) || !empty($validate['alert']))
                $alert['error'] = 'مشکلی در اطلاعات ارسالی رخ داده است.';

            include 'includes/step-3.php';

        } else if ($this->post('step-4', '') == 'true' || $this->post('Status', '') != '' || $this->post('InvoiceNumber', '') != '') {
            include 'step-4.php';
        } else {
            include 'step-1.php';
        }
    }

    private function validate($fields)
    {
        $error = array();
        $alert = array();

        foreach ($_POST as $key => $value) {

            if (array_key_exists($key, $fields) && $this->has_star($fields[$key])) {

                if (empty($value)) {
                    $alert[] = $error[$key]['message'] = sprintf('پر کردن فیلد %s ضروریست!', $this->remove_star($fields[$key]));
                }
            }

            if (!empty($value)) {

                if ($key == 'email') {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL) === false)
                        $alert[] = $error[$key]['message'] = 'ایمیل وارد شده معتبر نیست!';

                } else if ($key == 'phone') {

                    $value = str_replace(array('+98', '98', '0098'), array('0', '0', ''), $value);

                    if (!preg_match("/^[0-9]{11}$/", $value))
                        $alert[] = $error[$key]['message'] = 'شماره تلفن وارد شده معتبر نیست!';
                }
            }
        }

        return array('error' => $error, 'alert' => $alert);
    }

    public function payment()
    {
		$_SESSION["payprice"] 	= intval($this->param('price'));
		
		$MerchantID 			= $this->param('merchant');
		$Price 					= intval($this->param('price'));
		$Description 			= $this->param('description');
		$InvoiceNumber 			= time();
		$CallbackURL 			= $this->return_url();
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://gatepay.co/webservice/paymentRequest.php');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, "MerchantID=$MerchantID&Price=$Price&Description=$Description&InvoiceNumber=$InvoiceNumber&CallbackURL=". urlencode($CallbackURL));
		curl_setopt($curl, CURLOPT_TIMEOUT, 400);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = json_decode(curl_exec($curl));
		curl_close($curl);
		if ($result->Status == 100){
			return $this->payment = array('Payment_URL' => 'http://gatepay.co/webservice/startPayment.php?au='. $result->Authority, 'Message' => '');
		} else {
			return $this->payment = array('Payment_URL' => '', 'Message' => $this->Fault($result->Status));
		}
    }

    public function verify()
    {
		global $_SESSION;

		$MerchantID 			= $this->param('merchant');
		$Price 					= $_SESSION["payprice"];
		$Authority 				= $_POST['authority'];
		$InvoiceNumber 			= $_POST['InvoiceNumber'];

		if ($_POST['status'] == 1) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://gatepay.co/webservice/paymentVerify.php');
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
			curl_setopt($curl, CURLOPT_POSTFIELDS, "MerchantID=$MerchantID&Price=$Price&Authority=$Authority");
			curl_setopt($curl, CURLOPT_TIMEOUT, 400);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$result = json_decode(curl_exec($curl));
			curl_close($curl);
			if ($result->Status == 100) {
				$Status 	= 'completed';
				$Token 		= $result->RefCode;
				$Fault 		= $result->Status;
				$Message 	= "تراکنش با موفقیت انجام شد";
			} else {
				$Status 	= 'failed';
				
				switch ($result->Status) {
					case '-1' :
						$Message =	'پارامترهای ارسال ناقص میباشد';
						break;
					case '-2' :
						$Message =	'مرچنت کد ارسال شده صحیح نمیباشد';
						break;
					case '-3' :
						$Message =	'مبلغ وارد شده با مقدار ثبت و پرداخت شده مغایرت دارد';
						break;
					case '-4' :
						$Message =	'تراکنش موفق بوده است, اما این تراکنش قبلاً Verify شده است, هر تراکنش را فقط یک بار میتوان Verify کرد';
						break;
					case '-5' :
						$Message =	'خطا در Verify کردن تراکنش';
						break;
					case '-6' :
						$Message =	'خطای سیستمی, این موضوع را به بخش پشتیبانی ویکی پال اطلاع دهید';
						break;
					default :
						$Message =	'خطای نا مشخص';
						break;
				}
			}
		} else {
			$Status 		= 'failed';
			$Message 		= 'تراکنش لغو شد';
		}
		
        return $this->verify = array(
            'Status' 		=> $Status,
            'InvoiceNumber' => $InvoiceNumber,
            'Token' 		=> $Token,
            'Fault' 		=> $Fault,
            'Message' 		=> $Message,
        );
    }

    public function strip($string)
    {
        $string = trim($string);
        $string = strip_tags($string, '<a><img><br>');
        return $string;
    }

    public function post($name, $return = '...')
    {
        if (!empty($_POST[$name])) {
            return $this->strip($_POST[$name]);
        } else {
            return $return;
        }
    }

    public function remove_star($name)
    {
        return str_replace('*', '', $this->strip($name));
    }

    public function has_star($name)
    {
        return stripos($name, '*') !== false;
    }

    public function param($name)
    {
        $name = str_replace('-', '_', $name);
        if (!empty($this->{$name}))
            return $this->strip($this->{$name});
        else
            return '';
    }

    public function return_url()
    {

        $pageURL = 'http://';
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $pageURL = 'https://';
        }

        if ($_SERVER['SERVER_PORT'] != '80') {
            $pageURL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        } else {
            $pageURL .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }

        return $pageURL;
    }

    private function Fault($Fault)
    {

        switch ($Fault) {

			case '-1' :
				$Response =	'پارامترهای ارسال ناقص میباشد';
				break;
		
			case '-2' :
				$Response =	'مرچنت کد ارسال شده صحیح نمیباشد';
				break;
			case '-3' :
				$Response =	'مرچنت کد ( درگاه مورد نظر ) غیر فعال میباشد';
				break;
		
			case '-4' :
				$Response =	'مقدار پارامتر Price باید یک عدد صحیح برابر یا بزرگتر از 100 باشد ( حداقل مبلغ قابل پرداخت 100 تومان میباشد )';
				break;
		
			case '-5' :
				$Response =	'مقدار InvoiceNumber باید یک عدد صحیح بزرگتر از 0 باشد';
				break;

			case '-6' :
				$Response =	'خطای سیستمی در ایجاد Authority, این موضوع را به بخش پشتیبانی ویکی پال اطلاع دهید';
				break;
		
			case '-7' :
				$Response =	'خطا در دریافت Authority, این موضوع را به پشتیبانی ویکی پال اطلاع دهید';
				break;
			
			case '-8' :
				$Response =	'خطای سیستمی, این موضوع را به پشتیبانی ویکی پال اطلاع دهید';
				break;
				
			default :
				$Response =	'خطای نا مشخص';
				break;

        }
        return $Response;
    }
}

global $wpal;
$wpal = new WPAL_Easy_Pay();
?>