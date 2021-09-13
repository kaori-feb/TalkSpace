<?php
session_start();
require_once(ROOT_PATH .'Controllers/UserController.php');
require_once(ROOT_PATH .'Controllers/BordController.php');
$user = new UserController();
$bord = new BordController();
$csrf_token = $user->csrf();
$user->xss();

$setData = $user->setData();
$output = $user->output();
$column=$user->myprofile();
 ?>

<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>悩み相談SPACE「」：ユーザー情報の更新</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/talkspace.js"></script>
  </head>
  <body>
    <?php require_once('../Views/header.php') ?>

    <div class="wrap_bg ma0">
      <div class="line">
        <div class="content ma0">
          <h1>プロフィール情報の編集 <i class="fas fa-edit"></i></h1>
          <div class="step ma0">
            <ul class="flex flex_center">
              <li class="current">ユーザー情報の入力</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li>内容の確認</li>
              <li><i class="fas fa-caret-right"></i></li>
              <li>編集完了（プロフィールページへ）</li>
            </ul>
          </div>
          <p class="text">
            <span class="red">*</span>マークがついている項目は入力必須です。<br>
            ※「公開する」にチェックを入れると他ユーザーが閲覧可能なプロフィールに表示されます。
          </p>
          <div class="address">
            <form action="/users/adduserconfirm.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
              <input type="hidden" name="id" value="<?php if(isset($_POST['id'])){
                echo h($_POST['id']);
              } else{
                echo h($column['id']);
              } ?>">
              <h3>ユーザー画像</h3>
              <div class="form_container">
                <table>
                  <tr>
                    <th rowspan="2">ユーザー画像：</th>
                    <td colspan="2">
                      <img src="<?php if(isset($setData['img_path'])){
                        echo h($setData['img_path']);
                      } else{
                        echo h($column['img_path']);
                      } ?>" class="ma0 profile_img" id="preview">
                      <input type="hidden" name="img_path" value="" class="tempimg">
                    </td>
                  </tr>
                    <tr>
                    <td class="center">
                      <button type="button" class="naby_btn imgchoice">その他の画像を選ぶ</button>
                    </td>
                  </tr>
                </table>
              </div>

              <h3>基本情報</h3>
              <div class="form_container">
                <table>
                  <?php if ($_SESSION['User']['role_id']==3): ?>
                  <tr>
                      <th>区分<span class="red">*</span>：</th>
                      <td>
                        <input type="hidden" name="admin" value="admin">
                        <label for="talker">
                          <input type="radio" name="role_id" value="1" id="talker" required
                          <?php
                          if (isset($setData['role_id']) && $setData['role_id']==1) {
                          echo "checked";
                          }elseif($column['role_id']==1){
                          echo "checked";
                          } ?>>相談者
                        </label>
                        <label for="staff">
                          <input type="radio" name="role_id" value="2" id="staff"
                          <?php
                          if (isset($setData['role_id']) && $setData['role_id']==2) {
                          echo "checked";
                        }elseif($column['role_id']==2){
                          echo "checked";
                          } ?>>相談スタッフ
                        </label>
                        <label for="admin">
                          <input type="radio" name="role_id" value="3" id="admin"
                          <?php
                          if (isset($setData['role_id']) && $setData['role_id']==3) {
                          echo "checked";
                        }elseif($column['role_id']==3){
                          echo "checked";
                          } ?>>管理者
                        </label>
                      </td>
                    </tr>
                  <?php else: ?>
                    <input type="hidden" name="role_id" value="<?= h($column['role_id']) ?>">
                  <?php endif; ?>

                  <tr>
                    <th>ニックネーム<span class="red">*</span>：</th>
                    <td>
                      <input type="text" name="nickname" value="<?php
                      if(isset($setData['nickname'])){
                        echo h($setData['nickname']);
                      }else{
                        echo h($column['nickname']);
                      } ?>" placeholder="ニックネームを入力してください" required>
                    </td>
                  </tr>
                  <tr>
                    <th>性別<span class="red">*</span>：</th>
                    <td>
                      <label for="female">
                        <input type="radio" name="gender" value="1" id="female" required
                        <?php
                        if(isset($setData['gender']) && $setData['gender']==1){
                          echo 'checked';
                        }elseif(isset($column['output_gender']) && $column['output_gender']=='女性'){
                          echo 'checked';
                        } ?>>女性
                      </label>
                      <label for="male">
                        <input type="radio" name="gender" value="2" id="male"
                        <?php
                        if(isset($setData['gender']) && $setData['gender']==2){
                          echo 'checked';
                        }elseif(isset($column['output_gender']) && $column['output_gender']=='男性'){
                          echo 'checked';
                        } ?>>男性　
                      </label>
                      <label for="x-gender">
                        <input type="radio" name="gender" value="3" id="x-gender"
                        <?php
                        if(isset($setData['gender']) && $setData['gender']==3){
                          echo 'checked';
                        }elseif(isset($column['output_gender']) && $column['output_gender']=='その他'){
                          echo 'checked';
                        } ?>>その他　
                      </label>
                      <label for="gender_open">
                        <input type="checkbox" name="gender_open" value="1" id="gender_open"
                        <?php
                        if(isset($setData['gender_open']) && $setData['gender_open']=1){
                          echo 'checked';
                        }elseif(isset($column['gender_open']) && $column['gender_open']==1){
                          echo 'checked';
                        } ?>>公開する
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <th>メールアドレス<span class="red">*</span>：</th>
                    <td><input type="text" name="mail" value="<?php if(isset($setData['mail'])){
                      echo h($setData['mail']);
                    } else{
                      echo h($column['mail']);
                    } ?>" placeholder="メールアドレスを入力してください" required></td>
                  </tr>
                  <tr>
                  <th>出身地：</th>
                  <td>
                    <select name="birth_place">
                      <option value="top" disabled
                      <?php if(empty($setData['birth_place'])||empty($column['birth_place'])): ?>
                        selected
                      <?php endif; ?>
                      >選択してください</option>
                      <?php foreach ($output['pref'] as $key => $value): ?>
                        <optgroup label="<?= $key ?>">
                          <?php for ($i=0; $i < count($output['pref'][$key]); $i++){
                            if (isset($setData['birth_place']) && $setData['birth_place'] === $value[$i]) {
                              echo '<option value="'.$value[$i].'" selected>'.$value[$i].'</option>';
                            }
                            elseif($column['birth_place'] === $value[$i]){
                              echo '<option value="'.$value[$i].'" selected>'.$value[$i].'</option>';
                            } else {
                            echo '<option value="'.$value[$i].'">'.$value[$i].'</option>';
                            }
                          }?>
                        </optgroup>
                      <?php endforeach; ?>
                    </select>
                  <label for="birth_place_open">
                    <input type="checkbox" name="birth_place_open" value="1"  id="birth_place_open" <?php
                    if(isset($setData['birth_place_open']) && $setData['birth_place_open']==1){
                      echo 'checked';
                    }elseif(isset($column['birth_place_open']) && $column['birth_place_open']==1){
                      echo 'checked';
                    }; ?>>公開する
                  </label>
                  </td>
                </tr>
                <tr>
                  <th>出身地(その他)：</th>
                  <td><input type="text" name="birth_place2" value="<?php
                  if (isset($setData['birth_place2'])) {
                    echo h($setData['birth_place2']);
                  }elseif(!empty($column['birth_place2'])){
                    echo h($column['birth_place2']);
                  } ?>" placeholder="出身地を入力してください" disabled></td>
                </tr>
                <th>居住地：</th>
                <td>
                  <select name="living_place">
                    <option value="top" disabled
                    <?php if(empty($setData['living_place'])||empty($column['living_place'])): ?>
                      selected
                    <?php endif; ?>
                    >選択してください</option>
                    <?php foreach ($output['pref'] as $key => $value): ?>
                      <optgroup label="<?= $key ?>">
                        <?php for ($i=0; $i < count($output['pref'][$key]); $i++){
                          if (isset($setData['living_place']) && $setData['living_place'] === $value[$i]) {
                            echo '<option value="'.$value[$i].'" selected>'.$value[$i].'</option>';
                          }
                          elseif($column['living_place'] === $value[$i]){
                            echo '<option value="'.$value[$i].'" selected>'.$value[$i].'</option>';
                          } else{
                          echo '<option value="'.$value[$i].'">'.$value[$i].'</option>';
                          }
                        }?>
                      </optgroup>
                    <?php endforeach; ?>
                  </select>　
                <label for="living_place_open">
                  <input type="checkbox" name="living_place_open" value="1"  id="living_place_open" <?php
                  if(isset($setData['living_place_open']) && $setData['living_place_open']==1){
                    echo 'checked';
                  }elseif(isset($column['living_place_open']) && $column['living_place_open']==1){
                    echo 'checked';
                  } ?>>公開する
                </label>
                </td>
              </tr>
              <tr>
                <th>居住地(その他)：</th>
                <td><input type="text" name="living_place2" value="<?php
                if (isset($setData['living_place2'])) {
                  echo h($setData['living_place2']);
                }elseif(!empty($column['living_place2'])){
                  echo h($column['living_place2']);
                } ?>" placeholder="居住地を入力してください"    disabled></td>
              </tr>

              <?php if ($column['role_id']==2): ?>
              <tr>
                <th rowspan="2">対応可能時間<span class="red">*</span>：</th>
                <td>
                  <select class="" name="free_time1" required>
                  <?php for($i=0; $i<25; $i++): ?>
                    <?php
                    if($setData['free_time1']==$i && $setData['free_time1']!=NULL||$column['free_time1']==$i && $column['free_time1']!=NULL): ?>
                      <option value="<?php
                      if (isset($setData['free_time1'])) {
                        echo h($setData['free_time1']);
                      }else{
                        echo h($column['free_time1']);
                      } ?>" selected><?php
                      if (isset($setData['free_time1'])) {
                        // 9以下の場合0をつける
                        if ($setData['free_time1']<=9) {
                          echo h('0'.$setData['free_time1']);
                        }else{
                          echo h('0'.$setData['free_time1']);
                        }
                      }else{
                        if ($column['free_time1']<=9) {
                          echo h('0'.$column['free_time1']);
                        }else{
                          echo h($column['free_time1']);
                        }
                      } ?></option>
                  <?php elseif($i<=9): ?>
                    <option value="<?php echo '0'.$i ?>"><?php echo '0'.$i ?></option>
                    <?php else: ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endif; ?>
                  <?php endfor; ?>
                  </select>
                  :00 ～
                  <span class="endtime">01</span>:00<span>*</span>
                </td>
              </tr>
              <tr>
                <td>
                  <select class="" name="free_time2">
                  <?php for($i=0; $i<25; $i++): ?>
                    <?php if($setData['free_time2']==$i && $setData['free_time2']!=NULL||$column['free_time2']==$i && $column['free_time2']!=NULL): ?>
                      <option value="<?php
                      if (isset($setData['free_time2'])) {
                        echo h($setData['free_time2']);
                      }else{
                        echo h($column['free_time2']);
                      } ?>" selected><?php
                      if (isset($setData['free_time2'])) {
                        echo h($setData['free_time2']);
                      }else{
                        echo h($column['free_time2']);
                      } ?></option>
                    <?php elseif($i<=9): ?>
                    <option value="<?php echo '0'.$i ?>"><?php echo '0'.$i ?></option>
                    <?php else: ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endif; ?>
                  <?php endfor; ?>
                  </select>
                  :00 ～
                  <span class="endtime">01</span>:00

                  <label for="use_time2">
                    <input type="checkbox" name="use_time2" value="1" id="use_time2" <?php
                    if(isset($_POST['use_time2']) && $_POST['use_time2']==1){
                      echo 'checked';
                    }elseif($column['free_time2']==99){
                      echo 'checked';
                    } ?>>設定しない
                  </label>
                </td>
              </tr>
            <?php endif; ?>

              </table>
              <?php if ($column['role_id']==2): ?>
              <div class="notes">
                ※対応可能時間は相談者から予約を受けた際に利用します。<br>
                　相談時間は60分を目安としています。<br>
                　安定してログインできる時間をご指定ください。
              </div>
            <?php endif; ?>

              </div>


            <h3>パスワード設定</h3>
            <div class="form_container">
            <a href="/users/iforgot.php" target="_blank">　パスワード変更はこちらから</a>
            </div>

            <h3>一言コメント</h3>
            <div class="form_container">
              <table>
                <tr>
                  <th>一言コメント：</th>
                  <td>
                    <textarea name="comment" rows="8" cols="80" class="comment"><?php if (isset($setData['comment'])) {
                      echo h($setData['comment']);
                    }else{
                      echo h($column['comment']);
                    } ?></textarea>
                  </td>
                </tr>
              </table>
            </div>

            <div class="btn_container center">
              <button type="submit" name="up_prof" class="common_btn">更新する</button>
            </div>
            </form>
          </div>

        </div>
      </div>
    </div>

    <div class="imgpop">
      <div class="imgpop_bg js-popup_close"></div>
      <div class="imgpop_contents">
        <div class="cancel">
          <i class="fas fa-window-close fa-lg window_close"></i>
        </div>
        <p class="center">画像をご選択ください。</p>
        <div class="imgpop_container">
          <?php if ($column['role_id']==2): ?>
            <img src="/img/profile_img/samplestaff01.png" class="ma0 profile_img">
            <img src="/img/profile_img/samplestaff02.png" class="ma0 profile_img">
            <img src="/img/profile_img/samplestaff03.png" class="ma0 profile_img">
            <img src="/img/profile_img/samplestaff04.png" class="ma0 profile_img">
          <?php elseif($column['role_id']==1): ?>
            <img src="/img/profile_img/sampletalker01.png" class="ma0 profile_img">
            <img src="/img/profile_img/sampletalker02.png" class="ma0 profile_img">
            <img src="/img/profile_img/sampletalker03.png" class="ma0 profile_img">
            <img src="/img/profile_img/sampletalker04.png" class="ma0 profile_img">
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php require_once('../Views/footer.php') ?>

  </body>
</html>
