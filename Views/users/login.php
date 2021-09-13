<?php
session_start();
require_once(ROOT_PATH.'Controllers/UserController.php');
$user = new UserController();
$message = $user->login();
 ?>

<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>悩み相談SPACE「」：ログインページ</title>
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/login.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-scrollify@1/jquery.scrollify.min.js"></script>
    <script type="text/javascript" src="/js/loginpage.js"></script>
  </head>
  <body>

<div class="section">
  <div class="login_logo"></div>

  <div class="loginform_container">
    <div class="login_form">
      <div class="user">
        <div class="title">
          ◇◆ログイン◆◇
        </div>
        <form action="" method="post" class="center ma0">
          <?php if (isset($message['mail'])): ?>
            <p class="login_error"><?= $message['mail'] ?></p>
          <?php endif; ?>
          <p><label>メールアドレス：<br>
            <input type="text" name="login_mail" placeholder="メールアドレスを入力" class="login_input"></label></p>
            <?php if (isset($message['pass'])): ?>
              <p class="login_error"><?= $message['pass'] ?></p>
            <?php endif; ?>
          <p><label>パスワード：<br>
            <input type="password" name="login_password"  placeholder="パスワードを入力" class="login_input"></label></p>
            <button type="submit" name="login" class="common_btn">ログインする</button>
        </form>
        <div class="iforgot ma0">
          <a href="iforgot.php">パスワードをお忘れですか？</a>
        </div>
      </div>

      <div class="user">
        <div class="top_adduser">
          <div class="title">
            ◇◆相談スタッフ登録はこちら◆◇
          </div>

            <form action="adduser.php" method="post" class="center">
              <button type="submit" name="addstaff" class="adduser_btn center">相談スタッフになる</button>
            </form>
          </div>

        <div class="top_adduser">
          <div class="title">
            ◇◆登録をしていない方はこちら◆◇
          </div>
          <div class="anonymous_container center scrollpoint">
            <a href="#commit" class="scroll">
            <i class="fas fa-chevron-down fa-2x"></i><br>Scroll
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>


<div id="commit" class="section">
  <div class="wrap_bg commit_board">
    <div class="line commit_line">
      <div class="commit_main">
        <div class="box">
          <h1>はじめに</h1>
          <p>
            この窓口は年齢や性別を問わず誰でも無料・匿名で利用できるチャット相談窓口です。<br>
            ユーザー登録していただきますと、特定のスタッフへ相談の予約ができたり、過去の相談内容を振り返ることができるようになります。<br>
            相談の秘密はまもり、あなたの同意がない限り相談内容を誰かに伝えることはありません。
          </p>
          <h2>注意</h2>
          <ol type="1">
            <li>相談内容はSNSなどで公開しないでください</li>
            <li>相談スタッフの相談対応時間を過ぎた場合は相談を終了することがあります</li>
            <li>10分以上応答が途絶えた場合は相談を終了することがあります</li>
            <li>医療機関ではありませんので医療行為のアドバイスはできません</li>
            <li>犯罪などに関する内容、アドバイスはできません。</li>
          </ol>
        </div>

        <div class="box">
          <p class="center commit_main_message">
            あなたの悩みを私達も一緒に考えます。<br>
            まずは相談を、してみませんか
          </p>
          <div class="flex flex_around sp-anonymous">
            <form action="adduser.php" method="post" class="center">
              <button type="submit" name="addtalker" class="adduser_btn">新規登録</button>
            </form>
            <form action="index.php" method="post" class="center">
              <input type="hidden" name="n_userid" value="<?= uniqid(rand().'_') ?>">
              <button type="submit" name="anonymous" class="adduser_btn">登録せずに相談する</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  </body>
</html>
