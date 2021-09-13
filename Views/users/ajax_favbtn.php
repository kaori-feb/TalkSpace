<?php
require_once(ROOT_PATH.'Controllers/UserController.php');
$user = new UserController();

$message=[];

  $data=[];
  $data['favId']=$_POST['favId'];
  $data['user_id']=$_POST['user_id'];

  // favIdとユーザーIDが一致したレコードを取得
  $row=$user->checkfav($data);

  if (!empty($row)) {
    // レコードがある場合->レコードの削除（お気に入り解除）
    $user->delfav($data);
    $message='del';
    echo $message;
  }else{
    // レコードがない->お気に入り追加(INSERT)
    $user->insertfav($data);
    $message="add";
    echo $message;
}

 ?>
