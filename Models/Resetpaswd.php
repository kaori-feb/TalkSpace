<?php
require_once(ROOT_PATH.'/Models/Db.php');

class Resetpaswd extends Db {
  public function __construct($dbh = null) {
    parent::__construct($dbh);
  }

  public function insert_token($data){
    date_default_timezone_set('Asia/Tokyo');
    $sql="INSERT INTO resetpaswds(id,user_id,mail,reset_token,created_at) VALUES(:id,:user_id,:mail,:reset_token,:created_at)";
    $stmt=$this->dbh->prepare($sql);
    $params=[
      ':id'=>null,
      ':user_id'=>$data['id'],
      ':mail'=>$data['mail'],
      ':reset_token'=>$data['resetpaswd_token'],
      ':created_at'=>date('Y-m-d H:i:s')
    ];
    $stmt->execute($params);
  }

  public function findtoken($data){
    $sql="SELECT id, user_id, reset_token FROM resetpaswds";
    if (isset($data['findmail'])) {
      $sql .= " WHERE mail = :mail";
    }else{
      $sql .= " WHERE id = :id AND reset_token = :reset_token";
    }
    $sth=$this->dbh->prepare($sql);
    if (isset($data['findmail'])) {
    $sth->bindValue("mail",$data['mail']);
  }else{
    $sth->bindValue("id", $data['id']);
    $sth->bindValue("reset_token", $data['token']);
  }
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  // トークンデータの削除
  public function deletetoken($id=null){

    $sql="DELETE FROM resetpaswds WHERE id = :id";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();
  }

}
 ?>
