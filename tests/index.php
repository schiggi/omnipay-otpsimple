<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 08/03/2016
 * Time: 19:17
 */
header("Content-Type: text/html; charset=utf-8");
require '..\vendor\autoload.php';

use Omnipay\Omnipay;
use Omnipay\OTPSimple;

// Setup payment gateway
$gateway = Omnipay::create('OTPSimple');
$gateway->setTestMode(true);
$gateway->setMerchantId('P137702');
$gateway->setSecretKey('U0!K6[(L9X^S[[J7@I2f');

//$settings = $gateway->getDefaultParameters();
//echo '<pre>',print_r($settings,1),'</pre>';

$orderData = [
    'firstName' => 'PayU',
    'lastName' => 'Tester',
    'billingAddress1' => 'First line address',
    'billingAddress2' => '',
    'billingCity' => 'City',
    'billingPostcode' => '1234',
    'billingState' => 'State',
    'billingCountry' => 'HU',
    'billingPhone' => '36201234567',
    'shippingAddress1' => 'First line address',
    'shippingAddress2' => 'Second line address',
    'shippingCity' => 'City',
    'shippingPostcode' => '1234',
    'shippingState' => 'State',
    'shippingCountry' => 'HU',
    'shippingPhone' => '36201234567',
    'company' => 'Billing Company',
    'email' => 'payu.tester@example.com'
];

$transactionID = 1234;
$params = array(
    'description'   => 'Butoraim Order' . ' ' . $transactionID,
    'transactionId' => $transactionID,
    'amount'        => 123.00,
    'currency'      => 'HUF',
    'card'          => $orderData,
    'returnUrl' => 'https://butoraim.hu/index.php?route=payment/payu/callbackOK',
    'timeoutUrl' => 'https://butoraim.hu/index.php?route=payment/payu/callbackTimeOut&amp;order=1825&amp;currency=HUF'
);


$request = $gateway->purchase($params);
// Create a basket of items.
//$basket = new \Omnipay\Common\ItemBag();
//$basket->add(array(
//    'name' => 'Butoraim Order' . ' ' . $params['transactionId'],
//    'description' => 'More Information',
//    'quantity' => 1,
//    'price' => '5500'
//));

//$request->setItems($basket);


echo '<pre>',print_r($request->getData(),1),'</pre>';

// Send purchase request
//$response = $request->send();
//
//if ($response->isSuccessful()) {
//    // payment is complete
//} elseif ($response->isRedirect()) {
//    $response->redirect(); // this will automatically forward the customer
//} else {
//    // not successful
//}

//echo '<pre>',print_r($response->getMessage(),1),'</pre>';
?>