<?php
require_once(ROOT_PATH.'/Models/User.php');
require_once(ROOT_PATH.'/Models/Bord.php');
require_once(ROOT_PATH.'/Models/Message.php');

class BordController{
  private $request; // リクエストパラメータ（GET, POST）
  private $User;
  private $Bord;
  private $Message;

  public function __construct(){
    //リクエストパラメータの取得（コンストラクタ）
    $this->request['post'] = $_POST;
    $this->request['get'] = $_GET;

    // モデルオブジェクトの生成
    $this->Bord = new Bord();
    // 別モデルと連携
    $dbh=$this->Bord->get_Db_handler();
    $this->User = new User($dbh);
    $this->Message = new Message($dbh);
  }

  // XSS対策
  public function xss(){
    function h($s){
      return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
  }

  // CSRF対策
  public function csrf(){
    $token = $this->request['post']['csrf_token'];

    if(isset($token) && $token == $_SESSION['csrf_token']){
      $csrf_token = $token;
      return $csrf_token;
    }else if(isset($_SESSION['User'])){
      header('Location:/users/index.php');
      exit();
    }
    else{
      header('Location:/users/login.php');
      exit();
    }
  }

  // 相談受付中判定
  public function canTalk($id){
      $result = $this->Bord->can_talk($id);
      return $result;
  }

  // 相談受付終了
  public function is_finished(){
    $post = $this->request['post'];
    $this->Bord->finishTalk($post);
    $_SESSION['talkspace_flg']="";
  }

  // 相談者ID登録
  public function userIdcheck(){
    $post = $this->request['post'];
    // 相談者IDが自分の時はスルー
    if (isset($post['to_talkspace']) && $post['talker_id']!=$post['staff_id']) {
      // 最新の相談板のデータを取り出す
      $result = $this->Bord->can_talk($post['staff_id']);
      // 相談者が居ないとき
      if(!isset($result['talker_id'])){
        // 相談者ID登録
        $this->Bord->in_talker($post);
      }
    }
  }

  // 相談板userデータの取得
  public function getUsersData(){
    $post = $this->request['post'];
    $get = $this->request['get'];
    $defaultname="匿名相談者";
    $defaultimg="/img/profile_img/default.png";
    $talkuser=[];

    if (isset($get['b'])) {
      $talkers=$this->Bord->findThisbordById($get['b']);
      // 相談板userデータの取得
      $talkuser['staff']=$this->User->finduserById($talkers['staff_id']);
      $talkuser['talker']=$this->User->finduserById($talkers['talker_id']);
      if ($talkuser['talker']==false) {
        $talkuser['talker']=[
          "nickname"=>'匿名相談者',
          "img_path"=>$defaultimg
        ];
      }
    }
    elseif ($post['talker_id']!=$post['staff_id']) {

    // 相談板userデータの取得
    $talkuser['staff']=$this->User->finduserById($post['staff_id']);
    $talkuser['talker']=$this->User->finduserById($post['talker_id']);
    if ($talkuser['talker']==false) {
      $talkuser['talker']=[
        "nickname"=>'匿名相談者',
        "img_path"=>$defaultimg
      ];
    }
    // 相談板IDの取得
    $talkuser['bordId']=$post['bord_id'];
  }

    return $talkuser;
  }

  // メッセージ登録
  public function sendmessage($data){

    if (isset($data['message'])) {
      $this->Message->insert_message($data);
    }
  }

  // メッセージの取得
  public function getThisBordmessage($data){

    if (isset($data['bord_id'])) {
      if ($data['talkername']=='匿名相談者') {
        $result=$this->Message->n_getMessageById($data);
      }else{
        $result=$this->Message->getMessageById($data);
        $result['test']='test';
      }
      $result['length']=$this->Message->countMessage($data['bord_id']);

      $finish=$this->Bord->finishtime($data['bord_id']);

      if ($finish['is_finished']==NULL) {
        $result['finish']=0;
      }else{
        $result['finish']=$finish;
      }

      return $result;

    }elseif(isset($data['b'])){
      $data['bord_id']=$data['b'];
      $result=$this->Message->getMessageById($data);
      if ($result==false) {
        $result=$this->Message->n_getMessageById($data);
      }
      $result['length']=$this->Message->countMessage($data['b']);
      return $result;
    }


  }

}
 ?>
