<?php

include ('vendor/autoload.php');

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\MessageBuilder\ImageMessageBuilder; 
use \LINE\LINEBot\MessageBuilder\LocationMessageBuilder;  
use \LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;


class BOT_API extends LINEBot {
	
    /* ====================================================================================
     * Variable
     * ==================================================================================== */
	
    private $httpClient     = null;
    private $endpointBase   = null;
    private $channelSecret  = null;
	
    public $content         = null;
    public $events          = null;
	
    public $isEvents        = false;
    public $isText          = false;
    public $isImage         = false;
    public $isSticker       = false;
    public $isfollow        = false; 
    public $unfollow        = false;
    public $join            = false;
    public $leave           = false;
    public $isLocation      = false;
    public $isMap           = false;

    public $text            = null;
    public $replyToken      = null;
    public $source          = null;
    public $message         = null;
    public $timestamp       = null;
    public $userId          = null;
    public $stickerId       = null;
    public $packageId       = null;
	
    public $response        = null;
	
    /* ====================================================================================
     * Custom
     * ==================================================================================== */

	
    public function __construct ($channelSecret, $access_token) {
		
        $this->httpClient     = new CurlHTTPClient($access_token);
        $this->channelSecret  = $channelSecret;
        $this->endpointBase   = LINEBot::DEFAULT_ENDPOINT_BASE;
		
        $this->content        = file_get_contents('php://input');
        $events               = json_decode($this->content, true); 
       // $events               = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $this->content), true); 
        
		
        if (!empty($events['events'])) {
			
            $this->isEvents = true;
            $this->events   = $events['events'];
			
            foreach ($events['events'] as $event) {
				
                $this->replyToken = $event['replyToken'];
                $this->source     = (object) $event['source'];
                $this->message    = (object) $event['message'];
                $this->timestamp  = $event['timestamp'];
				
                if ($event['type'] == 'message' && $event['message']['type'] == 'text' && $event['message']['text'] == "Location") {
                    
                    $this->isLocation = true;

                } else if($event['type'] == 'message' && $event['message']['type'] == 'text' && $event['message']['text'] == "Map"){
                    $this->isMap = true;

                } else if($event['type'] == 'message' && $event['message']['type'] == 'text') {
                     $this->isText = true;
                     $this->text   = ($event['message']['text']);
                     //$this->text = "ว่าไงครับ";
                }

                if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
                    $this->isImage = true;
                }
				
                if ($event['type'] == 'message' && $event['message']['type'] == 'sticker') {
                    $this->isSticker = true;
                    $this->stickerId   = $event['message']['stickerId'];
                    $this->packageId   = $event['message']['packageId'];

                    //$ans = $this->replySticker($event['replyToken'],$event['message']['packageId'],$event['message']['stickerId']);

                }

                //  if ($event['type'] == 'follow') {
                //        $this->isfollow = true;
                //        $this->text   = 'Hi Follow';
                // }

                // if ($event['type'] == 'unfollow') {
                //        $this->unfollow = true;
                //        $this->text   = 'Unfollow';
                // }

                // if ($event['type'] == 'join') {
                //        $this->join = true;
                //        $this->text   = 'join';
                // }

                // if ($event['type'] == 'leave') {
                //        $this->leave = true;
                //        $this->text   = 'leave';
                // }

				
            }

        }
		
        parent::__construct($this->httpClient, [ 'channelSecret' => $channelSecret ]);
		
    }

	
    public function sendMessageNew ($to = null, $message = null) {
        $messageBuilder = new TextMessageBuilder($message);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/push', [
            'to' => $to,
            // 'toChannel' => 'Channel ID,
            'messages'  => $messageBuilder->buildMessage()
        ]);
    }
	
    public function replyMessageNew ($replyToken = null, $message = null , $source = null) {
        $messageBuilder = new TextMessageBuilder($message,$source);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $messageBuilder->buildMessage(),
            
        ]);
    }

    public function replySticker ($replyToken = null, $packageId = null, $stickerId = null ) {
        $messageBuilder = new StickerMessageBuilder ($packageId,$stickerId);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $messageBuilder->buildMessage(),
            
        ]);
    }

    public function replyImg ($replyToken = null, $originalContentUrl = null, $previewImageUrl = null ) {
        $messageBuilder = new ImageMessageBuilder ($originalContentUrl,$previewImageUrl);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $messageBuilder->buildMessage(),
            
        ]);
    }


    public function replyLocation ($replyToken = null, $title = null, $address = null , $latitude = null, $longitude = null) {
        $messageBuilder = new LocationMessageBuilder ($title,$address,$latitude,$longitude);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $messageBuilder->buildMessage(),
            
        ]);
    }

     public function replyMap ($replyToken = null, $baseUrl = null, $altText = null , array $baseSizeBuilder = null, array $imagemapActionBuilders = null) {
        $messageBuilder = new ImagemapMessageBuilder ($baseUrl,$altText,$baseSizeBuilder,$imagemapActionBuilders);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $messageBuilder->buildMessage(),
            
        ]);
    }

	
    public function isSuccess () {
        return !empty($this->response->isSucceeded()) ? true : false;
    }
	
    public static function verify ($access_token) {
		
        $ch = curl_init('https://api.line.me/v1/oauth/verify');
		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Authorization: Bearer ' . $access_token ]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
		
    }

    
	
}
