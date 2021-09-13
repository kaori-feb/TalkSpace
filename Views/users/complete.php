<?php
session_start();
require_once(ROOT_PATH.'Controllers/UserController.php');
$user = new UserController();
$csrf_token = $user->csrf();
$user->xss();
$user->setData();

// 二重投稿阻止：POSTされたトークンを取得
$token = isset($_POST["doublesubmit_token"]) ? $_POST["doublesubmit_token"] : "";
// セッション変数のトークンを取得
$session_token = isset($_SESSION["doublesubmit_token"]) ? $_SESSION["doublesubmit_token"] : "";
// セッション変数のトークンを削除
unset($_SESSION["doublesubmit_token"]);
// POSTされたトークンとセッション変数のトークンの比較
if($token != "" && $token == $session_token) {
  if (isset($_POST['update'])) {
    $user->datacontroll();
    if ($_SESSION['User']['role_id']!=3) {
      header("Location:/users/myprofile.php");
      exit();
    }else {
      header("Location:/admins/index.php");
      exit();
    }
  }else{
    // 登録画面送信データの登録を行う
    $user->datacontroll();
    if ($_SESSION['User']['role_id']==3) {
      header("Location:/admins/index.php");
      exit();
    }
  }
}

 ?>

<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>悩み相談SPACE「」：登録完了</title>
    <link rel="stylesheet" href="/css/login.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  </head>
  <body>
    <div class="header_logo">
        <a href="login.php">
        <img src="/img/Logowords.png" alt="悩み相談SPACE「」"></a>
    </div>
    <div class="wrap_bg ma0">
      <div class="line">
        <div class="content ma0">
          <div class="step ma0">
            <ul class="flex flex_center">
              <li>ユーザー情報の入力</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li>入力内容の確認</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li class="current">登録完了（ホームへ）</li>
            </ul>
          </div>

          <div class="complete">
            <div class="centerposition">
              <p class="complete_message">登録が完了しました。</p>
              <p>次回ログイン時には登録したメールアドレスとパスワードが必要になります。</p>
            </div>
          </div>

          <form action="/users/index.php" method="post" class="center">
            <?php
            // 二重送信防止用トークンの発行
            $doublesubmit_token = uniqid('', true);
            //トークンをセッション変数にセット
            $_SESSION['doublesubmit_token_forhome'] = $doublesubmit_token;
             ?>
            <input type="hidden" name="login_mail" value="<?= h($_POST['mail']) ?>">
            <input type="hidden" name="login_password" value="<?= h($_POST['password']) ?>">
            <input type="hidden" name="doublesubmit_token_forhome" value="<?= h($doublesubmit_token) ?>">
            <button type="submit" name="to_home" class="common_btn">マイページへ進む</button>
          </form>

        </div>
      </div>
    </div>

    <?php require_once('../Views/footer.php'); ?>

  </body>
</html>
