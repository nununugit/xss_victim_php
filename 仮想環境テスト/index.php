<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>掲示板</title>
        <link rel="stylesheet" href="stylesheet.css">

    </head>
    <boby>
    <h1>web初心者の掲示板</h1>
    <p>初めて作った掲示板サイトを公開してみました。</p>

<div class='container'>
<div class="jumbotron mt-4">

<form action="index.php" method="post">

  <div class="form-group">
    <label for="exampleInputName">投稿者</label>
    <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="名前">
    <small class="text-muted">投稿者名を入れてください</small>
  </div>

  <div class="form-group">
    <label for="exampleInputComment">コメント</label>
        <textarea class="form-control" name="comment" rows="3" cols="50" id="exampleFormControlSelect2"></textarea>
        <small class="text-muted">コメントを入れてください</small>
  </div>

  <div class="form-group form-check">
  <input type="submit" name='send' value="upload" class="btn btn-primary">
  <input type="reset" name="reset" value="reset" class="btn btn-primary">
  <input type="submit" name="delete" value="Delete" class="btn btn-primary">
  <input name="chkno" type="hidden" value="<?php echo $chkno; ?>">
  </p>
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
<?php         
    try {        
        $dsn='mysql:dbname=xss_demo;host=localhost;charset=utf8';
        $user='root';
        $password= '';
        $dbh =new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'select * from lesson1;';         //lesson1というテーブルの中身を確認するという意味
        $result = $dbh->query($sql);
                               
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {     //forとwhileの違いは、繰り返し回数を優先的に指定したい場合はfor文、条件を優先的に繰り返したい場合はwhile文。今回はDBの中身がすべて書き出されるまでループを行う
            print "<div class='card'><h5 class='card-header'>{$row['name']} </h5> <div class='card-body'> {$row['comment']} </div> <div class='card-footer'> {$row['updated_at']} ";
            print '<form action="index.php" method="post">';
            print '<input type="submit" class="btn btn-primary  type="submit" name="remove" value="Remove"><br>';
            print '<input type="hidden" name="id" value="';
            print "{$row['id']}";
            print '">';
            print '</form></div> </div>';
        }

        if (isset($_POST['send'])) {         //isset関数は、変数に値がセットされていて、かつNULLでないときに、TRUE(真)を戻り値として返します。$_POST(ポスト変数)を利用することで、HTML入力フォームの値を受信して処理することが出来ます。
            $name = @$_POST['name'];            //@つきの式で生成されたエラーの場合は返り値が0(NULL,false)になるように制御されます。(＠をつけるとワーニングが出力されません。)nameに関するエラーは無視
            $comment = @$_POST['comment'];      //commentに関するエラーも無視
            if ( $name == NULL ) {
                print "<script type='text/javascript'>alert('nameが入力されていません。');window.location.href = 'http://localhost/myapp/XSS_demo_mine/';</script>";
            } elseif ( $comment == NULL ) {
                print "<script type='text/javascript'>alert('commentが入力されていません。');window.location.href = 'http://localhost/myapp/XSS_demo_mine/';</script>";
            } else {
                
            date_default_timezone_set('Asia/Tokyo');
            $timestamp = time() ;
            $now= date( "Y/m/d H:i:s", $timestamp );
            $stmt = $dbh->prepare("INSERT INTO lesson1 (name,comment,updated_at) VALUES (:name , :comment,'$now');");
            $stmt->execute([':name' => $name , ':comment' => $comment]);   //queryに$sqlを渡す。sqlを変数に入れるだけではデーターベースからデータを取得することはできない。
            $site = "http://localhost/myapp/XSS_demo_mine/";
            header("Location: $site");
            }
            
        }

        if (isset($_POST['edit'])) {
            $id = @$_POST['id'];
            $commentedit = @$_POST['commentedit'];
            if ( $commentedit == NULL ) {
                print "<script type='text/javascript'>alert('comment-editが入力されていません。');window.location.href = 'http://localhost/myapp/XSS_demo_mine/';</script>";
            } else {
            $sql = "UPDATE lesson1 set comment = '$commentedit' where id = $id;";
            $result = $PDO->query($sql);
            $site = "http://localhost/myapp/XSS_demo_mine/";
            header("Location: $site");
            }
        }

        if (isset($_POST['remove'])) {
            $id = @$_POST['id'];
            $sql = "DELETE from lesson1 where id = $id;";
            $result = $PDO->query($sql);
            $site = "http://localhost/myapp/XSS_demo_mine/";
            header("Location: $site");
        }
    
            //全削除ボタン
        if (isset($_POST['delete'])) {
            $sql = "DELETE FROM lesson1;";
            $result = $PDO->query($sql);
            $site = "http://localhost/myapp/XSS_demo_mine/";
            header("Location: $site");
        }

    } catch (PDOException $e) {
            exit('データベースに接続できませんでした。' . $e->getMessage());
    }
?>