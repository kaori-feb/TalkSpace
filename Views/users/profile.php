<?php
session_start();
require_once(ROOT_PATH .'Controllers/UserController.php');
require_once(ROOT_PATH .'Controllers/BordController.php');
$user = new UserController();
$bord = new BordController();
$user->xss();
$column=$user->myprofile();
 ?>
<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>プロフィールページ：悩み相談SPACE「」</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.css" type="text/css" media="all">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/favorite_ajax.js"></script>
  </head>
  <body>
    <?php require_once('../Views/header.php') ?>

    <div class="wrap_bg ma0 clearfix">
      <div class="line">
        <div class="flex flex_between">
          <h1 class="title"><?= h($column['nickname']) ?>さんのプロフィール <i class="far fa-id-card"></i></h1>

          <?php if ($_SESSION['User']['nickname']==$column['nickname']): ?>
            <?php
            $toke_byte=openssl_random_pseudo_bytes(16);
            $csrf_token=bin2hex($toke_byte);
            $_SESSION['csrf_token'] = $csrf_token;
             ?>
            <form action="/users/updateprofile.php" method="post" class="">
              <input type="hidden" name="myid" value="<?= h($_SESSION['User']['id']) ?>">
              <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
              <button type="submit" name="go_up_prof" class="navy_btn">プロフィールを編集する</button>
            </form>
        <?php elseif ($_SESSION['User']['role_id']!=3): ?>
          <?php if ($column['role_id']==2 && $column['id']!=$_SESSION['User']['id']): ?>
          <?php
          $result=$bord->canTalk($column['id']);
           ?>
           <?php if (empty($result)): ?>
             <div class="center">
               <button type="button" name="disabled" class="disabled_btn userprofile_btn">相談画面へ</button>
             </div>
           <?php elseif($result['talker_id']!=null && $result['talker_id']!=$_SESSION['User']['id'] && $result['is_finished']==null): ?>
           <div class="center">
             <button type="button" name="disabled" class="disabled_btn userprofile_btn">相談対応中</button>
           </div>
           <?php elseif($result['talker_id']==null || $result['talker_id']==$_SESSION['User']['id'] && $result['is_finished']==null): ?>
              <form action="/bords/talkspace.php" method="post" target="_blank">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="staff_id" value="<?= h($column['id']) ?>">
                <input type="hidden" name="talker_id" value="<?= h($_SESSION['User']['id']) ?>">
                <input type="hidden" name="bord_id" value="<?= h($result['id']) ?>">
                <button type="submit" name="to_talkspace" class="navy_btn favorite_staff_btn userprofile_btn">相談画面へ</button>
              </form>
           <?php endif; ?>
           <?php endif; ?>
         <?php endif; ?>

        </div>

        <div class="profile flex flex_around aligin_center">

          <div class="profimg">
            <img src="<?= h($column['img_path']) ?>" alt="<?= h($column['nickname']) ?>" class="ma0 profile_img">

          <?php if ($_SESSION['User']['role_id']!=3): ?>
            <?php if ($column['role_id']==2 && $column['id']!=$_SESSION['User']['id']): ?>
            <!-- 相談スタッフのプロフのみ表示 -->
            <?php
            $favflg=$user->favBtnflg($column['id'],$_SESSION['User']['id']); ?>
            <form action="" method="post" class="center">
              <input type="hidden" name="favId" value="<?= h($column['id']) ?>" id="favId">
              <input type="hidden" name="user_id" value="<?= h($_SESSION['User']['id']) ?>" id="user_id">
              <button type="submit" name="userprof_fav" class="userprof_fav ma0 mt10
              <?php
                if (empty($favflg)) {
                  echo 'gray_btn';
                }
                else{
                  echo 'prof_favorite_btn';
                } ?>"><i class="far fa-star rotate"></i> お気に入り</button>
            </form>
          <?php endif; ?>
        <?php endif; ?>


            <table class="profile_data userprof_comment">
              <tr>
                <th colspan="3">一言コメント</th>
              </tr>
              <tr>
                <td colspan="3" class="myprof_comment">
                  <?= nl2br(h($column['comment'])) ?>
                </td>
              </tr>
            </table>
          </div>

          <table class="profile_data">
            <?php if ($_SESSION['User']['role_id']==3): ?>
              <tr>
                <th>区分： </th>
                <td colspan="2">
                  <?= h($column['output_role']) ?>
                </td>
              </tr>
            <?php endif; ?>
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
              <th rowspan="2">対応可能時間：</th>
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
            <tr>
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
    <?php require_once('../Views/footer.php'); ?>
  </body>
</html>
