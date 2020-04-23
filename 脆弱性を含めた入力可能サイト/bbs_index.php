<?php

session_start();
$session_user = $_SESSION['profile']['user_name'];
$session_uid = $_SESSION['profile']['user_id'];
if($session_uid){
echo "<h1>ようこそ".$_SESSION['profile']['user_name']."さん</h1>";

        //MySQLサーバへの接続とデータベースの選択
        $dsn='mysql:dbname=kadai;host=localhost;charset=utf8';
        $user='root';
        $password= '';
        try{
            $dbh =new PDO($dsn,$user,$password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM todolist where delete_flag=0 AND uid = '$session_uid'";
            $stmt=$dbh->prepare($sql);
            $stmt->execute();
            $count = $stmt->rowCount();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data[]=$row;
            }
        }catch(PDOException $e){
            print ($e->getMessage());
            die();
        }
            //テーブルへの登録
        if(isset($_POST['todo_title'])){
            $title = @$_POST['todo_title'];
            $comment = @$_POST['todo_value'];
            if (empty($title)||empty($comment)){
                echo '<strong>文字を入力してください</strong>';
            }else{
            date_default_timezone_set('Asia/Tokyo');
            $timestamp = time() ;
            $now= date( "Y/m/d H:i:s", $timestamp );
            $result = $dbh ->query($sql);
            $stmt = $dbh->prepare("INSERT INTO todolist VALUES( '', :title, :comment,'$session_uid',0,'$now',0 );");
            $stmt->execute([':title' => $title,':comment' => $comment]);
            if(!$result){
                die($dbh ->error);
            }
            header('Location: ./bbs_index.php');
        }
            
            //個別削除
        }if(isset($_GET['todo_id1'])){
                    $todo_id =  @$_GET['todo_id1'];
                    $sql = "UPDATE todolist SET delete_flag = 1 WHERE todo_id=$todo_id;";
                    $result = $dbh ->query($sql);
                    if(!$result){
                        die($dbh ->error);
                    }
                    header('Location: ./bbs_index.php');

            //全削除
        }if(isset($_POST['delete_all'])){
            date_default_timezone_set('Asia/Tokyo');
            $timestamp = time() ;
            $now= date( "Y/m/d H:i:s", $timestamp ) ;
            $sql="UPDATE todolist SET delete_flag = 1 WHERE delete_flag = 0 AND uid ='$session_uid';
            INSERT INTO todolist VALUES( '','test', 'test','$session_uid', 0 ,'$now',0 );";
                    $result = $dbh ->query($sql);
                    echo "success";
                    if(!$result){
                        die($dbh ->error);
                    }
                    header('Location: ./bbs_index.php');
        }if(isset($_POST['logout'])){
            session_destroy();
            header('Location: ./bbs_login.php');
        }
    }else{
        header('Location: ./bbs_login.php');
    }
        ?>

<div class='container'>
<div class="jumbotron mt-4">

<form action="bbs_index.php" method="post">

  <div class="form-group">
    <label for="exampleInputName">投稿者</label>
    <input type="text" name="todo_title" class="form-control" id="exampleInputEmail1" placeholder="名前">
    <small class="text-muted">投稿者名を入れてください</small>
  </div>

  <div class="form-group">
    <label for="exampleInputComment">コメント</label>
        <textarea class="form-control" name="todo_value" rows="4" cols="50" id="exampleFormControlSelect2"></textarea>
        <small class="text-muted">コメントを入れてください</small>
  </div>

        <input type="submit" value="投稿"  class="btn btn-primary">
        <input type="reset" value="リセット" class="btn btn-primary">        
</form>
        
        <form action="bbs_index.php" method="post">
        <input type="submit" value="全削除" name="delete_all" class="btn btn-primary">
        </form>
        
        <form action="bbs_index.php" method="post">
        <input type="submit" value="ログアウト" name="logout" class="btn btn-primary">
        </form>
        </div>
</div>
        <div>
        <?php foreach($data as $row){ ?>
        <div class='card'>
        <h5 class='card-header'><?php echo $row['todo_title'];?> </h5> 
        <div class='card-body'> <?php echo $row['todo_value'] ;?></div>
        <div class='card-footer'> <?php echo $row['post_date'];?>
        <form action="bbs_index.php" method="get">
            <input  value="削除する"  type="submit"  class="btn btn-primary">
            <input type="hidden"  name="todo_id1" value="<?=$row['todo_id']?>">
            </form>
        </div>
        </div>
        <?php }?>   
        </div>
        <a href="./bbs_alltodo.php">みんなの投稿はこちら</a>
        
 <!-- Bootstrap Javascript(jQuery含む) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        </body>
    </html>