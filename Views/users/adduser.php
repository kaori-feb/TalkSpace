<?php
session_start();
require_once(ROOT_PATH.'Controllers/UserController.php');
$user = new UserController();
$user->xss();
$setData = $user->setData();
$output = $user->output();

if ($_SESSION['User']['role_id']!=3) {
  $_SESSION=[];
}
$toke_byte=openssl_random_pseudo_bytes(16);
$csrf_token=bin2hex($toke_byte);
$_SESSION['csrf_token'] = $csrf_token;
 ?>

<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>悩み相談SPACE「」：新規ユーザー登録</title>
    <link rel="stylesheet" href="/css/login.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/talkspace.js"></script>
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
              <li class="current">ユーザー情報の入力</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li>入力内容の確認</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li>登録完了（ホームへ）</li>
            </ul>
          </div>
          <p class="text">
            <span class="red">*</span>マークがついている項目は入力必須です。<br>
            各項目はログイン後にマイページから変更することができます。<br>
            ※「公開する」にチェックを入れると他ユーザーが閲覧可能なプロフィールに表示されます。
          </p>
          <div class="address">
            <form action="adduserconfirm.php" method="post">
              <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
              <h3>基本情報</h3>
              <div class="form_container">
                <table>

                  <?php if(isset($_POST['adduser_fromadmin'])): ?>
                    <input type="hidden" name="admin" value="admin">
                  <tr>
                      <th>区分<span class="red">*</span>：</th>
                      <td>
                        <label for="talker">
                          <input type="radio" name="role_id" value="1" id="talker" required
                          <?php if(isset($setData['role_id']) && $setData['role_id']==1) echo 'checked' ?>>
                          相談者
                        </label>
                        <label for="staff">
                          <input type="radio" name="role_id" value="2" id="staff"
                          <?php if(isset($setData['role_id']) && $setData['role_id']==2) echo 'checked' ?>>
                          相談スタッフ
                        </label>
                        <label for="admin">
                          <input type="radio" name="role_id" value="3" id="admin"
                          <?php if(isset($setData['role_id']) && $setData['role_id']==3) echo 'checked' ?>>
                          管理者
                        </label>
                      </td>
                    </tr>
                  <?php elseif(isset($_POST['addstaff'])): ?>
                    <input type="hidden" name="role_id" value="2">
                  <?php elseif(isset($_POST['addtalker'])): ?>
                    <input type="hidden" name="role_id" value="1">
                  <?php endif; ?>

                  <tr>
                    <th>ニックネーム<span class="red">*</span>：</th>
                    <td>
                      <input type="text" name="nickname" value="<?= h($setData['nickname']) ?>" placeholder="ニックネームを入力してください" required>
                    </td>
                  </tr>
                  <tr>
                    <th>性別<span class="red">*</span>：</th>
                    <td>
                      <label for="female">
                        <input type="radio" name="gender" value="1" id="female" required
                        <?php if(isset($setData['gender']) && $setData['gender']==1) echo 'checked' ?>>
                        女性　
                      </label>
                      <label for="male">
                        <input type="radio" name="gender" value="2" id="male"
                        <?php if(isset($setData['gender']) && $setData['gender']==2) echo 'checked' ?>>
                        男性　
                      </label>
                      <label for="x-gender">
                        <input type="radio" name="gender" value="3" id="x-gender"
                        <?php if(isset($setData['gender']) && $setData['gender']==3) echo 'checked' ?>>
                        その他　
                      </label>
                      <label for="gender_open">
                        <input type="checkbox" name="gender_open" value="1" id="gender_open"
                        <?php if(isset($setData['gender_open']) && $setData['gender_open']=1) echo 'checked' ?>>
                        公開する
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <th>メールアドレス<span class="red">*</span>：</th>
                    <td><input type="text" name="mail" value="<?= h($setData['mail']) ?>" placeholder="メールアドレスを入力してください"></td>
                  </tr>
                  <tr class="adminCheck">
                  <th>出身地：</th>
                  <td>
                  <select name="birth_place">
                    <option value="top" disabled
                    <?php if(empty($setData['birth_place'])): ?>
                      selected
                    <?php endif; ?>
                    >選択してください</option>
                    <?php foreach ($output['pref'] as $key => $value): ?>
                      <optgroup label="<?= $key ?>">
                        <?php for ($i=0; $i < count($output['pref'][$key]); $i++){
                          if($setData['birth_place'] === $value[$i]){
                            echo '<option value="'.$value[$i].'" selected>'.$value[$i].'</option>';
                          }else{
                            echo '<option value="'.$value[$i].'">'.$value[$i].'</option>';
                          }
                        }?>
                      </optgroup>
                    <?php endforeach; ?>
                  </select>　
                  <label for="birth_place_open">
                    <input type="checkbox" name="birth_place_open" value="1" id="birth_place_open" <?php if(isset($setData['birth_place_open']) && $setData['birth_place_open']==1) echo 'checked' ?>>
                    公開する
                  </label>
                  </td>
                </tr>
                <tr class="adminCheck">
                  <th>出身地(その他)：</th>
                  <td>
                      <input type="text" name="birth_place2" value="<?= h($setData['birth_place2']) ?>" placeholder="その他出身地を入力してください(都市程度まで)" disabled>
                    </td>
                </tr>
                <tr class="adminCheck">
                <th>居住地：</th>
                <td>
                <select name="living_place">
                  <option value="top" disabled
                  <?php if(empty($setData['living_place'])): ?>
                    selected
                  <?php endif; ?>
                  >選択してください</option>
                  <?php foreach ($output['pref'] as $key => $value): ?>
                    <optgroup label="<?= $key ?>">
                      <?php for ($i=0; $i < count($output['pref'][$key]); $i++){
                        if($setData['living_place'] === $value[$i]){
                          echo '<option value="'.$value[$i].'" selected>'.$value[$i].'</option>';
                        } else {
                          echo '<option value="'.$value[$i].'">'.$value[$i].'</option>';
                        }
                      }?>
                    </optgroup>
                  <?php endforeach; ?>
                </select>　
                <label for="living_place_open">
                  <input type="checkbox" name="living_place_open" value="1" id="living_place_open" <?php if(isset($setData['living_place_open']) && $setData['living_place_open']==1) echo 'checked' ?>>
                  公開する
                </label>
                </td>
              </tr>
              <tr class="adminCheck">
                <th>居住地(その他)：</th>
                <td><input type="text" name="living_place2" value="<?= h($setData['living_place2']) ?>" placeholder="その他居住地を入力してください(都市程度まで)" disabled></td>
              </tr>

              <?php if(isset($_POST['addstaff']) || isset($_POST['adduser_fromadmin'])): ?>
              <tr class="staffCheck">
                <th rowspan="2">対応可能時間<span class="red">*</span>：</th>
                <td>
                  <select class="" name="free_time1" required>
                  <?php for($i=0; $i<25; $i++): ?>
                    <?php if($setData['free_time1']==$i && $setData['free_time1']!=NULL): ?>
                      <option value="<?= h($setData['free_time1']) ?>" selected><?= h($setData['free_time1']) ?></option>
                    <?php elseif($i<=9): ?>
                    <option value="<?php echo '0'.$i ?>"><?php echo '0'.$i ?></option>
                    <?php else: ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endif; ?>
                  <?php endfor; ?>
                  </select>
                  :00 ～
                  <span class="endtime1">01</span>:00<span class="red">*</span>
                  <p class="error">(!) 相談スタッフの場合、一つ目の対応可能時間の設定は必須です</p>
                </td>
              </tr>
              <tr class="staffCheck">
                <td>
                  <select class="" name="free_time2">
                  <?php for($i=0; $i<25; $i++): ?>
                    <?php if($setData['free_time2']==$i && $setData['free_time2']!=NULL): ?>
                      <option value="<?= h($setData['free_time2']) ?>" selected><?= h($setData['free_time2']) ?></option>
                    <?php elseif($i<=9): ?>
                    <option value="<?php echo '0'.$i ?>"><?php echo '0'.$i ?></option>
                    <?php else: ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endif; ?>
                  <?php endfor; ?>
                  </select>
                  :00 ～
                  <span class="endtime2">01</span>:00　

                  <label for="use_time2">
                    <input type="checkbox" name="use_time2" value="1" id="use_time2" <?php if(isset($_POST['use_time2']) && $_POST['use_time2']=1) echo 'checked' ?>>設定しない
                  </label>
                </td>
              </tr>
            <?php endif; ?>

              </table>
              <div class="staffCheck notes">
                ※対応可能時間は相談者から予約を受けた際に利用します。<br>
                　相談時間は60分を目安としています。<br>
                　安定してログインできる時間をご指定ください。
              </div>
              </div>



            <h3>パスワード設定</h3>
            <div class="form_container">
              <table>
                <tr>
                  <th>パスワード<span class="red">*</span>：</th>
                  <td>
                    <input type="password" name="password" value="" placeholder="任意のパスワードを入力してください" required>(半角英数字)
                    <span class="toggle_pass eye"></span>
                  </td>
                </tr>
              </table>
            </div>

            <h3>一言コメント</h3>
            <div class="form_container">
              <table>
                <tr>
                  <th>一言コメント：</th>
                  <td>
                    <textarea name="comment" rows="8" cols="80" class="comment" placeholder="よろしくお願いいたします。"><?= h($setData['comment']) ?></textarea>
                  </td>
                </tr>
              </table>
            </div>

            <div class="btn_container center">
              <button type="submit" name="adduser" class="common_btn">送 信</button>
            </div>
            </form>
          </div>

        </div>
      </div>
    </div>

    <?php require_once('../Views/footer.php'); ?>

  </body>
</html>
