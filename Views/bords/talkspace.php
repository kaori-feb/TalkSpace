<?php
session_start();
require_once(ROOT_PATH .'Controllers/BordController.php');
$bord = new BordController();
$bord->xss();
$t_csrf_token=$bord->csrf();
$bord->userIdcheck();
if (isset($_POST["to_talkspace"])) {
  $talkusers=$bord->getUsersData();
  $_SESSION['TalkSpace']=$talkusers;
}
 ?>
<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>相談スペース：悩み相談SPACE「」</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/talkspace.js"></script>
    <script type="text/javascript" src="/js/talkspace_ajax.js"></script>
  </head>
  <body>
    <?php require_once('../Views/header.php') ?>

    <div class="wrap_bg ma0 wrap_talkspace">
      <div class="line">
        <h1 class="title talkspace_title">TALK SPACE</h1>

        <div class="flex flex_between">
          <div class="user">
            <!-- TALKER -->
            <img src="<?= h($_SESSION['TalkSpace']['talker']['img_path']) ?>" alt="相談者画像" class="talkspace_img">
            <span><?= h($_SESSION['TalkSpace']['talker']['nickname']) ?></span>
          </div>

          <div class="talkspace">
              <div class="bord ma0"></div>
          </div>

          <div class="user">
            <!-- STAFF -->
            <img src="<?= h($_SESSION['TalkSpace']['staff']['img_path']) ?>" alt="スタッフ画像" class="talkspace_img">
            <span><?= h($_SESSION['TalkSpace']['staff']['nickname']) ?></span>
          </div>
        </div>

        <div class="inputmessage">
          <p class="error" id='errormessage'></p>
          <form action="" method="post" id="message_form" enctype="multipart/form-data">
            <?php if (isset($_SESSION['anonymous'])): ?>
              <input type="hidden" name="view_name" value="匿名相談者" id="view_name">
              <?php else: ?>
                <input type="hidden" name="view_name" value="0" id="view_name">
            <?php endif; ?>
            <textarea name="message" rows="2" placeholder="投稿メッセージを入力してください" id="textmessage"></textarea>
            <input type="hidden" name="bord_id" value="<?= h($_SESSION['TalkSpace']['bordId']) ?>" id="bord_id">
            <input type="hidden" name="user_id" value="<?= h($_SESSION['User']['id']) ?>" id="user_id">

              <input type="hidden" name="talkername" value="<?= h($_SESSION['TalkSpace']['talker']['nickname']) ?>">

            <input type="hidden" name="action" value="send">
            <input type="hidden" name="length" id="length">
            <button type="button" name="sendmessage" class="common_btn sendmessage_btn" onclick="$('#errormessage').empty(); WriteTalkMessage(); return false;"><i class="far fa-paper-plane"></i></button>
          </form>
        </div>

      </div>
    </div>

    <?php require_once('../Views/footer.php'); ?>
  </body>
</html>
