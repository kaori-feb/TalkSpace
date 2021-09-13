<?php
session_start();
require_once(ROOT_PATH .'Controllers/UserController.php');
$user = new UserController();
$user->xss();
// 二重投稿阻止
$post_iforgot_token=isset($_POST["iforgot_token"]) ? $_POST["iforgot_token"] : "";
$session_iforgot_token=isset($_SESSION["iforgot_token"]) ? $_SESSION["iforgot_token"] : "";
unset($_SESSION['iforgot_token']);

if($post_iforgot_token != "" && $post_iforgot_token == $session_iforgot_token){
    $message=$user->resetpaswd();

    if (empty($message)) {
      $result=$user->findResetpaswd_token();
    }

}

  $iforgot_token = uniqid('', true);
  $_SESSION['iforgot_token'] = $iforgot_token;

 ?>

<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>パスワード再登録：悩み相談SPACE「」</title>
    <link rel="stylesheet" href="/css/login.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/talkspace.js"></script>
  </head>
  <body>
    <div class="header_logo">
      <a href="login.php">
      <img src="/img/Logowords.png" alt="悩み相談SPACE「」"></a>
    </div>

    <div class="wrap_bg ma0">
      <div class="line">
        <div class="content ma0">
          <h1>パスワードを変更しますか？</h1>
          <div class="step ma0">
            <ul class="flex flex_center">
              <li class="current">メールアドレスの入力</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li>パスワードの入力</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li>登録完了</li>
            </ul>
          </div>
          <p class="text">
            登録したメールアドレスを入力していただきますと、パスワード再設定ページのURLをご案内いたします。
          </p>

          <div class="address">
            <h3>パスワード設定　STEP1</h3>
            <?php if (isset($result)): ?>
              <div class="form_container">
                <table>
                  <tr>
                    <th>パスワード変更ページ：</th>
                    <td>
                      <a href="changepw.php?id=<?= h($result['id']) ?>&token=<?= h($result['reset_token']) ?>">パスワードを変更する</a>
                    </td>
                  </tr>
                </table>
            </div>
            <?php else: ?>
          <form action="" method="post">
            <input type="hidden" name="iforgot_token" value="<?= h($iforgot_token) ?>">
            <div class="form_container">
              <table>
                <?php if (!empty($message)): ?>
                  <tr>
                    <td colspan="2">
                      <p class="error"><?= h($message['message']['error']) ?></p>
                    </td>
                  </tr>
                <?php endif; ?>
                <tr>
                  <th>メールアドレス<span class="red">*</span>：</th>
                  <td>
                    <input type="text" name="mail" placeholder="登録済みのメールアドレスを入力してください">
                  </td>
                </tr>
              </table>
            </div>

            <div class="btn_container center">
              <button type="submit" name="findmail" class="common_btn">送 信</button>
            </div>
            </form>
          <?php endif; ?>
          </div>

        </div>
      </div>
    </div>

    <?php require_once('../Views/footer.php'); ?>

  </body>
</html>
