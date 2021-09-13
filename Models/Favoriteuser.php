<?php
require_once(ROOT_PATH.'/Models/Db.php');

class Favoriteuser extends Db {
  public function __construct($dbh = null) {
    parent::__construct($dbh);
  }

  public function fav_insert($data){
    date_default_timezone_set('Asia/Tokyo');

    $sql="INSERT INTO favoriteusers(id,user_id,favoriteuser_id,created_at) VALUES(:id,:user_id,:favoriteuser_id,:created_at)";
    $stmt=$this->dbh->prepare($sql);
    $params=[
      ":id"=>NULL,
      ":user_id"=>$data['user_id'],
      ":favoriteuser_id"=>$data['favId'],
      ":created_at"=>date('Y-m-d H:i:s'),
    ];
    $stmt->execute($params);
  }

  // お気に入りスタッフ一覧
  public function getFavuserList($id){
    $sql = "SELECT f.favoriteuser_id as id,u.img_path as img, u.nickname as name, u.gender_open as gender_open, u.birth_place as birth_place, u.birth_place2 as birth_place2, u.birth_place_open as birth_place_open, u.living_place as living_place, u.living_place2 as living_place2, u.living_place_open as living_place_open, u.free_time1 as free_time1,u.free_time2 as free_time2,";
    $sql .= ' CASE
                WHEN u.gender = 1 THEN "女性"
                WHEN u.gender = 2 THEN "男性"
                ELSE "その他" END AS output_gender';
    $sql .= " FROM favoriteusers as f";
    $sql .= " LEFT JOIN users as u ON u.id=f.favoriteuser_id";
    $sql .= " WHERE f.user_id=:user_id";
    $sql .= ' LIMIT 30 OFFSET '.(30 * $id['page']);
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":user_id",$id['myid']);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function countFavuserList($id){
    $sql="SELECT id FROM favoriteusers WHERE user_id=:myid";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":myid",$id['myid']);
    $stmt->execute();
    $count=$stmt->fetchColumn();
    return $count;
  }

  public function getfavId($data){
    $sql="SELECT * FROM favoriteusers WHERE user_id=:user_id AND favoriteuser_id=:favId";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":user_id",$data['user_id']);
    $stmt->bindValue(":favId",$data['favId']);
    $stmt->execute();
    $result = $stmt->rowCount(PDO::FETCH_ASSOC);
    return $result;
  }

  public function del_fav($data){
    $sql="DELETE FROM favoriteusers WHERE user_id=:user_id AND favoriteuser_id=:favId";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":user_id",$data['user_id']);
    $stmt->bindValue(":favId",$data['favId']);
    $stmt->execute();
  }

}
 ?>
