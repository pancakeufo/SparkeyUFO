<?php
if (isset($update['update']['message']['out']) and $update['update']['message']['out'] == true) return 0;
if (!isset($msg)) return 0;

if ($msg == '/stato' and $userID == 279109924) {
  sm($chatID, "<b>Status: Online ✅</b>", 1);
}

if(strpos($msg,"/cercamessaggi")===0 and $userID == 279109924){
  $e = explode(" ",$msg,2);
  
  if($msg == "/cercamessaggi") die();

  $max_id = $update['update']['message']['id'];


  while (true) {
        $log = $MadelineProto->messages->search(['peer' => $chatID, 'q' => $e[1],  'min_date' => 1, 'max_date' => $update['update']['message']['date'], 'offset_id' => 0, 'add_offset' => 0, 'limit' => 200, 'max_id' => $max_id, 'min_id' => 1, ]);
        $arrayMSG = array();

        foreach($log['messages'] as $messaggioID){
            array_push($arrayMSG, $messaggioID['id']);
        }

        if (empty($arrayMSG)) {
            break;
        }

        $max_id = min($arrayMSG);
        $MadelineProto->channels->deleteMessages(['channel' => $chatID,'revoke' => true, 'id' => $arrayMSG, ]);
    }
    
    sm($chatID,'Messaggi eliminati con corrispondenza: "'.$e[1].'" ✅');
}

//del all message of an user: $MadelineProto->channels->deleteUserHistory(['channel' => $chatID, 'user_id' => $user, ]);

if(strpos($msg,"/eliminatempo")===0 and $userID == 279109924){
  if($msg == "/eliminatempo") die();
  $e = explode(" ",$msg,3);
  $max_id = $update['update']['message']['id'];
  while (true) {
        $log = $MadelineProto->messages->search(['peer' => $chatID, 'q' => $e[2],  'min_date' => strtotime("-".$e[1]." minutes"), 'max_date' => $update['update']['message']['date'], 'offset_id' => 0, 'add_offset' => 0, 'limit' => 200, 'max_id' => $max_id, 'min_id' => 1, ]);
        $arrayMSG = array();

        foreach($log['messages'] as $messaggioID){
            array_push($arrayMSG, $messaggioID['id']);
        }

        if (empty($arrayMSG)) {
            break;
        }
        $max_id = min($arrayMSG);
        $MadelineProto->channels->deleteMessages(['channel' => $chatID,'revoke' => true, 'id' => $arrayMSG, ]);
    }
    sm($chatID,'Messaggi eliminati per i passati: '.$e[1].' minuti con corrispondenza: "'.$e[2].'" ✅');
}



if(strpos($msg,"/limita")===0  and $userID == 279109924){
	$e = explode(" ",$msg,4);
	$mode = $e[1];
	$timing = $e[2];
	$usernametoban = $e[3];
	if($msg == "/limita" or count($e) < 4) die();
	if(strlen($usernametoban) <= 4 or substr($usernametoban, 0,1) != "@") die();
	switch ($mode) {
		case '1':
			$channelBannedRights = ['_' => 'channelBannedRights', 'send_media' => true, 'send_media' => true, 'send_stickers' => true,'send_gifs' => true, 'send_games' => true,'send_inline' => true,'embed_links' => true, 'until_date' => strtotime("+".$e[2]." minutes"),];
			$MadelineProto->channels->editBanned(['channel' => $chatID, 'user_id' => $usernametoban, 'banned_rights' => $channelBannedRights]);
			sm($chatID,"Attenzione\nUtente: ".$usernametoban." \nSei stato limitato per ".$e[2]." minuto/i e non potrai inviare media.");
			break;
		case '2':
			$channelBannedRights = ['_' => 'channelBannedRights', 'send_media' => true, 'send_stickers' => true,'send_gifs' => true, 'send_games' => true,'send_inline' => true,'embed_links' => true, 'until_date' => strtotime("+".$e[2]." hours"),];
			$MadelineProto->channels->editBanned(['channel' => $chatID, 'user_id' => $usernametoban, 'banned_rights' => $channelBannedRights]);
			sm($chatID,"Attenzione\nUtente: ".$usernametoban." \nSei stato limitato per ".$e[2]." ora/e e non potrai inviare media.");
			break;
		case '3':
			$channelBannedRights = ['_' => 'channelBannedRights', 'send_media' => true, 'send_media' => true, 'send_stickers' => true,'send_gifs' => true, 'send_games' => true,'send_inline' => true,'embed_links' => true, 'until_date' => strtotime("+".$e[2]." day"),];
			$MadelineProto->channels->editBanned(['channel' => $chatID, 'user_id' => $usernametoban, 'banned_rights' => $channelBannedRights]);
			sm($chatID,"Attenzione\nUtente: ".$usernametoban."\nSei stato limitato per ".$e[2]." giorno/i e non potrai inviare media.");
			break;
	}
}