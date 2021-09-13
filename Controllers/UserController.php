<?php
require_once(ROOT_PATH.'/Models/User.php');
require_once(ROOT_PATH.'/Models/Bord.php');
require_once(ROOT_PATH.'/Models/Resetpaswd.php');
require_once(ROOT_PATH.'/Models/Favoriteuser.php');

class UserController{
  private $request; // リクエストパラメータ（GET, POST）
  private $User;
  private $Bord;
  private $Resetpaswd;
  private $Favorite;

  public function __construct(){
    //リクエストパラメータの取得（コンストラクタ）
    $this->request['post'] = $_POST;
    $this->request['get'] = $_GET;

    // モデルオブジェクトの生成
    $this->User = new User();
    // 別モデルと連携
    $dbh=$this->User->get_Db_handler();
    $this->Bord = new Bord($dbh);
    $this->Resetpaswd = new Resetpaswd($dbh);
    $this->Favorite = new Favoriteuser($dbh);
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
  }
  elseif (!isset($_SESSION['User'])) {
    header('Location:login.php');
    exit();
  }
  else{
    header('Location:login.php');
    exit();
  }
}

// ログイン
public function login(){
  $post = $this->request['post'];
  $_SESSION=[];
  $loginmessage=[];

  // post操作されていないかどうか判定
  if(!empty($post)){
    // postデータのチェック
    if (empty($post['login_mail'])) {
      $loginmessage['mail']="(!) メールアドレスは入力必須です。";
    }else{
      // メールアドレスと一致したユーザデータを引っ張ってくる
      $result = $this->User->login($post);
      if (!empty($result)) {
        if (isset($post['to_home'])) {
          // 新規登録した場合、パスワードを比較
            if($post['login_password']==$result['password']){
              $_SESSION['User'] = $result;
            }else{
              header('Location:/users/login.php');
              exit();
            }

        }else if (empty($post['login_password'])) {
          $loginmessage['pass']="(!) パスワードは入力必須です。";
        }else if (!password_verify($post['login_password'],$result['password'])) {
          $loginmessage['pass']="(!) パスワードが違っています。";
        }else{
          // セッションにユーザー情報を保存
            $_SESSION['User'] = $result;
            // role_idの区分によってホームを変える（リダイレクト）
            if($result['role_id']==3){
              header('Location:/admins/index.php');
              exit();
            }else{
              header('Location:/users/index.php');
              exit();
            }

        }

      }else{
        $loginmessage['mail']="(!) 登録したメールアドレスを入力してください。";
      }
    }

  }else{
    // ログインページの場合、セッションを消去
    // ホームの場合、初回時のみ動く。
    $_SESSION=[];
  }

  return $loginmessage;
}

// パスワード変更
public function resetpaswd(){
  $post = $this->request['post'];
  $errormessage=[];

  if (empty($post['mail'])) {
    $errormessage['message']['error']="(!) メールアドレスの入力は必須です。";
  }else{
    // メールアドレスが一致しているかどうか判定
    $rows = $this->User->mailfind($post);

    if($rows==true){
      $resetpaswd_token=openssl_random_pseudo_bytes(16);
      $rows['resetpaswd_token']=bin2hex($resetpaswd_token);
      $this->Resetpaswd->insert_token($rows);
    }else{
      $errormessage['message']['error']='(!) 障害が発生し、メールアドレスが確認できませんでした';
    }
  }

  return $errormessage;
}

public function findResetpaswd_token(){
  $post = $this->request['post'];
  $result=$this->Resetpaswd->findtoken($post);
  return $result;
}

