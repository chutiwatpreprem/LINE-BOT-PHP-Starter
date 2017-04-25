<?php

include ('vendor/autoload.php');

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

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
	
    public $text            = null;
    public $replyToken      = null;
    public $source          = null;
    public $message         = null;
    public $timestamp       = null;
    public $userId          = null;
	
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
				
                if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
                    $this->isText = true;
                    //$this->text   = $this->u_decode($event['message']['text']);
                    $this->text = "ว่าไงครับ";
                
                }
				
                if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
                    $this->isImage = true;
                }
				
                if ($event['type'] == 'message' && $event['message']['type'] == 'sticker') {
                    $this->isSticker = true;
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

     public function mat ($matches) {
         return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
    }
     public function u_decode($input){
        return preg_replace_callback( '/\\\\u([0-9a-zA-Z]{4})/', mat , $input );
    }
     public function raw_json_encode($input) {
    // convert 2 utf8 json encode 
        return u_decode( json_encode($input) );
    }
    //echo u_decode('\u0e27\u0e48\u0e32\u0e44\u0e07');
	
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
