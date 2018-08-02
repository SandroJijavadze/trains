<?php

require_once __DIR__ .'/vendor/autoload.php';
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;
$client = new Client();

$res = $client->request('POST', 'https://biletebi.ge/startup.aspx?ajax=1&action=searchfortrain', [
	'form_params' => [
		'date' => '03/08/2018',
		'from' => '56014',
		'fromStation' => 'თბილისი-სამგზ',
     		'oneComp' => 'false',
		'to' => '57151',
		'toStation' => 'ბათუმი',
	]
]);
$body = $res->getBody();
preg_match('/<b>[0-9]{2}:[0-9]{2}<\/b>.+/', $body , $matches, PREG_OFFSET_CAPTURE);
//foreach($matches[0] as $match){
//	echo $match;		
//}
//var_dump($matches);
//echo $body;
$html = HtmlDomParser::str_get_html($body);
for($i = 0; $i < 15; $i++){
	$div = $html->find('div[class=trainDates]', $i);

	//var_dump($divs);
	echo $div.'<br>';
}
$dt = new DateTime(date("Y-m-d"));
echo $dt->modify('+1 day')->format('d/m/Y');
//echo $date("d/m/Y")->modify('+1 day');