public function changepw(){
  $get = $this->request['get'];
  $post = $this->request['post'];
  $post_iforgot_token=isset($post["iforgot_token"]) ? $post["iforgot_token"] : "";
  $session_iforgot_token=isset($_SESSION["iforgot_token"]) ? $_SESSION["iforgot_token"] : "";
  unset($_SESSION['iforgot_token']);
  $error=[];

  $result=$this->Resetpaswd->findtoken($get);

  if ($result==true) {

    if (isset($post['changepw'])) {
      // パスワード変更
      if (empty($post['pass'])) {
        $error['message']="(!) パスワードの入力は必須です。";
      }else if (!preg_match("/^[a-zA-Z0-9_-]+$/", $post['pass'])) {
        $error["message"]="(!) パスワードは半角英数字のみで入力してください。";
      }elseif (mb_strlen($post['pass'])< 8) {
        $error['message']='(!) パスワードは8文字以上で入力してください。';
      }elseif ($post_iforgot_token != "" && $post_iforgot_token == $session_iforgot_token) {
        $postpasswd=password_hash($post['pass'],PASSWORD_DEFAULT);
        $result['password']=$postpasswd;
        $this->User->changepw($result);
        $error['complete']="パスワードの変更が完了しました。";
      }
    }

  } else{
    header("Location:/users/login.php");
    exit();
  }

  return $error;

}

public function setData(){
  $post = $this->request['post'];

  // 入力された値があればそれを代入、なければ初期化
  if(isset($post['adduser']) || isset($post['up_prof'])){
    $role_id=isset($post['role_id']) ? trim($post['role_id']) : NULL;
    $nickname=isset($post['nickname']) ? trim($post['nickname']) : NULL;
    $gender=isset($post['gender']) ? trim($post['gender']) : NULL;
    $gender_open=isset($post['gender_open']) ? trim($post['gender_open']) : NULL;
    $birth_place=isset($post['birth_place']) ? trim($post['birth_place']) : NULL;
    $birth_place_open=isset($post['birth_place_open']) ? trim($post['birth_place_open']) : NULL;
    $birth_place2=isset($post['birth_place2']) ? trim($post['birth_place2']) : NULL;
    $living_place=isset($post['living_place']) ? trim($post['living_place']) : NULL;
    $living_place_open=isset($post['living_place_open']) ? trim($post['living_place_open']) : NULL;
    $living_place2=isset($post['living_place2']) ? trim($post['living_place2']) : NULL;
    $mail=isset($post['mail']) ? trim($post['mail']) : NULL;
    $free_time1=isset($post['free_time1']) ? trim($post['free_time1']) : NULL;
    $free_time2=isset($post['free_time2']) ? trim($post['free_time2']) : NULL;
    $password=isset($post['password']) ? trim($post['password']) : NULL;
    $comment=isset($post['comment']) ? trim($post['comment']) : NULL;
    $img_path=isset($post['img_path']) ? $post['img_path'] : NULL;

    // 入力された値をセッションに保存
    $_SESSION['role_id']=$role_id;
    $_SESSION['nickname']=$nickname;
    $_SESSION['gender']=$gender;
    $_SESSION['gender_open']=$gender_open;
    $_SESSION['birth_place']=$birth_place;
    $_SESSION['birth_place2']=$birth_place2;
    $_SESSION['birth_place_open']=$birth_place_open;
    $_SESSION['living_place']=$living_place;
    $_SESSION['living_place2']=$living_place2;
    $_SESSION['living_place_open']=$living_place_open;
    $_SESSION['mail']=$mail;
    $_SESSION['free_time1']=$free_time1;
    $_SESSION['free_time2']=$free_time2;
    $_SESSION['password']=$password;
    $_SESSION['comment']=$comment;
    $_SESSION['img_path']=$img_path;
  }

  // 戻るボタンで戻ってきたとき
  // 先に入力していた値があればそれを出す、なければ初期化
  if(isset($post['back']) || isset($post['up_back'])){
    $setData["role_id"]=isset($_SESSION['role_id'])?$_SESSION['role_id']:null;
    $setData["nickname"]=isset($_SESSION['nickname'])?$_SESSION['nickname']:null;
    $setData["gender"]=isset($_SESSION['gender'])?$_SESSION['gender']:null;
    $setData["gender_open"]=isset($_SESSION['gender_open'])?$_SESSION['role_id']:null;
    $setData["birth_place"]=isset($_SESSION['birth_place'])?$_SESSION['birth_place']:null;
    $setData["birth_place2"]=isset($_SESSION['birth_place2'])?$_SESSION['birth_place2']:null;
    $setData["birth_place_open"]=isset($_SESSION['birth_place_open'])?$_SESSION['birth_place_open']:null;
    $setData["living_place"]=isset($_SESSION['living_place'])?$_SESSION['living_place']:null;
    $setData["living_place2"]=isset($_SESSION['living_place2'])?$_SESSION['living_place2']:null;
    $setData["living_place_open"]=isset($_SESSION['living_place_open'])?$_SESSION['living_place_open']:null;
    $setData["mail"]=isset($_SESSION['mail'])?$_SESSION['mail']:null;
    $setData["free_time1"]=isset($_SESSION['free_time1'])?$_SESSION['free_time1']:null;
    $setData["free_time2"]=isset($_SESSION['free_time2'])?$_SESSION['free_time2']:null;
    $setData["password"]=isset($_SESSION['password'])?$_SESSION['password']:null;
    $setData["comment"]=isset($_SESSION['comment'])?$_SESSION['comment']:null;
    $setData["img_path"]=isset($_SESSION['img_path'])?$_SESSION['img_path']:null;
  }else {
    $setData["role_id"]=null;
    $setData["nickname"]=null;
    $setData["gender"]=null;
    $setData["gender_open"]=null;
    $setData["birth_place"]=null;
    $setData["birth_place2"]=null;
    $setData["birth_place_open"]=null;
    $setData["living_place"]=null;
    $setData["living_place2"]=null;
    $setData["living_place_open"]=null;
    $setData["mail"]=null;
    $setData["free_time1"]=null;
    $setData["free_time2"]=null;
    $setData["password"]=null;
    $setData["comment"]=null;
    $setData["img_path"]=null;
  }

  return $setData;

}

