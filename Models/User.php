<?php
require_once(ROOT_PATH.'/Models/Db.php');

class User extends Db {
  public function __construct($dbh = null) {
    parent::__construct($dbh);
  }


  // 参照
  public function findAll($page = 0):Array{
    $sql = 'SELECT id, nickname,mail,';
    $sql .= ' CASE
                WHEN role_id = 1 THEN "相談者"
                WHEN role_id = 2 THEN "相談スタッフ"
                ELSE "管理者" END AS output_role';
    $sql .= ' FROM users';
    $sql .= ' LIMIT 20 OFFSET '.(20 * $page);
    $sth = $this->dbh->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  // ユーザー参照（ID利用）
  public function finduserById($id){
    $sql = 'SELECT id, role_id, nickname, mail, gender_open, birth_place, birth_place2, birth_place_open, living_place, living_place2, living_place_open, free_time1, free_time2, img_path,comment,';
    $sql .= ' CASE
                WHEN gender = 1 THEN "女性"
                WHEN gender = 2 THEN "男性"
                ELSE "その他" END AS output_gender,';
    $sql .= ' CASE
                WHEN role_id = 1 THEN "相談者"
                WHEN role_id = 2 THEN "相談スタッフ"
                ELSE "管理者" END AS output_role';
    $sql .= ' FROM users';
    $sql .= ' WHERE id=:id';
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":id",$id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  // スタッフ参照
  public function findStaff($page = 0, $findStaff):Array{
    $role_id=2;
    $no_open=0;

    $sql = 'SELECT id, role_id, nickname, gender_open, birth_place, birth_place2, birth_place_open, living_place, living_place2, living_place_open, free_time1, free_time2, img_path,';
    $sql .= ' CASE
                WHEN gender = 1 THEN "女性"
                WHEN gender = 2 THEN "男性"
                ELSE "その他" END AS output_gender';
    $sql .= ' FROM users';
    $sql .= ' WHERE role_id = :role_id';

    if (isset($findStaff)) {
      if (isset($findStaff['staffgender']) && $findStaff['staffgender']!=null) {
        $sql .= ' AND gender = :gender AND gender_open != :gender_open';
      }
      if (isset($findStaff['staff_birth_place']) && $findStaff['staff_birth_place']!=null) {
        $sql .= ' AND birth_place = :birth_place AND birth_place_open != :birth_place_open';
      }
      if (isset($findStaff['staff_living_place']) && $findStaff['staff_living_place']!=null) {
        $sql .= ' AND living_place = :living_place AND living_place_open != :living_place_open';
      }
      if (isset($findStaff['staff_free_time']) && $findStaff['staff_free_time']!=null) {
        $sql .= ' AND free_time1 = :free_time1 OR free_time2 = :free_time2';
      }
    }
    $sql .= ' LIMIT 9 OFFSET '.(9 * $page);
    $sth = $this->dbh->prepare($sql);

    $sth->bindValue(':role_id',$role_id);

    if (isset($findStaff)) {
      if (isset($findStaff['staffgender']) && $findStaff['staffgender']!=null) {
        $sth->bindValue(':gender',$findStaff['staffgender']);
        $sth->bindValue(':gender_open',$no_open);
      }
      if (isset($findStaff['staff_birth_place']) && $findStaff['staff_birth_place']!=null) {
        $sth->bindValue(':birth_place',$findStaff['staff_birth_place']);
        $sth->bindValue(':birth_place_open',$no_open);
      }
      if (isset($findStaff['staff_living_place']) && $findStaff['staff_living_place']!=null) {
        $sth->bindValue(':living_place',$findStaff['staff_living_place']);
        $sth->bindValue(':living_place_open',$no_open);
      }
      if (isset($findStaff['staff_free_time']) && $findStaff['staff_free_time']!=null) {
        $sth->bindValue(':free_time1',$findStaff['staff_free_time']);
        $sth->bindValue(':free_time2',$findStaff['staff_free_time']);
      }
    }
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  // スタッフトータル件数の取得
  public function countStaff($findUser=[]):Int{
    $role_id=2;
    $no_open=0;

    $sql = 'SELECT count(*) as count FROM users';
    $sql.=' WHERE role_id=:role_id';

    if (isset($findUser)) {

      if (isset($findUser['staffgender']) && $findUser['staffgender']!=null) {
        $sql .= ' AND gender = :gender AND gender_open != :gender_open';
      }
      if (isset($findUser['staff_birth_place']) && $findUser['staff_birth_place']!=null) {
        $sql .= ' AND birth_place = :birth_place AND birth_place_open != :birth_place_open';
      }
      if (isset($findUser['staff_living_place']) && $findUser['staff_living_place']!=null) {
        $sql .= ' AND living_place = :living_place AND living_place_open != :living_place_open';
      }
      if (isset($findUser['staff_free_time']) && $findUser['staff_free_time']!=null) {
        $sql .= ' AND free_time1 = :free_time1 OR free_time2 = :free_time2';
      }
    }

    $sth = $this->dbh->prepare($sql);

    if (isset($findUser['staffsearch'])) {
      $sth->bindValue(':role_id',$findUser['role_id']);
    }else{
      $sth->bindValue(':role_id',$role_id);
    }

    if (isset($findUser)) {
      if (isset($findUser['staffgender']) && $findUser['staffgender']!=null) {
        $sth->bindValue(':gender',$findUser['staffgender']);
        $sth->bindValue(':gender_open',$no_open);
      }
      if (isset($findUser['staff_birth_place']) && $findUser['staff_birth_place']!=null) {
        $sth->bindValue(':birth_place',$findUser['staff_birth_place']);
        $sth->bindValue(':birth_place_open',$no_open);
      }
      if (isset($findUser['staff_living_place']) && $findUser['staff_living_place']!=null) {
        $sth->bindValue(':living_place',$findUser['staff_living_place']);
        $sth->bindValue(':living_place_open',$no_open);
      }
      if (isset($findUser['staff_free_time']) && $findUser['staff_free_time']!=null) {
        $sth->bindValue(':free_time1',$findUser['staff_free_time']);
        $sth->bindValue(':free_time2',$findUser['staff_free_time']);
      }
    }

    $sth->execute();
    $count = $sth->fetchColumn();
    return $count;
  }

  // メール参照
  public function mailfind($mail){
    $sql = 'SELECT id, role_id, mail FROM users WHERE mail=:mail';
    if (!isset($mail['findmail'])) {
    $sql .= ' AND role_id=:role_id';
  }
    if (isset($mail['up_prof'])) {
      $sql .= ' AND user_id!=:user_id';
    }
    $sth = $this->dbh->prepare($sql);
    if (!isset($mail['findmail'])) {
      $sth->bindValue(':role_id',$mail['role_id']);
    }
    if (isset($mail['up_prof'])) {
      $sth->bindValue(':user_id',$mail['id']);
    }
    $sth->bindValue(':mail',$mail['mail']);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  // パスワード変更
  public function changepw($data){
    date_default_timezone_set('Asia/Tokyo');
    $sql="UPDATE users SET password = :password, updated_at = :updated_at WHERE id=:user_id";
    $sth=$this->dbh->prepare($sql);
    $params=[
      ":user_id"=>$data['user_id'],
      ":password"=>$data['password'],
      ":updated_at"=>date('Y-m-d H:i:s')
    ];
    $sth->execute($params);
  }

  // ログイン
  public function login($arr){
    $sql = 'SELECT * FROM users WHERE mail=:mail';
    $sth = $this->dbh->prepare($sql);
    $sth->bindValue(':mail',$arr['login_mail']);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  // 過去の相談一覧
  public function getTalkuserList($id){
    $sql = "SELECT u.id as talkerid,u2.id as talkerid2, u.img_path as img,u2.img_path as img2, u.nickname as name,u2.nickname as name2,b.id as bord_id, b.created_at as day ";
    $sql .= " FROM bords as b";
    $sql .= " LEFT JOIN users as u ON u.id=b.talker_id";
    $sql .= " LEFT JOIN users as u2 ON u2.id=b.staff_id";
    $sql .= " WHERE b.talker_id=:talker_id OR b.staff_id=:staff_id";
    $sql .= " ORDER BY b.id DESC";
    $sql .= ' LIMIT 9 OFFSET '.(9 * $id['page']);
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":talker_id",$id['myid']);
    $stmt->bindValue(":staff_id",$id['myid']);
    $stmt->execute();
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function countTalkuserList($id){
    $sql="SELECT id FROM bords WHERE talker_id=:myid OR staff_id=:myid";
    $stmt=$this->dbh->prepare($sql);
    $stmt->bindValue(":myid",$id['myid']);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
  }

  // 更新　UPDATE
  public function updateuser($data){
    date_default_timezone_set('Asia/Tokyo');
    $sql="UPDATE users SET
      img_path=:img_path,nickname=:nickname,
      gender=:gender,gender_open=:gender_open,mail=:mail,
      birth_place=:birth_place,birth_place2=:birth_place2,
      birth_place_open=:birth_place_open,
      living_place=:living_place,living_place2=:living_place2,
      living_place_open=:living_place_open,comment=:comment,updated_at=:updated_at";
    if ($data['role_id']==2) {
      $sql.=" ,free_time1=:free_time1,free_time2=:free_time2";
    }
    if ($data['role_id']==3) {
      $sql.=" ,role_id=:role_id";
    }
    $sql.=" WHERE id=:id";
    $sth=$this->dbh->prepare($sql);
    $params=[
      ":id"=>$data['id'],
      ":img_path"=>$data['img_path'],
      ":nickname" => $data['nickname'],
      ":mail" => $data['mail'],
      ":gender" => $data['gender'],
      ":gender_open" => $data['gender_open'],
      ":birth_place"=>$data['birth_place'],
      ":birth_place2"=>$data['birth_place2'],
      ":birth_place_open"=>$data['birth_place_open'],
      ":living_place"=>$data['living_place'],
      ":living_place2"=>$data['living_place2'],
      ":living_place_open"=>$data['living_place_open'],
      ":comment"=>$data['comment'],
      ":updated_at"=>date('Y-m-d H:i:s'),
    ];
    if ($data['role_id']==2) {
      $params[":free_time1"]=$data['free_time1'];
      $params[":free_time2"]=$data['free_time2'];
    }
    if ($data['role_id']==3) {
      $params[":role_id"]=$data['role_id'];
    }
    $sth->execute($params);
  }

  // 登録　INSERT
  public function insert($data){
    date_default_timezone_set('Asia/Tokyo');
    if(isset($data['update_flg'])){
      $data['updated_at']=date('Y-m-d H:i:s');
    }else{
      $data['updated_at']=null;
    }
    $sql="INSERT INTO
      users(id,role_id,nickname,mail,password,gender,
      gender_open,
      birth_place,
      birth_place2,
      birth_place_open,
      living_place,
      living_place2,
      living_place_open,
      free_time1,
      free_time2,
      comment,
      img_path,
      created_at,
      updated_at)
      VALUES(:id,:role_id,:nickname,:mail,:password,:gender,
      :gender_open,
      :birth_place,
      :birth_place2,
      :birth_place_open,
      :living_place,
      :living_place2,
      :living_place_open,
      :free_time1,
      :free_time2,
      :comment,
      :img_path,
      :created_at,
      :updated_at)";
      $stmt = $this->dbh->prepare($sql);
      $params = [
        ":id" => null,
        ":role_id"=> $data['role_id'],
        ":nickname" => $data['nickname'],
        ":mail" => $data['mail'],
        ":password" => $data['password'],
        ":gender" => $data['gender'],
        ":gender_open" => $data['gender_open'],
        ":birth_place"=>$data['birth_place'],
        ":birth_place2"=>$data['birth_place2'],
        ":birth_place_open"=>$data['birth_place_open'],
        ":living_place"=>$data['living_place'],
        ":living_place2"=>$data['living_place2'],
        ":living_place_open"=>$data['living_place_open'],
        ":free_time1"=>$data['free_time1'],
        ":free_time2"=>$data['free_time2'],
        ":comment"=>$data['comment'],
        ":img_path"=>$data['img_path'],
        ":created_at"=>date('Y-m-d H:i:s'),
        ':updated_at'=>$data['updated_at']
      ];
      $stmt->execute($params);
    }

    // ユーザーデータの削除
    public function deleteuser($id=null){
      $sql="DELETE FROM users WHERE id = :id";
      $stmt=$this->dbh->prepare($sql);
      $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
      $stmt->execute();
    }

    // トータル件数の取得
    public function countAll():Int {
      $sql = 'SELECT count(*) as count FROM users';
      $sth = $this->dbh->prepare($sql);
      $sth->execute();
      $count = $sth->fetchColumn();
      return $count;
    }

}

 ?>
