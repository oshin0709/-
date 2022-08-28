<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    
    <?php 
    // DB接続設定
    $dsn = 'パスワード';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //新規投稿
    $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment,date,pass) VALUES (:name, :comment,:date,:pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    
    if(!empty($_POST["comment"])&&!empty($_POST["name"])&&empty($_POST["edits"])&&!empty($_POST["pass"])){
        $name = $_POST["name"];
        $comment = $_POST["comment"]; 
        $date=date("Y/n/j G:i:s");
        $pass= $_POST["pass"];
        $sql -> execute();
    
    }elseif(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["edits"])){
        $name = $_POST["name"];
        $comment = $_POST["comment"]; 
        $date=date("Y/n/j G:i:s");
        $edits=$_POST["edits"];
        $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $edits, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
    }
    
    //削除機能
    if(!empty($_POST["deleteno"])&&!empty($_POST["pass_d"])){
        $deleteno=$_POST["deleteno"];
        $pass_d=$_POST["pass_d"];
        $sql = 'delete from tbtest where id=:id AND pass=:pass';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $deleteno, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $pass_d, PDO::PARAM_STR);
        $stmt->execute();
    }
    
    //編集対象番号をフォームに表示
    if(!empty($_POST["edit"])&&!empty($_POST["pass_e"])){
        $edit=$_POST["edit"];
        $pass_e=$_POST["pass_e"];
        
        $sql = "SELECT * FROM tbtest where id=:id AND pass=:pass";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $pass_e, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(pdo::FETCH_ASSOC);
        foreach ($results as $row){
            $editnumber=$row['id'];
            $editname=$row['name'];
            $editcomment=$row['comment'];
            $pass=$row['pass'];
        }
    }
    
    ?>
    
    <form action="" method="post">
        <input type="str" name="name"placeholder="名前"value="<?php if(isset($editname)){echo $editname;}?>"><br>
        <input type="str" name="comment"placeholder="コメント"value="<?php if(isset($editcomment)){echo $editcomment;}?>"><br>
        <input type="str" name="pass"placeholder="パスワード">
        <input type="hidden" name="edits"value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
        <input type="submit" name="submit1"><br><br>
        <input type="number" name="deleteno"placeholder="削除対象番号"><br>
        <input type="str" name="pass_d"placeholder="パスワード">
        <input type="submit" name="submit2" value="削除"><br><br>
        <input type="number" name="edit" placeholder="編集対象番号"><br>
        <input type="str" name="pass_e"placeholder="パスワード">
        <input type="submit" name="submit3" value="編集">
    </form>
    
    <?php
    //投稿表示
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
    }
    ?>