public function output(){
  $output=[];
  $post = $this->request['post'];

  // 区分
  if(isset($post['role_id'])){
    if($post['role_id']==1){
      $output['role_id']='相談者';
    }
    if($post['role_id']==2){
      $output['role_id']='相談スタッフ';
    }
    if($post['role_id']==3){
      $output['role_id']='管理者';
    }
  }

  // 性別
  if(isset($post['gender'])){
    if($post['gender']==1){
      $output['gender']='女性';
    }
    if($post['gender']==2){
      $output['gender']='男性';
    }
    if($post['gender']==3){
      $output['gender']='その他';
    }
  }

  // 都道府県プルダウン
  $pref=[
    "北海道"=>['北海道'],
    "東北"=>['青森県','岩手県','宮城県','秋田県','山形県','福島県'],
    "関東"=>['茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県'],
    "甲信越"=>['山梨県','長野県','新潟県'],
    "北陸"=>['富山県','石川県','福井県'],
    "東海"=>['岐阜県','静岡県','愛知県','三重県'],
    "近畿"=>['滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県'],
    "中国"=>['鳥取県','島根県','岡山県','広島県','山口県'],
    "四国"=>['徳島県','香川県','愛媛県','高知県'],
    "九州"=>['福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県'],
    "沖縄"=>['沖縄県'],
    "Foreign"=>["その他"]
  ];

    $output['pref'] = $pref;

  // 画像
  if(isset($post['img_path'])){
    $output['img_path'] = $post['img_path'];
  } elseif (!isset($post['img_path']) && isset($post['role_id']) && $post['role_id']==2) {
    $output['img_path'] = '/img/profile_img/samplestaff01.png';
  } elseif (!isset($post['img_path']) && isset($post['role_id']) && $post['role_id']==1) {
    $output['img_path'] = '/img/profile_img/sampletalker01.png';
  } elseif (!isset($post['img_path']) && isset($post['role_id']) && $post['role_id']==3) {
    $output['img_path'] = '/img/profile_img/samplestaff.png';
  }


  // 対応可能時間の終了時間
  if(isset($post['free_time1'])){
    $time1=$post['free_time1']+1;
    if($time1 < 10){
      $output['endtime1']='0'.$time1;
    }else if($time1+1 == 25){
      $output['endtime1'] = 01;
    }else{
      $output['endtime1'] = $time1;
    }
  }
  if(isset($post['free_time2'])){
    $time1=$post['free_time2']+1;
    if($time1 < 10){
      $output['endtime2'] = '0'.$time1;
    }else if($time1+1 == 25){
      $output['endtime2'] = 01;
    }else{
      $output['endtime2'] = $time1;
    }
  }

  // デフォルトコメント
  if(!isset($post['comment']) || isset($post['comment']) && $post['comment']==null){
    $output['comment']="よろしくお願いいたします。";
  }else{
    $output['comment']=$post['comment'];
  }

  return $output;
}

