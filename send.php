<?php
/**
 *
 *
 * USERNAME , .
 * PASSWORD , .
 * GATEWAY_URL .
 * RETURN_URL ,
 * .
 */
define('USERNAME', 'USERNAME');
define('PASSWORD', 'PASSWORD');
define('GATEWAY_URL', 'https://server/payment/rest/');
define('RETURN_URL', 'http://your.site/rest.php');
/**
 *
 *
 * POST
 * cURL.
 *
 *
 * method API.
 * data .
 *
 *
 * response .
 */
function gateway($method, $data) {
 $curl = curl_init(); //
 curl_setopt_array($curl, array(
 CURLOPT_URL => GATEWAY_URL.$method, //
 CURLOPT_RETURNTRANSFER => true, //
 CURLOPT_POST => true, // POST
 CURLOPT_POSTFIELDS => http_build_query($data) //
 ));
 $response = curl_exec($curl); //

 $response = json_decode($response, true); // JSON
 curl_close($curl); //
 return $response; //
}
/**
 *
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['orderId'])) {
 echo '
 <form method="post" action="/rest.php">
 <label>Order number</label><br />
 <input type="text" name="orderNumber" /><br />
 <label>Amount</label><br />
 <input type="text" name="amount" /><br />
 <button type="submit">Submit</button>
 </form>
 ';
}
/**
 *
 */
else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 $data = array(
 'userName' => USERNAME,
 'password' => PASSWORD,
 'orderNumber' => urlencode($_POST['orderNumber']),
 'amount' => urlencode($_POST['amount']),
 'returnUrl' => RETURN_URL
 );
 /**
 *
 * register.do
 *
 *
 * userName .
 * password .
 * orderNumber .
 * amount .
 * returnUrl , .
 *
 *
 * :
 * errorCode . .
 * errorMessage .
 *
 * :
 * orderId . .
 * formUrl URL , .
 *
 *
 * 0 .
 * 1 .
 * 3 () .
 * 4 .
 * 5 .
 * 7 .
 */
 $response = gateway('register.do', $data);

 /**
 *
 * registerPreAuth.do
 *
 * , .
 * register.do, registerPreAuth.do.
 */
// $response = gateway('registerPreAuth.do', $data);

 if (isset($response['errorCode'])) { //
 echo ' #' . $response['errorCode'] . ': ' . $response['errorMessage'];
 } else { //
 header('Location: ' . $response['formUrl']);
 die();
 }
}
/**
 *
 */
else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['orderId'])){
 $data = array(
 'userName' => USERNAME,
 'password' => PASSWORD,
 'orderId' => $_GET['orderId']
 );

 /**
 *
 * getOrderStatusExtended.do
 *
 *
 * userName .
 * password .
 * orderId . .
 *
 *
 * ErrorCode . .
 * OrderStatus .
 * . , .
 *
 *
 * 0 .
 * 2 .
 * 5 ;
 * ;
 * .
 * 6 .
 * 7 .
 *
 *
 * 0 , .
 * 1 ( ).
 * 2 .
 * 3 .
 * 4 .
 * 5 ACS -.
 * 6 .
 */
 $response = gateway('getOrderStatusExtended.do', $data);

 //
 echo '
 <b>Error code:</b> ' . $response['ErrorCode'] . '<br />
 <b>Order status:</b> ' . $response['OrderStatus'] . '<br />
 ';
}
?>
8. Спец
