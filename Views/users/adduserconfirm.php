<?php
session_start();
require_once(ROOT_PATH.'Controllers/UserController.php');
$user = new UserController();
$csrf_token = $user->csrf();
$user->xss();
$user->setData();
$output = $user->output();
$valid = $user->valid($_POST);

// 二重送信防止用トークンの発行
$doublesubmit_token = uniqid('', true);
//トークンをセッション変数にセット
$_SESSION['doublesubmit_token'] = $doublesubmit_token;
 ?>

<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>悩み相談SPACE「」：入力内容の確認</title>
    <link rel="stylesheet" href="/css/login.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  </head>
  <body>
    <div class="header_logo">
      <?php if ($_SESSION['User']['role_id']!=3): ?>
        <a href="login.php">
        <img src="/img/Logowords.png" alt="悩み相談SPACE「」"></a>
      <?php else: ?>
        <a href="/admins/index.php">
        <img src="/img/Logowords.png" alt="悩み相談SPACE「」"></a>
      <?php endif; ?>
    </div>
    <div class="wrap_bg ma0">
      <div class="line">
        <div class="content ma0">
          <h1>新規ユーザー登録 <i class="fas fa-edit"></i></h1>
          <div class="step ma0">
            <ul class="flex flex_center">
              <li>ユーザー情報の入力</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li class="current">入力内容の確認</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li>登録完了（ホームへ）</li>
            </ul>
          </div>
          <p class="text">
            <span class="red">*</span>マークがついている項目は入力必須です。<br>
            各項目はログイン後にマイページから変更することができます。
          </p>
          <div class="address">
            <form action="complete.php" method="post" id="adduserconfirm">
              <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
              <input type="hidden" name="doublesubmit_token" value="<?= $doublesubmit_token ?>">
              <?php if (isset($_POST['up_prof'])): ?>
                <input type="hidden" name="id" value="<?= h($_POST['id']) ?>">
              <?php endif; ?>
              <h3>基本情報</h3>
              <div class="form_container">
                <table>
                  <?php if(isset($_POST['role_id']) && $_POST['role_id'] == 3): ?>
                    <tr>
                      <th>区分<span class="red">*</span>：</th>
                      <td>
                        <?php if(isset($valid['role_id'])): ?>
                          <p class="error"><?= $valid['role_id'] ?></p>
                        <?php else: ?>
                          <?= $output['role_id'] ?>
                          <input type="hidden" name="role_id" value="<?= h($_POST['role_id']) ?>">
                        <?php endif; ?>
                      </td>
                      </tr>
                    <?php else: ?>
                      <input type="hidden" name="role_id" value="<?= h($_POST['role_id']) ?>">
                  <?php endif; ?>
                  <tr>
                    <th>ニックネーム<span class="red">*</span>：</th>
                    <td>
                      <?= h($_POST['nickname']) ?>
                      <?php if(isset($valid['nickname'])): ?>
                        <p class="error"><?= $valid['nickname'] ?></p>
                      <?php else: ?>
                        <input type="hidden" name="nickname" value="<?= h($_POST['nickname']) ?>">
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <th>性別<span class="red">*</span>：</th>
                    <td>
                      <?php if(isset($valid['gender'])): ?>
                        <p class="error"><?= $valid['gender'] ?></p>
                      <?php else: ?>
                      <?= $output['gender'] ?>
                      <input type="hidden" name="gender" value="<?= h($_POST['gender']) ?>">
                      ：
                      <?php endif; ?>
                      <?php if(isset($_POST['gender_open']) && $_POST['gender_open']==1): ?>
                      公開する
                      <input type="hidden" name="gender_open" value="<?= h($_POST['gender_open']) ?>">
                      <?php else: ?>
                      公開しない
                      <input type="hidden" name="gender_open" value="0">
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <th>メールアドレス<span class="red">*</span>：</th>
                    <td>
                      <?= h($_POST['mail']) ?>
                      <?php if (isset($valid['mail'])): ?>
                        <p class="error"><?= $valid['mail'] ?></p>
                      <?php elseif (isset($valid['mailfind'])): ?>
                        <p class="error"><?= $valid['mailfind'] ?></p>
                      <?php else: ?>
                        <input type="hidden" name="mail" value="<?= h($_POST['mail']) ?>">
                      <?php endif; ?>
                    </td>
                  </tr>

                <?php if(isset($_POST['birth_place'])): ?>
                  <tr>
                  <th>出身地：</th>
                  <td>
                    <?= h($_POST['birth_place']) ?>
                    <input type="hidden" name="birth_place" value="<?= h($_POST['birth_place']) ?>">
                    <input type="hidden" name="birth_place2" value="0">
                    ：
                    <?php if(isset($_POST['birth_place_open']) && $_POST['birth_place_open']==1): ?>
                    公開する
                    <input type="hidden" name="birth_place_open" value="<?= h($_POST['birth_place_open']) ?>">
                    <?php else: ?>
                    公開しない
                    <input type="hidden" name="birth_place_open" value="0">
                    <?php endif; ?>
                  </td>
                </tr>
                <?php if($_POST['birth_place']=='その他'): ?>
                <tr>
                  <th>出身地(その他)：</th>
                  <td>
                    <?= h($_POST['birth_place2']) ?>
                    <?php if (isset($valid['birth_place2'])): ?>
                      <p class="error"><?= $valid['birth_place2'] ?></p>
                    <?php elseif(isset($_POST['birth_place2'])): ?>
                      <input type="hidden" name="birth_place2" value="<?= h($_POST['birth_place2']) ?>">
                    <?php endif; ?>
                  </td>
                </tr>
              <?php elseif(isset($_POST['birth_place2'])&&$_POST['birth_place']!='その他'): ?>
                <input type="hidden" name="birth_place2" value="">
              <?php endif; ?>
            <?php elseif(!isset($_POST['birth_place'])): ?>
                <!-- 出身地入力無し -->
                <input type="hidden" name="birth_place" value="0">
                <input type="hidden" name="birth_place2" value="0">
                <input type="hidden" name="birth_place_open" value="0">
            <?php endif; ?>

            <?php if(isset($_POST['living_place'])): ?>
              <tr>
                <th>居住地：</th>
                <td>
                  <?= h($_POST['living_place']) ?>
                  <input type="hidden" name="living_place" value="<?= h($_POST['living_place']) ?>">
                  <input type="hidden" name="living_place2" value="0">
                  ：
                  <?php if(isset($_POST['living_place_open']) && $_POST['living_place_open']==1): ?>
                  公開する
                  <input type="hidden" name="living_place_open" value="<?= h($_POST['living_place_open']) ?>">
                  <?php else: ?>
                  公開しない
                  <input type="hidden" name="living_place_open" value="0">
                  <?php endif; ?>
                </td>
              </tr>
              <?php if(isset($_POST['living_place']) && $_POST['living_place']=='その他'): ?>
              <tr>
                <th>居住地(その他)：</th>
                <td>
                  <?= h($_POST['living_place2']) ?>
                  <?php if (isset($valid['living_place2'])): ?>
                    <p class="error"><?= $valid['living_place2'] ?></p>
                  <?php elseif(isset($_POST['living_place2'])): ?>
                    <input type="hidden" name="living_place2" value="<?= h($_POST['living_place2']) ?>">
                  <?php endif; ?>
                </td>
              </tr>
            <?php elseif(isset($_POST['living_place2'])&&$_POST['living_place']!='その他'): ?>
              <input type="hidden" name="living_place2" value="">
              <?php endif; ?>
            <?php elseif(!isset($_POST['living_place'])): ?>
              <!-- 居住地入力無し -->
              <input type="hidden" name="living_place" value="0">
              <input type="hidden" name="living_place2" value="0">
              <input type="hidden" name="living_place_open" value="0">
            <?php endif; ?>

              <?php if($_POST['role_id']==2): ?>
              <tr>
                <th rowspan="2">対応可能時間<span class="red">*</span>：</th>
                <td>
                  <?php if (isset($_POST['free_time1'])): ?>
                    <?= h($_POST['free_time1']) ?>
                    <input type="hidden" name="free_time1" value="<?= h($_POST['free_time1']) ?>">
                  <?php else: ?>
                    00
                    <input type="hidden" name="free_time1" value="00">
                  <?php endif; ?>
                  :00 ～<?= $output['endtime1'] ?>:00
                  <?php if (isset($valid['free_time2'])): ?>
                    <p class="error"><?= $valid['free_time2'] ?></p>
                  <?php endif; ?>
                </td>
              </tr>
              <?php if(empty($_POST['use_time2'])): ?>
              <tr>
                <td>
                  <?php if (isset($_POST['free_time2'])): ?>
                  <?= h($_POST['free_time2']) ?>
                  <input type="hidden" name="free_time2" value="<?= h($_POST['free_time2']) ?>">
                  <?php else: ?>
                    00
                    <input type="hidden" name="free_time2" value="00">
                  <?php endif; ?>
                  :00 ～<?= $output['endtime2'] ?>:00
                  <?php else: ?>
                    <!-- free_time2を利用しない -->
                  <input type="hidden" name="free_time2" value="99">
                  <?php endif; ?>
                </td>
              </tr>
              <?php else: ?>
                <!-- 相談スタッフ以外 -->
                <input type="hidden" name="free_time1" value="99">
                <input type="hidden" name="free_time2" value="99">
            <?php endif; ?>
              </table>
              </div>

            <?php if (isset($valid['password'])): ?>
            <h3>パスワード設定</h3>
            <div class="form_container">
              <table>
                <tr>
                  <th rowspan="2">パスワード<span class="red">*</span>：</th>
                  <td>
                    <p class="error"><?= $valid['password'] ?></p>
                  </td>
                </tr>
                <tr>
                  <td><?= h($_POST['password']) ?></td>
                </tr>
              </table>
            </div>
          <?php else: ?>
            <?php if (!isset($_POST['up_prof'])): ?>
              <input type="hidden" name="password" value="<?= password_hash(h($_POST['password']),PASSWORD_DEFAULT) ?>">
            <?php endif; ?>
        <?php endif; ?>


            <?php if (isset($_POST['up_prof'])): ?>
              <input type="hidden" name="update_flg" value="date">
              <h3>ユーザー画像</h3>
              <div class="form_container">
                <table>
                  <tr>
                    <th rowspan="2">ユーザー画像：</th>
                    <td colspan="2">
                      <img src="<?= $output['img_path'] ?>" alt="ユーザー画像" class="ma0 profile_img">
                      <input type="hidden" name="img_path" value="<?= h($output['img_path']) ?>">
                    </td>
                  </tr>
                </table>
              </div>
              <?php else: ?>
                <input type="hidden" name="img_path" value="<?= h($output['img_path']) ?>">
            <?php endif; ?>

              <h3>一言コメント：</h3>
              <div class="form_container">
                <table>
                  <tr>
                    <th>一言コメント：</th>
                    <td>
                      <div class="check_textarea">
                        <?= nl2br(h($output['comment'])) ?>
                      </div>
                      <input type="hidden" name="comment" value="<?= h($output['comment']) ?>">
                    </td>
                  </tr>
                </table>
              </div>

            </form>

            <div class="flex btn_container flex_around sp-confirmbtn">
              <?php if (isset($_POST['up_prof'])): ?>
                <button type="submit" name="up_back" form="up_back" class="common_btn">戻 る</button>
                <?php else: ?>
                <button type="submit" name="back" form="back" class="common_btn">戻 る</button>
              <?php endif; ?>

              <?php if (empty($valid)): ?>
                <?php if (isset($_POST['up_prof'])): ?>
                  <button type="submit" name="update" class="common_btn" form="adduserconfirm">更 新</button>
                <?php else: ?>
                  <button type="submit" name="adduser" class="common_btn" form="adduserconfirm">送 信</button>
                <?php endif; ?>
              <?php endif; ?>
            </div>

            <form action="/users/adduser.php" method="post" id="back">
              <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <?php if(isset($_POST['admin'])): ?>
              <input type="hidden" name="adduser_fromadmin" value="3">
            <?php elseif($_POST['role_id']==2): ?>
              <input type="hidden" name="addstaff" value="2">
            <?php elseif($_POST['role_id']==1): ?>
              <input type="hidden" name="addtalker" value="1">
            <?php endif; ?>
              <input type="hidden" name="use_time2" value="<?= h($_POST['use_time2']) ?>">
            </form>

            <form action="/users/updateprofile.php" method="post" id="up_back">
              <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
              <input type="hidden" name="id" value="<?= h($_POST['id']) ?>">
              <input type="hidden" name="use_time2" value="<?= h($_POST['use_time2']) ?>">
            </form>

          </div>

        </div>
      </div>
    </div>

    <?php require_once('../Views/footer.php'); ?>

  </body>
</html>
