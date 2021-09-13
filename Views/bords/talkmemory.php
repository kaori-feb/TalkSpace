<?php
session_start();
require_once(ROOT_PATH .'Controllers/BordController.php');
$bord = new BordController();
$bord->xss();
$talkuser=$bord->getUsersData();
 ?>
<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>相談メモリー：悩み相談SPACE「」</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/talkspace.js"></script>
  </head>
  <body>
    <?php require_once('../Views/header.php') ?>
    <div class="home_header ma0">
      <p>ようこそ「<?= h($_SESSION['User']['nickname']) ?>」さん</p>
    </div>

    <menu class="menu_wrap ma0">
      <a href="/users/index.php" class="menu_label">
      スタッフ検索</a>
      <a href="/users/talkmemory.php" class="menu_label menu_current">
      過去の相談</a>
      <a href="/users/favoriteuser.php" class="menu_label">
      お気に入り</a>
      <a href="/users/myprofile.php" class="menu_label">
      プロフィール</a>
    </menu>


    <div class="wrap_bg ma0 wrap_talkspace">
      <div class="line">
        <h1 class="title talkspace_title">TALK MEMORY</h1>

        <div class="flex flex_between">
          <div class="user">
            <!-- TALKER -->
            <img src="<?= h($talkuser['talker']['img_path']) ?>" alt="相談者画像" class="talkspace_img">
            <span><?= h($talkuser['talker']['nickname']) ?></span>
          </div>

          <?php
          if (isset($_GET['b'])) {
            $result=$bord->getThisBordmessage($_GET);
          }?>
          <div class="talkspace">
              <div class="bord ma0">
                <?php $length=$result['length']-1; ?>
                <?php for($i=0; $i < $length; $i++): ?>
                  <?php if($result[$i]['user_id']==$result[$i]['talkerId']): ?>
                      <div class='talker'>
                        <p class='nickname'><?= h($result[$i]['talker']) ?></p>
                        <div class='ballon ballon_left'>
                          <?= nl2br(h($result[$i]['message'])) ?>
                        </div>
                      </div>
                    <?php elseif($result[$i]['user_id']==$result[$i]['staffId']): ?>
                      <div class='staff'>
                        <p class='nickname'><?= h($result[$i]['staff']) ?></p>
                        <div class='ballon ballon_right'>
                          <?= nl2br(h($result[$i]['message'])) ?>
                        </div>
                      </div>
                  <?php endif; ?>
                <?php endfor; ?>
              </div>
          </div>

          <div class="user">
            <!-- STAFF -->
            <img src="<?= h($talkuser['staff']['img_path']) ?>" alt="スタッフ画像" class="talkspace_img">
            <span><?= h($talkuser['staff']['nickname']) ?></span>
          </div>
        </div>

      </div>
    </div>

    <?php require_once('../Views/footer.php'); ?>
  </body>
</html>
