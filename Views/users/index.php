<?php
session_start();
require_once(ROOT_PATH .'Controllers/UserController.php');
require_once(ROOT_PATH .'Controllers/BordController.php');
$user = new UserController();
$bord = new BordController();
$staffsearch = $user->datacontroll();
$user->xss();
$output = $user->output();
$user->favBtnflg();

$token = isset($_POST["doublesubmit_token_forhome"]) ? $_POST["doublesubmit_token_forhome"] : "";
// セッション変数のトークンを取得
$session_token = isset($_SESSION["doublesubmit_token_forhome"]) ? $_SESSION["doublesubmit_token_forhome"] : "";
// セッション変数のトークンを削除
unset($_SESSION["doublesubmit_token_forhome"]);
// POSTされたトークンとセッション変数のトークンの比較
if($token != "" && $token == $session_token) {
  // 新規登録した場合
  if(isset($_POST['to_home'])){
    $result=$user->login();
  }
}

// ログイン経由か
if(!isset($_SESSION['User'])){
  if (!isset($_POST['anonymous'])) {
    header('Location:login.php');
    exit;
  }
}

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
    <title>悩み相談SPACE「」：ホーム</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/favorite_ajax.js"></script>
  </head>
  <body>
    <?php require_once('../Views/header.php') ?>

    <div class="home_header ma0">
      <?php if (isset($_POST['anonymous'])): ?>
        <?php $_SESSION['User']['id']=$_POST['n_userid'];
              $_SESSION['anonymous']=$_POST['anonymous']?>
        <p>ようこそ「ゲスト」さん</p>
      <?php elseif(isset($_SESSION['anonymous'])): ?>
        <p>ようこそ「ゲスト」さん</p>
        <?php else: ?>
          <p>ようこそ「<?= h($_SESSION['User']['nickname']) ?>」さん</p>
      <?php endif; ?>
    </div>

    <menu class="menu_wrap ma0">
      <a href="/users/index.php" class="menu_label menu_current">
      スタッフ検索</a>
      <?php if (!isset($_SESSION['anonymous'])): ?>
        <a href="/users/talkmemory.php" class="menu_label">
        過去の相談</a>
        <a href="/users/favoriteuser.php" class="menu_label">
        お気に入り</a>
        <a href="/users/myprofile.php" class="menu_label">
        プロフィール</a>
      <?php endif; ?>
    </menu>

      <div class="wrap_bg ma0 tab_content">
        <div class="line">
          <h1>スタッフ検索</h1>
          <p>相談スタッフが検索できます。<br>相談画面へボタンに色がついているスタッフは今相談できるスタッフになります。</p>
          <div class="flex flex_between staffsearch_container">

            <div class="search_box">
              <form class="staffsearch_form" action="index.php" method="post">
                <p class="staffsearch_formtop"><i class="fas fa-search"></i> 絞り込む</p>
                <p>性別</p>
                <div class="search_condition">
                  <label for="female">
                    <input type="radio" name="staffgender" value="1" id="female">女性　
                  </label>
                  <label for="male">
                    <input type="radio" name="staffgender" value="2" id="male">男性　
                  </label>
                  <label for="x-gender">
                    <input type="radio" name="staffgender" value="3" id="x-gender">その他　
                  </label>
                </div>

                <p>出身</p>
                <div class="search_condition">
                  <select name="staff_birth_place" class="w90">
                    <option value="" selected>出身地で絞り込む</option>
                    <?php foreach ($output['pref'] as $key => $value): ?>
                      <optgroup label="<?= $key ?>">
                        <?php for ($i=0; $i < count($output['pref'][$key]); $i++){
                            echo '<option value="'.$value[$i].'">'.$value[$i].'</option>';
                          }?>
                      </optgroup>
                    <?php endforeach; ?>
                  </select>
                </div>

                <p>居住地</p>
                <div class="search_condition">
                  <select name="staff_living_place" class="w90">
                    <option value="" selected>居住地で絞り込む</option>
                    <?php foreach ($output['pref'] as $key => $value): ?>
                      <optgroup label="<?= $key ?>">
                        <?php for ($i=0; $i < count($output['pref'][$key]); $i++){
                            echo '<option value="'.$value[$i].'">'.$value[$i].'</option>';
                          }?>
                      </optgroup>
                    <?php endforeach; ?>
                  </select>
                </div>

                  <p>対応可能時間</p>
                  <div class="search_condition">
                    <select name="search_freetime">
                      <?php for($i=0; $i<25; $i++): ?>
                        <?php if($i<=9): ?>
                        <option value="<?php echo '0'.$i ?>"><?php echo '0'.$i ?></option>
                        <?php else: ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php endif; ?>
                      <?php endfor; ?>
                    </select>
                    :00
                    <input type="hidden" name="staff_free_time" value="">
                  </div>

                <div class="search_condition">
                  <button type="submit" name="staffsearch" class="common_btn">検索</button>
                </div>

              </form>
            </div>

            <div class="flex search_result">
              <?php foreach ($staffsearch['stafflist'] as $column): ?>
                <div class="staff_box">
                  <?php if (!isset($_SESSION['anonymous'])): ?>
                  <?php if ($column['id']!=$_SESSION['User']['id']): ?>
                    <?php
                    $favflg=$user->favBtnflg($column['id'],$_SESSION['User']['id']); ?>
                    <span class="favorite_btn <?php if (empty($favflg)) echo 'gray_btn'; ?>" data-favId='{"favId":"<?= h($column['id']) ?>","user_id":"<?= h($_SESSION['User']['id']) ?>"}' id="fav_btn">
                      <i class="far fa-star"></i>
                    </span>
                  <?php endif; ?>
                <?php endif; ?>


                  <img src="<?= h($column['img_path']) ?>" alt="">
                  <table class="ma0">
                    <tr>
                      <th>名前：</th>
                      <td><a href="/users/profile.php?user=<?= h($column['id']) ?>" class="name_limit"><?= h($column['nickname']) ?></a></td>
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
                  </table>

                  <?php
                  $result=$bord->canTalk($column['id']);
                   ?>
                   <?php if ($column['id']!=$_SESSION['User']['id']): ?>
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
                 <?php endif; ?>

                </div>
              <?php endforeach; ?>

              <div class="paging">
                <?php
                for($i=1; $i<$staffsearch['pages']+1; $i++){
                  // URLにページ番号ある　& ページ番号がiと一致
                  if(isset($_GET['page']) && $_GET['page'] == $i){
                    echo $i;
                  }elseif (!isset($_GET['page']) && $i == 1) {
                    echo $i;
                  }
                  else {
                    echo '<a href=?page='.($i).'>'.($i).'</a>';
                  }
                }
                ?>
              </div>

            </div>
          </div>
        </div>
      </div>


    <?php require_once('../Views/footer.php'); ?>
  </body>
</html>
