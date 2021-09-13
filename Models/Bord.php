<?php
require_once(ROOT_PATH.'/Models/Db.php');

class Bord extends Db {
  public function __construct($dbh = null) {
    parent::__construct($dbh);
  }

  public function insert_bord($data){
    date_default_timezone_set('Asia/Tokyo');

    $sql="INSERT INTO
    bords(id,staff_id,talker_id,created_at,is_finished)
    VALUES
    (:id,:staff_id,:talker_id,:created_at,:is_finished)";
    $stmt = $this->dbh->prepare($sql);
    $params = [
      ":id" => null,
      ":staff_id" => $data['staff_id'],
      ":talker_id" => null,
      ":created_at" => date('Y-m-d H:i:s'),
      ":is_finished" => null
    ];
    $stmt->execute($params);
  }

  // 相談版IDの取得
  public function findbordById($staff_id = 0):Array{
    $sql="SELECT max(id) as now_bord FROM bords WHERE staff_id = :staff_id";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(':staff_id', $staff_id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  // 相談板のデータ取得
  public function findThisbordById($bord_id = 0):Array{
    $sql="SELECT staff_id, talker_id FROM bords WHERE id = :bord_id";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(':bord_id', $bord_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  // 相談受付中判定用：最大のIDの終了時間がNULL
  public function can_talk($id){
    $sql="SELECT id,staff_id,talker_id,is_finished FROM bords WHERE staff_id=:id AND id=(SELECT max(id) FROM bords WHERE staff_id=:id) AND is_finished IS NULL";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":id",$id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  // 相談受付終了
  public function finishTalk($data){
    date_default_timezone_set('Asia/Tokyo');

    $sql="UPDATE bords SET is_finished=:is_finished WHERE id=:bord_id";
    $stmt=$this->dbh->prepare($sql);
    $params=[
      ":bord_id"=>$data['finishbord_id'],
      ":is_finished"=>date('Y-m-d H:i:s')
    ];
    $stmt->execute($params);
  }

  // 相談受け付け終了時間取得
  public function finishtime($data){
    $sql="SELECT is_finished FROM bords WHERE id=:bord_id";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(':bord_id',$data);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  // 相談者IDの登録
  public function in_talker($data){
    date_default_timezone_set('Asia/Tokyo');

    $sql="UPDATE bords SET talker_id=:talker_id, created_at=:created_at WHERE id=:bord_id";
    $stmt=$this->dbh->prepare($sql);
    $params=[
      ":talker_id"=>$data['talker_id'],
      ":created_at" => date('Y-m-d H:i:s'),
      ":bord_id"=>$data['bord_id']
    ];
    $stmt->execute($params);
  }


}
 ?>
