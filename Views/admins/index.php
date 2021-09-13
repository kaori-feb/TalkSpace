<?php
session_start();
require_once(ROOT_PATH .'Controllers/UserController.php');
$user = new UserController();
$user->xss();
$user->deluser();
$alluser=$user->alluserList();

if(!isset($_SESSION['User'])){
  header('Location:/users/login.php');
  exit;
}
// トークン発行
$_SESSION['csrf_token'] =[];
$toke_byte=openssl_random_pseudo_bytes(16);
$csrf_token=bin2hex($toke_byte);
$_SESSION['csrf_token'] = $csrf_token;

 ?>
<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>悩み相談SPACE「」：管理ホーム</title>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/talkspace.js"></script>
  </head>
  <body>
    <?php require_once('../Views/header.php') ?>

    <div class="wrap_bg ma0">
      <div class="line">
        <h1 class="title talkspace_title">管理トップ</h1>

        <div class="admin_adduser ma0">
          <form action="/users/adduser.php" method="post">
            <button type="submit" name="adduser_fromadmin" class="navy_btn">新規ユーザー登録</button>
          </form>
        </div>

        <div class="sp-admin_table">
        <table class="admin_table ma0">
          <tr>
            <th>id</th>
            <th>ユーザ名</th>
            <th>区分</th>
            <th>メールアドレス</th>
            <th colspan="2">編集 / 削除</th>
          </tr>
          <?php foreach ($alluser['list'] as $value): ?>
            <tr>
              <td><?= h($value['id']) ?></td>
              <td>
                <div class="overxauto">
                <a href="/users/profile.php?user=<?= h($value['id']) ?>" target="_blank"><?= h($value['nickname']) ?></a>
                </div>
              </td>
              <td><?= h($value['output_role']) ?></td>
              <td>
                <div class="admin_mail overxauto">
                  <?= h($value['mail']) ?>
                </div>
                </td>
              <td>
                <form action="/users/updateprofile.php" method="post">
                  <input type="hidden" name="myid" value="<?= h($value['id']) ?>">
                  <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
                  <button type="submit" name="go_up_prof" class="admin_btn">編 集</button>
                </form>
              </td>
              <td>
                <?php if ($value['id']!=$_SESSION['User']['id']): ?>
                  <input type="hidden" name="del_id" value="<?= h($value['id']) ?>">
                  <button type="submit" name="delete" class="admin_btn admin_delbtn">削 除</button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>

        <?php if ($alluser['pages']>=1): ?>
          <div class="paging">
            <?php
            for($i=1; $i<=$alluser['pages']+1; $i++){
              // URLにページ番号ある　& ページ番号がiと一致
              if(isset($_GET['page']) && $_GET['page'] == $i){
                echo $i;
              }elseif (!isset($_GET['page']) && $i == 1) {
                echo $i;
              }
              else {
                echo '<a href=?page='.($i).'>'.($i).'</a>';
              }
            }
            ?>
          </div>
        <?php endif; ?>

      </div>
    </div>

    <div class="popup">
    <div class="popup_bg js-popup_close"></div>
    <div class="popup_content">
    <form action="" method="post">
    <h1>確認</h1>
    <p>No.<span class="del_id"></span>のデータを削除してよろしいですか？</p>
    <div class="flex flex_around">
      <input type="hidden" name="del_id" value="" class="del_id">
      <button type="submit" name="delete" class="navy_btn">削除</button>
      <button type="button" class="navy_btn js-popup_close">キャンセル</button>
    </div>
    </form>
    </div>
    </div>

    <?php
    if(isset($_GET['del'])){
      echo '<script type="text/javascript">popup();</script>';
    }
    ?>

    <?php require_once('../Views/footer.php'); ?>
  </body>
</html>