public function valid($arr){
  $valid = [];
  $empty = '(!) 入力必須です。';
  $post = $this->request['post'];

  // 登録済みかどうか
  $rows = $this->User->mailfind($post);
  if(isset($rows['mail'])){
    $valid['mailfind']='(!) 登録できないメールアドレスです。';
  }

  // 区分のバリデーション
  if(isset($post['admin'])){
    if(empty($post['role_id'])){
      $valid['role_id']=$empty;
    }
  }

  // ニックネームのバリデーション
  if(empty($post['nickname'])){
    $valid['nickname']=$empty;
  }
  else if(mb_strlen($post['nickname']) > 15){
    $valid['nickname']='(!) ニックネームは15文字以内で入力してください。';
  }

  // 性別のバリデーション
  if(empty($post['gender'])){
    $valid['gender']=$empty;
  }

  // 出身地のバリデーション
  if(isset($post['birth_place']) && $post['birth_place']=='その他' && empty($post['birth_place2'])){
    $valid['birth_place2']="(!) その他を選択した場合、入力必須です。";
  }

  // 居住地のバリデーション
  if(isset($post['living_place']) && $post['living_place']=='その他' && empty($post['living_place2'])){
    $valid['living_place2']="(!) その他を選択した場合、入力必須です。";
  }

  // メールアドレスのバリデーション
  if(empty($post['mail'])){
    $valid['mail']=$empty;
  }
  else if (!filter_var($post['mail'], FILTER_VALIDATE_EMAIL)) {
    $valid['mail']='(!) メールアドレスの形式が正しくない可能性があります。';
  }

  // 対応可能時間のバリデーション
  if($post['role_id']==2 ){
    $equal = '(!) 同じ時間は指定できません';
    // 対応可能時間その２を設定しない場合
    if(empty($post['use_time2'])){
      if (empty($post['free_time1']) && empty($post['free_time2'])) {
        $valid['free_time2']=$equal;
      }

      if ($post['free_time1']==$post['free_time2']) {
        $valid['free_time2']=$equal;
      }
    }
  }

  // パスワードのバリデーション
  if (!isset($post['up_prof'])) {
    if(empty($post['password'])){
      $valid['password']=$empty;
    }else if (!preg_match("/^[a-zA-Z0-9_-]+$/", $post['password'])) {
      $valid['password']='(!) パスワードは半角英数字のみで入力してください。';
    }elseif (mb_strlen($post['password'])< 8) {
      $valid['password']='(!) パスワードは8文字以上で入力してください。';
    }
  }

  return $valid;

}

  // 相談板登録＆相談板ID取得
  public function insert_bord(){
    $post = $this->request['post'];
    if(isset($post['talk_on'])) {
        // 相談板登録
        $this->Bord->insert_bord($post);
      // 相談板ID取得
      $bordId=$this->Bord->findbordById($post['staff_id']);
      $_SESSION['Bord']=$bordId;
    }
  }


  public function datacontroll(){
    $post = $this->request['post'];

    if (isset($post['update'])) {
      // 更新
      $this->User->updateuser($post);
      if ($_SESSION['User']['role_id']!=3) {
        $_SESSION['User']['nickname']=$post['nickname'];
      }
    }
    elseif (isset($post['adduser'])) {
      // 登録
      $this->User->insert($post);
    }

    // スタッフ参照
    $page = 0;
    if(isset($this->request['get']['page'])){
      $page = $this->request['get']['page']-1;
    }

    $findStaff=$this->User->findStaff($page,$post);
    if(isset($post['staffsearch'])){
      $post['role_id']=2;
      $countStaff=$this->User->countStaff($post);
    }else{
      $countStaff=$this->User->countStaff();
    }
    $params=[
      'stafflist'=>$findStaff,
      'pages'=>$countStaff / 9
    ];
    return $params;

  }

  public function deluser(){
    $post = $this->request['post'];
    if (isset($post['del_id'])) {
      $this->User->deleteuser($post['del_id']);
    }
  }

  // お気に入りボタンの操作
  // お気に入り判定
  public function favBtnflg($a=0,$b=0){
      $data['favId']=$a;
      $data['user_id']=$b;
      $row=$this->Favorite->getfavId($data);
      return $row;
  }
  public function checkfav($post){
    $row=$this->Favorite->getfavId($post);
    return $row;
  }
  public function insertfav($post){
    $this->Favorite->fav_insert($post);
  }
  public function delfav($post){
    $this->Favorite->del_fav($post);
  }

  // 過去の相談一覧
  public function talkUserList(){
    $defaultname="匿名相談者";
    $defaultimg="/img/profile_img/default.png";

    $id['page'] = 0;
    if(isset($this->request['get']['page'])){
      $id['page'] = $this->request['get']['page']-1;
    }

    $id['myid']=$_SESSION['User']['id'];
    $getlist=$this->User->getTalkuserList($id);
    $coutlist=$this->User->countTalkuserList($id);
    $params=[
      'list'=>$getlist,
      'pages'=>$coutlist / 9,
      'defaultname'=>$defaultname,
      'defaultimg'=>$defaultimg
    ];
    return $params;
  }

  // お気に入りスタッフ一覧
  public function favuserList(){
    $id['page'] = 0;
    if(isset($this->request['get']['page'])){
      $id['page'] = $this->request['get']['page']-1;
    }
    $id['myid']=$_SESSION['User']['id'];
    $getlist=$this->Favorite->getFavuserList($id);
    $countlist=$this->Favorite->countFavuserList($id);
    $params=[
      "list"=>$getlist,
      'pages'=>$countlist / 30
    ];
    return $params;
  }

  // ユーザー一覧
  public function alluserList(){
    $id['page'] = 0;
    if(isset($this->request['get']['page'])){
      $id['page'] = $this->request['get']['page']-1;
    }

    $getlist=$this->User->findAll($id['page']);
    $countall=$this->User->countAll($id['page']);
    $params=[
      "list"=>$getlist,
      'pages'=>$countall / 20
    ];
    return $params;
  }

  // マイプロフィール
  public function myprofile(){
    $id=$_SESSION['User']['id'];
    $get=$this->request['get'];
    $post=$this->request['post'];

    if (isset($post['myid'])) {
      $myprofile=$this->User->finduserById($post['myid']);
    }else if(isset($get['user'])) {
      $myprofile=$this->User->finduserById($get['user']);
    }else if(isset($id)){
      $myprofile=$this->User->finduserById($id);
    }else{
      header('Location:login.php');
      exit();
    }

    return $myprofile;
  }



}
 ?>
