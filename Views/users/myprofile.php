<?php
session_start();
require_once(ROOT_PATH .'Controllers/UserController.php');
require_once(ROOT_PATH .'Controllers/BordController.php');
$user = new UserController();
$bord = new BordController();
$user->xss();
$column=$user->myprofile();

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
    <title>悩み相談SPACE「」：マイプロフィール</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
  </head>
  <body>
    <?php require_once('../Views/header.php') ?>

    <div class="home_header ma0">
      <?php if (isset($_SESSION['anonymous'])): ?>
        <p>ようこそ「ゲスト」さん</p>
        <?php else: ?>
          <p>ようこそ「<?= h($_SESSION['User']['nickname']) ?>」さん</p>
      <?php endif; ?>
    </div>

    <menu class="menu_wrap ma0">
      <a href="/users/index.php" class="menu_label">
      スタッフ検索</a>
      <a href="/users/talkmemory.php" class="menu_label">
      過去の相談</a>
      <a href="/users/favoriteuser.php" class="menu_label">
      お気に入り</a>
      <a href="/users/myprofile.php" class="menu_label menu_current">
      プロフィール</a>
    </menu>


    <div class="wrap_bg ma0 tab_content">
      <div class="line">
        <div class="prof_wrap">
          <h1>あなたのプロフィール</h1>
          <div class="home_content">
            <form action="/users/updateprofile.php" method="post" class="up_prof_form">
              <input type="hidden" name="myid" value="<?= h($_SESSION['User']['id']) ?>">
              <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
              <button type="submit" name="go_up_prof" class="navy_btn">プロフィールを編集する</button>
            </form>

            <div class="flex flex_around profile">
              <div class="profimg">
                <img src="<?= h($column['img_path']) ?>" alt="<?= h($column['nickname']) ?>" class="ma0 profile_img home_profimg">
                <dl class="dl_home_profimg ma0">
                  <dt>プロフィール画像:</dt>
                  <dd>プロフィールを編集するボタンからその他の画像を選択することが出来ます</dd>
                </dl>
              </div>

              <table class="profile_data myprofile">
                <tr>
                  <th colspan="3">一言コメント</th>
                </tr>
                <tr>
                  <td colspan="3" class="myprof_comment">
                      <?= nl2br(h($column['comment'])) ?>
                  </td>
                </tr>
                <tr>
                  <th>ニックネーム：</th>
                  <td colspan="2">
                    <?= h($column['nickname']) ?>
                  </td>
                </tr>
                <tr>
                  <th>性別：</th>
                  <td colspan="2">
                    <?php if($column['gender_open']!=0): ?>
                    <?= h($column['output_gender']) ?>
                    <?php else: ?>
                    非公開
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <th>出身地</th>
                  <td colspan="2">
                    <?php if ($column['birth_place_open']!=0 && $column['birth_place']!="その他"): ?>
                    <?= h($column['birth_place']) ?>
                    <?php elseif($column['birth_place']==="その他"): ?>
                      <?= h($column['birth_place2']) ?>
                    <?php else: ?>
                    非公開
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <th>居住地</th>
                  <td colspan="2">
                    <?php if ($column['living_place_open']!=0 && $column['living_place']!="その他"): ?>
                      <?= h($column['living_place']) ?>
                    <?php elseif($column['living_place']==="その他"): ?>
                      <?= h($column['living_place2']) ?>
                    <?php else: ?>
                    非公開
                    <?php endif; ?>
                  </td>
                </tr>

                <?php if ($column['role_id']=='2'): ?>
                <tr>
                  <th rowspan="3">対応可能時間：</th>
                </tr>
                  <tr class="sp-tableinline">
                  <td style="text-align:center;">
                    1
                  </td>
                  <td>
                    <?php
                    if ($column['free_time1']<10) {
                      print '0'.h($column['free_time1']).':00 ～';
                    }else{
                      print h($column['free_time1']).':00 ～';
                    }
                     ?>
                     <?php
                     if ($column['free_time1']<9) {
                       print '0'.h($column['free_time1']+1).':00';
                     }else if ($column['free_time1']==24) {
                       print '01:00';
                     }else {
                       print h($column['free_time1']+1).':00';
                     }
                      ?>
                  </td>
                </tr>
                <tr class="sp-tableinline">
                  <td style="text-align:center;">
                    2
                  </td>
                  <td>
                    <?php
                    if ($column['free_time2']<10) {
                      print '0'.h($column['free_time2']).':00 ～';
                    }elseif ($column['free_time2']==99) {
                      print '--';
                    }else{
                      print h($column['free_time2']).':00 ～';
                    }
                     ?>
                     <?php
                     if ($column['free_time2']<9) {
                       print '0'.h($column['free_time2']+1).':00';
                     }else if ($column['free_time2']==24) {
                       print '01:00';
                     }else if ($column['free_time2']==99) {
                       print ': --';
                     }else{
                       print h($column['free_time2']+1).':00';
                     }
                      ?>
                  </td>
                </tr>
              <?php endif; ?>

              </table>
            </div>

          </div>
        </div>
      </div>
    </div>


    <?php require_once('../Views/footer.php'); ?>
  </body>
</html>
