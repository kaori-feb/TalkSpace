<?php
session_start();
require_once(ROOT_PATH .'Controllers/UserController.php');
require_once(ROOT_PATH .'Controllers/BordController.php');
$user = new UserController();
$bord = new BordController();
$user->xss();
if (isset($_POST['delfav_btn'])) {
  $user->delfav($_POST);
  $_POST=[];
}
$list=$user->favuserList();


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
    <title>悩み相談SPACE「」：お気に入りスタッフ一覧</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/favorite_ajax.js"></script>
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
      <a href="/users/favoriteuser.php" class="menu_label menu_current">
      お気に入り</a>
      <a href="/users/myprofile.php" class="menu_label">
      プロフィール</a>
    </menu>


    <div class="wrap_bg ma0 tab_content">
      <div class="line">
        <h1>お気に入りスタッフ</h1>
        <div class="explain">
          <p>
            お気に入りに設定したスタッフが表示されます。<br>
            「相談画面へ」ボタンは相談スタッフが相談受付中の場合にのみ選択可能となります。
          </p>
        </div>
        <?php if (!empty($list['list'])): ?>
          <div class="flex fav_wrap">
            <?php foreach ($list['list'] as $column): ?>
            <div class="staff_box">
              <?php if ($column['id']!=$_SESSION['User']['id']): ?>
                <?php
                $favflg=$user->favBtnflg($column['id'],$_SESSION['User']['id']); ?>
                <form action="" method="post">
                  <input type="hidden" name="favId" value="<?= h($column['id']) ?>">
                  <input type="hidden" name="user_id" value="<?= h($_SESSION['User']['id']) ?>">
                  <button type="submit" class="delete_btn" name="delfav_btn">
                    解除
                  </button>
                </form>
            <?php endif; ?>

              <div class="flex flex_around aligin_center">
                <img src="<?= h($column['img']) ?>" alt="スタッフ：<?= h($column['name']) ?>" class="fav_img">

                <div class="fav_table">
                  <table class="ma0">
                    <tr>
                      <th>名前：</th>
                      <td><a href="/users/profile.php?user=<?= h($column['id']) ?>" class="name_limit"><?= h($column['name']) ?></a></td>
                    </tr>
                    <tr>
                      <th>性別：</th>
                      <td>
                        <?php if($column['gender_open']!=0): ?>
                        <?= h($column['output_gender']) ?>
                        <?php else: ?>
                        非公開
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <th>出身地：</th>
                      <td>
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
                      <th>居住地：</th>
                      <td>
                        <?php if ($column['living_place_open']!=0 && $column['living_place']!="その他"): ?>
                          <?= h($column['living_place']) ?>
                        <?php elseif($column['living_place']==="その他"): ?>
                          <?= h($column['living_place2']) ?>
                        <?php else: ?>
                        非公開
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <th rowspan="2">対応時間：</th>
                      <td class="center">
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
                    <tr>
                      <td class="center">
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
                  </table>
                </div>

              </div>

              <?php
              $result=$bord->canTalk($column['id']);
            // var_dump($result);
               ?>
               <?php if (empty($result)): ?>
                 <div class="center">
                   <button type="button" name="disabled" class="disabled_btn">相談画面へ</button>
                 </div>
               <?php elseif($result['talker_id']!=null && $result['talker_id']!=$_SESSION['User']['id'] && $result['is_finished']==null): ?>
               <div class="center">
                 <button type="button" name="disabled" class="disabled_btn">相談対応中</button>
               </div>
               <?php elseif($column['id'] == $_SESSION['User']['id']): ?>
                 <div class="center">
                   <button type="button" name="disabled" class="disabled_btn">相談画面へ</button>
                 </div>
               <?php elseif($result['talker_id']==null || $result['talker_id']==$_SESSION['User']['id'] && $result['is_finished']==null): ?>
                  <form action="/bords/talkspace.php" method="post" target="_blank">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="staff_id" value="<?= h($column['id']) ?>">
                    <input type="hidden" name="talker_id" value="<?= h($_SESSION['User']['id']) ?>">
                    <input type="hidden" name="bord_id" value="<?= h($result['id']) ?>">
                    <button type="submit" name="to_talkspace" class="navy_btn favorite_staff_btn">相談画面へ</button>
                  </form>
               <?php endif; ?>

            </div>
          <?php endforeach; ?>
          </div>
          <?php else: ?>
            <div class="complete flex aligin_center flex_around">
                <p class="complete_message">登録されていません。</p>
            </div>
        <?php endif; ?>


      </div>
    </div>


    <?php require_once('../Views/footer.php'); ?>
  </body>
</html>
