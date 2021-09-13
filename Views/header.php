<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/talkspace.js"></script>
  </head>
  <body>

    <?php if (isset($_POST['anonymous']) || isset($_SESSION['anonymous'])): ?>
      <!-- 非ログイン -->
      <div class="clearfix common_header">
      <header>
        <div class="logo">
          <?php if (isset($_SESSION['TalkSpace'])): ?>
            <a href="/users/index.php">
            <img src="/img/Logowords.png" alt="悩み相談SPACE「」"></a>
            <?php else: ?>
            <a href="/users/index.php">
            <img src="/img/Logowords.png" alt="悩み相談SPACE「」"></a>
          <?php endif; ?>
        </div>
      </header>
    </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['User']['role_id']) && $_SESSION['User']['role_id']==1): ?>
      <!-- ログイン相談者 -->
      <div class="clearfix common_header">
        <header>
          <div class="logo">
            <a href="/users/index.php">
            <img src="/img/Logowords.png" alt="悩み相談SPACE「」"></a>
          </div>
        </header>

        <!-- ログインしていたら表示 -->
        <?php if (isset($_SESSION['User']['role_id']) && $_SESSION['User']['role_id']==1): ?>
          <div class="menu">
            <ul>
              <li>
                <a href="/users/profile.php?user=<?= h($_SESSION['User']['id']) ?>">
                  <i class="far fa-id-card"></i> マイプロフィール
                </a>
              </li>
              <li>
                <a href="/users/index.php">
                  <i class="fas fa-home"></i> マイページ
                </a>
              </li>
              <li>
                <a href="/users/login.php">
                  <i class="fas fa-sign-out-alt"></i> ログアウト
                </a>
              </li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>


    <!-- 相談スタッフ -->
    <?php if (isset($_SESSION['User']['role_id']) && $_SESSION['User']['role_id']==2): ?>
    <?php
    if (isset($_POST['finishbord_id'])) {
      $bord->is_finished();
      $_SESSION['Bord']=[];
    }
    $post_bordflag_token=isset($_POST["bordflag_token"]) ? $_POST["bordflag_token"] : "";
    $session_post_bordflag_token=isset($_SESSION["bordflag_token"]) ? $_SESSION["bordflag_token"] : "";
    unset($_SESSION['bordflag_token']);
    if($post_bordflag_token != "" && $post_bordflag_token == $session_post_bordflag_token){
      if (isset($_POST["bordflag_token"])) {
        $_SESSION['talkspace_flg'] = $_POST['talkspace_flg'];
        $user->insert_bord();
        unset($_POST);
      }
    }
    // 相談板登録時二重投稿阻止用トークン
    $bordflag_token = uniqid('', true);
    $_SESSION['bordflag_token'] = $bordflag_token;

     ?>
      <div class="clearfix staff_header">
        <header>
          <div class="logo">
            <a href="index.php">
            <img src="/img/Logowords.png" alt="悩み相談SPACE「」"></a>
          </div>
        </header>

        <div class="menu">
          <ul>
            <li>
              <a href="/users/profile.php?user=<?= h($_SESSION['User']['id']) ?>">
                <i class="far fa-id-card"></i> マイプロフィール
              </a>
            </li>
            <li>
              <a href="/users/index.php">
                <i class="fas fa-home"></i> マイページ
              </a>
            </li>
            <li>
              <a href="/users/login.php">
                <i class="fas fa-sign-out-alt"></i> ログアウト
              </a>
            </li>
          </ul>
        </div>

        <?php $result=$bord->canTalk($_SESSION['User']['id']); ?>
        <div class="talkon_btn_container">
          <?php if($result==false || $result['is_finished']!=NULL): ?>
          <form action="" method="post">
            <input type="hidden" name="staff_id" value="<?= h($_SESSION['User']['id']) ?>">
            <input type="hidden" name="talkspace_flg" value="1">
            <input type="hidden" name="bordflag_token" value="<?= $bordflag_token ?>">
            <button type="submit" name="talk_on" class="common_btn home_header_btn">相談受付</button>
          </form>
        <?php else: ?>
          <form action="" method="post">
            <?php if (isset($t_csrf_token)): ?>
              <input type="hidden" name="csrf_token" value="<?= h($t_csrf_token) ?>">
            <?php endif; ?>
            <input type="hidden" name="finishbord_id" value="<?= h($result['id']) ?>">
            <button type="submit" name="is_finished" class="common_btn home_header_btn is_finished">相談終了</button>
          </form>
          <?php endif; ?>
        </div>
      </div>
<?php if(!isset($_POST['bord_id'])): ?>
          <?php if (isset($result['talker_id']) && empty($result['is_finished'])): ?>
          <form action="/bords/talkspace.php" method="post" target="_blank">
            <?php if (isset($csrf_token)): ?>
              <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <?php endif; ?>
            <input type="hidden" name="staff_id" value="<?= h($result['staff_id']) ?>">
            <input type="hidden" name="talker_id" value="<?= h($result['talker_id']) ?>">
            <input type="hidden" name="bord_id" value="<?= h($result['id']) ?>">
            <button type="submit" name="to_talkspace" class="common_btn home_header_btn to_talkspace">相談画面</button>
          </form>
        <?php endif; ?>

<?php endif; ?>


    <?php endif; ?>


    <?php if (isset($_SESSION['User']['role_id']) && $_SESSION['User']['role_id']==3): ?>
      <!-- 管理者 -->
      <div class="clearfix admin_header">
        <header>
          <div class="logo">
            <a href="/admins/index.php">
            <img src="/img/Logowords.png" alt="悩み相談SPACE「」"></a>
          </div>
        </header>

        <div class="menu">
          <ul>
            <li>
              <a href="/users/profile.php?user=<?= h($_SESSION['User']['id']) ?>">
                <i class="far fa-id-card"></i> マイプロフィール
              </a>
            </li>
            <li>
              <a href="/admins/index.php">
                <i class="fas fa-home"></i> 管理トップ
              </a>
            </li>
            <li>
              <a href="/users/login.php">
                <i class="fas fa-sign-out-alt"></i> ログアウト
              </a>
            </li>
          </ul>
        </div>
      </div>
    <?php endif; ?>

  </body>
</html>
