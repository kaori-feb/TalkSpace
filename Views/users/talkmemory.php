<?php
session_start();
require_once(ROOT_PATH .'Controllers/UserController.php');
require_once(ROOT_PATH .'Controllers/BordController.php');
$user = new UserController();
$bord = new BordController();
$user->xss();
$pasttalk=$user->talkUserList();
?>

<!DOCTYPE html>
<html lang='ja'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>悩み相談SPACE「」：過去の相談一覧</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
script>
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


    <div class="wrap_bg ma0 tab_content">
      <div class="line">
        <h1>あなたの過去の相談</h1>

        <div class="past_talk past_all">
          <table class="ma0">
            <tr>
              <th></th>
              <th>相談相手</th>
              <th>相談日</th>
              <th>Memory</th>
            </tr>
          <?php if (!empty($pasttalk['list'])): ?>
          <?php foreach ($pasttalk['list'] as $value): ?>
            <tr>
              <td>
                <img src="<?php
                if (empty($value['talkerid'])) {
                  echo h($pasttalk['defaultimg']);
                }else if($_SESSION['User']['id']!=$value['talkerid']){
                  echo h($value['img']);
                } else{
                  echo h($value['img2']);
                }; ?>" alt="相談相手の画像">
              </td>
              <td>
                <?php
                if ($value['talkerid']==null) {
                  echo h($pasttalk['defaultname']);
                }else if($_SESSION['User']['id']!=$value['talkerid']){
                  echo h($value['name']);
                } else {
                  echo h($value['name2']);
                }; ?>
              </td>
              <td>
                <?= h(date('Y年m月d日',strtotime($value['day']))) ?>
              </td>
              <td>
                <a href="/bords/talkmemory.php?b=<?= h($value['bord_id']) ?>">振り返る</a>
              </td>
            </tr>
        <?php endforeach; ?>
        </table>
        </div>
        <?php else: ?>
        </table>
        </div>
        <div class="complete flex aligin_center flex_around">
            <p class="complete_message">登録されていません。</p>
        </div>
        <?php endif; ?>

        <?php if ($pasttalk['pages']>=1): ?>
          <div class="paging">
            <?php
            for($i=1; $i<=$pasttalk['pages']+1; $i++){
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
        <?php endif; ?>

      </div>
    </div>

    <?php require_once('../Views/footer.php'); ?>
  </body>
</html>
