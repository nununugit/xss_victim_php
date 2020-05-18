<?php

session_start();
$session_user = $_SESSION['profile']['user_name'];
$session_uid = $_SESSION['profile']['user_id'];
$csrf_token = $session_uid;
$_SESSION['csrf_token'] = $csrf_token;
if($session_uid){
        //MySQLサーバへの接続とデータベースの選択
        $dsn='mysql:dbname=kadai;host=localhost;charset=utf8';
        $user='root';
        $password= '';
        try{
            $dbh =new PDO($dsn,$user,$password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){
            print ($e->getMessage());
            die();
        }if(isset($_POST['todo_value'])){
            if($_POST["csrf_token"] === $_SESSION['csrf_token']){
                $comment = @$_POST['todo_value'];
                if (empty($comment)){
                    echo '<strong>文字を入力してください</strong>';
                }else{
                date_default_timezone_set('Asia/Tokyo');
                $timestamp = time() ;
                $now= date( "Y/m/d H:i:s", $timestamp );
                $stmt = $dbh->prepare("INSERT INTO todolist VALUES( '0', :comment,'$session_uid',0,'$now',0 );");
                $stmt->execute([':comment' => $comment]);
                header('Location: ./sns_timeline.php');
            }
        }else{
            echo '不正なリクエストです';
        }
        }if(isset($_POST['logout'])){
            session_destroy();
            header('Location: ./sns_login.php');
        }
    }else{
        header('Location: ./sns_login.php');
    }
        ?>

<div class='container'>
<div class="jumbotron mt-4">

<form action="sns_submit.php" method="post">

  <div class="form-group">
    <label for="exampleInputComment">コメント</label>
        <textarea class="form-control" name="todo_value" rows="10" cols="50" id="exampleFormControlSelect2"></textarea>
        <small class="text-muted">コメントを入れてください</small>
  </div>

  <input type="hidden" name="csrf_token" value="<?php echo $csrf_token;?>">
        <input type="submit" value="投稿"  class="btn btn-primary">
        <input type="reset" value="リセット" class="btn btn-primary">        
</form>
        <form action="sns_submit.php" method="post">
        <input type="submit" value="ログアウト" name="logout" class="btn btn-primary">
        </form>
        </div>
</div>        
 <!-- Bootstrap Javascript(jQuery含む) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        </body>
    </html>