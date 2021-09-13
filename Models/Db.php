<?php
require_once(ROOT_PATH.'/database.php');

class Db{
  protected $dbh;

  public function __construct($dbh=null){
    // 接続情報が存在しない場合
    if(!$dbh) {
      try {
        $this->dbh = new PDO(
          'mysql:dbname='.DB_NAME.
          ';host='.DB_HOST, DB_USER, DB_PASSWD
        );
        // 接続成功
      } catch (PDOException $e) {
        echo '接続失敗：'.$e->getMessage() . '\n';
        exit();
      }

    } else{
      // 情報が存在する場合
      $this->dbh = $dbh;
    }
  }
  // DBハンドラを返す（DB接続処理を繰り返す）メソッド
  public function get_Db_handler(){
    return $this->dbh;
  }
}
 ?>
