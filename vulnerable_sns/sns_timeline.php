<?php 
session_start();
$session_user = $_SESSION['profile']['user_name'];
$session_uid = $_SESSION['profile']['user_id'];
if($session_uid){

        //MySQLサーバへの接続とデータベースの選択
        $dsn='mysql:dbname=kadai;host=localhost;charset=utf8';
        $user='root';
        $password= '';
        try{
            $dbh =new PDO($dsn,$user,$password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT T.todo_value ,U.user_name, T.post_date FROM todolist T JOIN users_datas U ON T.uid=U.uid where delete_flag=0 ;";
            $stmt=$dbh->prepare($sql);
            $stmt->execute();
            $count = $stmt->rowCount();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data[]=$row;
            }
            $data=array_reverse($data);

            $sql = "SELECT * FROM todolist where delete_flag=0 AND uid = '$session_uid'";
            $stmt=$dbh->prepare($sql);
            $stmt->execute();
            $count = $stmt->rowCount();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data1[]=$row;
            }
            $data1=array_reverse($data1);
        }catch(PDOException $e){
            print ($e->getMessage());
            die();
        }if(isset($_GET['todo_id1'])){
          $todo_id =  @$_GET['todo_id1'];
          $sql = "UPDATE todolist SET delete_flag = 1 WHERE todo_id=$todo_id;";
          $result = $dbh ->query($sql);
          if(!$result){
              die($dbh ->error);
          }
          header('Location: ./sns_timeline.php');
        }if(isset($_POST['logout'])){
          session_destroy();
          header('Location: ./sns_login.php');
      }
    }else{
        header('Location: ./sns_login.php');
    }
  ?>

        <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>ひとこと掲示板</title>
        <link rel="stylesheet" type="text/css" href = "./css/sns.css" >
    </head>
<body>
<div class="container responsive">
  
<form action="sns_submit.php" method="post">
        <input type="submit" value="ログアウト" name="logout" style="position: fixed;top: 20px; right: 40px;" class="btn btn-primary">
        </form>
                 
<nav>
  <div class="nav navbar navbar-light bg-light fixed-bottom bg-white" id="nav-tab" role="tablist" >
    <a class="nav-item text-white navbar-brand nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><img alt="Home" src="./pngs/home_hoso.png" width="80" height="80"></a>
    <a class="nav-item text-white navbar-brand nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false"><img alt="Profile" src="./pngs/user_hoso.png" width="80" height="80"></a>
    <a class="nav-item text-white navbar-brand nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false"><img alt="Contact" src="./pngs/search_hoso.png" width="80" height="80"></a>
  </div>
</nav>

<div class="tab-content mt-3" id="nav-tabContent">
  <div class="tab-pane active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
    <div>
    <article>
    <div>
    <button onclick="location01()" class="btn btn-primary rounded-circle p-0 buttoniti" style="width:4rem; height:4rem;position: fixed;bottom: 150px; right: 40px;" >＋</button>
    </div>
            <?php foreach($data as $row1){ ?>            
        <div class='card posiCenterIn'>
        <div class='card-header'><h5><?php echo $row1['user_name'];?> </h5></div> 
        <div class='card-body'> <?php echo $row1['todo_value'] ;?></div>
        <div class='card-footer'> <?php echo $row1['post_date'];?></div>
        </div>
        <?php }?>
        </article>
    </div>
        </div>
  
  <div class="tab-pane" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
<center><h3><?php echo $_SESSION['profile']['user_name'] ; ?></h3></center>
  <div>
      <article>  
      <?php foreach($data1 as $row){ ?>
        <div class='card posiCenterIn'>
        <div class='card-body'> <?php echo $row['todo_value'] ;?></div>
        <div class='card-footer'> 
        <form action="sns_timeline.php" method="get">
            <input  value="削除する"  type="submit"  class="btn btn-primary">
            <input type="hidden"  name="todo_id1" value="<?=$row['todo_id']?>">
            </form><?php echo $row['post_date'];?>
        </div>
        </div>
        <?php }?>
        <button onclick="location01()" class="btn btn-primary rounded-circle p-0 buttoniti" style="width:4rem; height:4rem;position: fixed;bottom: 150px; right: 40px; z-index: 10000;" >＋</button>
        </article>
        </div>
  
      </div>

    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab"><nav class="navbar navbar-light bg-light">
  <form class="form-inline">
    <input class="form-control mr-sm-2" type="search" placeholder="検索..." aria-label="検索...">
    <button type="submit" class="btn btn-outline-success my-2 my-sm-0">検索</button>
  </form>
</nav>
</div>
</div>
<script type="text/javascript">
function location01() {
  location.href="http://localhost/myapp/xss_demo_php/vulnerable_sns/sns_submit.php"
}
</script>
        </div>


</div>  

<!-- Bootstrap Javascript(jQuery含む) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        </body>

        </html>
