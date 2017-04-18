<?php
$proxy = 'http://fixie:L1EEC8Uy4gr9bl3@velodrome.usefixie.com:80';
$proxyauth = '';

$access_token = 'jZ2DUVXRbdH/oAPpwkIZyO0l7cCMXERHlRmGMZ569kel5lXe+Xhexg7LG+d8/xfSAgw2O0tyWiMLsemL61pZB14Pc9HzQoxOO6XIYS6vqYwxITLl1EcMP5BLr8Y0etsBPjT4wmM+iYl0rAJSc3zDuAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');

// Parse JSON
$events = json_decode($content, true);


// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {

		// Get replyToken
			$replyToken = $event['replyToken'];


		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			

			if ($event['message']['text'] == "Do you love me") {
				$text  = "I Love Kukkik Verymuch ^^. Do You Love Me?";

			} 	else if($event['message']['text'] == "Yes"){
				$text  = "Yed Mai?";
			} 
				else if($event['message']['text'] == "Where"){
				$text  = "Bathroom mai? Hee Hee";
			} 
			else {
				$text = $event['message']['text'];
			}

		

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);

			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}


		if ($event['type'] == 'message' && $event['message']['type'] == 'sticker') {


			// Build message to reply back
			$messages = [
				'type' => 'sticker',
				'packageId' => "1",
				'stickerId' => "2"
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);

			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";

		}



		if ($event['type'] == 'message' && $event['message']['type'] == 'image') {

			$pic = "https://github.com/chutiwatpreprem/LINE-BOT-PHP-Starter/blob/master/no_pic_user.jpg";
			$pic2 = "https://github.com/chutiwatpreprem/LINE-BOT-PHP-Starter/blob/master/no_pic_user2.JPG";
			// Build message to reply back
			$messages = [
				'type' => 'image',
				'originalContentUrl' => $pic,
				'previewImageUrl' => $pic2
			];


			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);

			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";

		}


	}
}
echo "Success";