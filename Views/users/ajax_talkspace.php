<?php

$message=[];

  // ルーティング
  switch ($_POST['action']) {
    case 'get':
      echo get_message($_POST);
      break;
    case 'send':
      echo send_message($_POST);
      break;
    default:
      // code...
      break;
  }

  // DBにメッセージを保存
  function send_message($msg){
    require_once(ROOT_PATH .'Controllers/BordController.php');
    $bord = new BordController();

    $bord->sendmessage($msg);
  }


  // DBからメッセージを取得
  function get_message($data){
    require_once(ROOT_PATH .'Controllers/BordController.php');
    $bord = new BordController();

    $message=$bord->getThisBordmessage($data);

    if (!empty($message)) {
      header('Content-type: application/json; charset=utf-8');
      return json_encode($message);
    }else{
      $message['empty']=0;
      header('Content-type: application/json; charset=utf-8');
      return json_encode($message);
    }
  }

 ?>
