<?php
require_once(ROOT_PATH.'/Models/Db.php');

class Message extends Db {
  public function __construct($dbh = null) {
    parent::__construct($dbh);
  }

  // メッセージの登録
  public function insert_message($data){
    date_default_timezone_set('Asia/Tokyo');

    $sql="INSERT INTO messages(id,user_id,bord_id,view_name,message,post_date)
    VALUES(:id,:user_id,:bord_id,:view_name,:message,:post_date)";
    $stmt = $this->dbh->prepare($sql);
    $params=[
      ":id"=>null,
      ":user_id"=>$data['user_id'],
      ":bord_id"=>$data['bord_id'],
      ":view_name"=>$data['view_name'],
      ":message"=>$data['message'],
      ":post_date"=>date('Y-m-d H:i:s')
    ];
    $stmt->execute($params);
  }

  // メッセージ取得 JOINでbordsテーブルを引っ張ってくる予定
  public function getMessageById($data){
    $sql="SELECT
      m.id as id, m.user_id as user_id, m.message as message,b.talker_id as talkerId, b.staff_id as staffId,";
    $sql.=" m.view_name as view_name,";
    $sql.=" u1.nickname as talker,";
    $sql.=" u2.nickname as staff";
    $sql.=" FROM messages as m";
    $sql.=" JOIN bords as b ON m.bord_id=b.id";
    $sql.=" JOIN users as u1 ON u1.id=b.talker_id";
    $sql.=" JOIN users as u2 ON u2.id=b.staff_id";
    $sql.=" WHERE m.bord_id=:bord_id";
    $sql.=" ORDER BY m.id";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":bord_id",$data['bord_id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function n_getMessageById($data){
    $sql="SELECT
      m.id as id, m.user_id as user_id, m.message as message,b.talker_id as talkerId, b.staff_id as staffId,";
    $sql.=" m.view_name as talker,";
    $sql.=" u2.nickname as staff";
    $sql.=" FROM messages as m";
    $sql.=" JOIN bords as b ON m.bord_id=b.id";
    $sql.=" JOIN users as u2 ON u2.id=b.staff_id";
    $sql.=" WHERE m.bord_id=:bord_id";
    $sql.=" ORDER BY m.id";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":bord_id",$data['bord_id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  // メッセージ件数取得
  public function countMessage($bord_id){
    $sql="SELECT * FROM messages WHERE bord_id=:bord_id";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":bord_id",$bord_id,PDO::PARAM_INT);
    $stmt->execute();
    $result=$stmt->rowCount();
    return $result;
  }

}
 ?>
