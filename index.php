<?php
echo '<!DOCTYPE html><html>';
if (!isset($_GET['from'])){
	$from_dest = $_GET["from"];
	echo '<form action=\'/\' method=\'get\'><input name=\'from\' type=\'hidden\' value=\'batumi\'><input type=\'submit\' value=\'Batumi-Tbilisi\'/></form>';
	echo '<form action=\'/\' method=\'get\'><input name=\'from\' type=\'hidden\' value=\'tbilisi\'><input type=\'submit\' value=\'Tbilisi-Batumi\'/></form>';
	echo '<html>';
	return;
}

require_once __DIR__ .'/vendor/autoload.php';
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;
$client = new Client();
$dt = new DateTime(date("Y-m-d"));
$dt->modify('-1 day');
$batumi = array('name' => 'ბათუმი', 'number' => '57151');
$tbilisi = array('name' => 'თბილისი-სამგზ', 'number' => '56014');


$from = "";
if ($from_dest == "batumi"){
	$from = $batumi;

} else {
	$from = $tbilisi;
}
//$date -> string d/m/Y 
function getResponse($from, $to, $date, $client){
	$res = $client->request('POST', 'https://biletebi.ge/startup.aspx?ajax=1&action=searchfortrain', [
		'form_params' => [
			'date' => $date,
			'from' => $from['number'],
			'fromStation' => $from['name'],
			'oneComp' => 'false',
			'to' => $to['number'],
			'toStation' => $to['name'],
		]
	]);

	return $res->getBody();
}

for($i = 0; $i < 7; $i++){
	$body = getResponse($tbilisi, $batumi, $dt->modify('+1 day')->format('d/m/Y'), $client);
	preg_match('/<b>[0-9]{2}:[0-9]{2}<\/b>.+/', $body , $matches, PREG_OFFSET_CAPTURE);
	$html = HtmlDomParser::str_get_html($body);

	for($a = 0; $a < 15; $a++){
		$div = $html->find('div[class=trainDates]', $a);
		if($div === NULL){
			break;
		}
		//var_dump($divs);
		echo $div.'<br>';
	}
}
echo '<html>';

?>
