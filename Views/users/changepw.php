<?php
session_start();
require_once(ROOT_PATH .'Controllers/UserController.php');
$user = new UserController();
$user->xss();
$error=$user->changepw();

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
          <h1>パスワードの変更</h1>
          <div class="step ma0">
            <ul class="flex flex_center">
              <li>メールアドレスの入力</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li
              <?php if(!isset($error['complete'])): ?>class="current"<?php endif; ?>>パスワードの入力</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li <?php if(isset($error['complete'])): ?>class="current"<?php endif; ?>>登録完了</li>
            </ul>
          </div>

          <?php if (isset($error['complete'])): ?>
            <div class="complete">
              <div class="centerposition">
                <p class="complete_message"><?= h($error['complete']) ?></p>
                <p>
                  <a href="/users/login.php">ログインページへ</a>
                </p>
              </div>
            </div>
          <?php else: ?>
          <p class="text">
            新しいパスワードを入力してください。
          </p>
          <div class="address">
            <form action="" method="post">
              <input type="hidden" name="iforgot_token" value="<?= h($iforgot_token) ?>">
              <h3>パスワード設定</h3>
              <div class="form_container">
                <table>
                  <?php if (!empty($error['message'])): ?>
                    <tr>
                      <td colspan="2">
                        <p class="error"><?= h($error['message']) ?></p>
                      </td>
                    </tr>
                  <?php endif; ?>
                  <tr>
                    <th>パスワード<span class="red">*</span>：</th>
                    <td>
                      <input type="password" name="pass" placeholder="新しいパスワードを入力してください" required>(半角英数字)
                      <span class="toggle_pass eye"></span>
                    </td>
                  </tr>
                </table>
              </div>

            <div class="btn_container center">
              <button type="submit" name="changepw" class="common_btn">送 信</button>
            </div>
            </form>
          </div>

        <?php endif; ?>

        </div>
      </div>
    </div>

    <?php require_once('../Views/footer.php'); ?>

  </body>
</html>
