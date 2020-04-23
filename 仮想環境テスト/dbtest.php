<html>
<head><title>PHP TEST</title></head>
<body>
 
<?php
$dsn = 'mysql:dbname=xss_demo ; host=localhost';
$user = 'root';
$password = ',Nm:OCj,-40D';
 
try{
    $dbh = new PDO($dsn, $user, $password);
 
    if ($dbh == null){
        print('接続に失敗しました。
');
    }else{
        print('接続に成功しました。
');
    }
}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}
 
$dbh = null;
 
?>
 
</body>
</html>