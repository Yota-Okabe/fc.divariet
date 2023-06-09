<?php
    include './include/login.php';
    $name = $_POST['name'];
    $title = $_POST['title'];
    $body = $_POST['body'];
    $pass = $_POST['pass'];
    $token = $_POST['token'];

    if ($token != hash("sha256", session_id())) {
        header('Location: bbs.php');
        exit();
    }

    if ($name == '' || $body == '') {
        header("Location:bbs.php");
        exit();
    }

// 削除機能passwordチェック（数字かつ4文字）
    if (!preg_match("/^[0-9]{4}$/", $pass)) {
        exit();
    }

// 名前をクッキーに
    setcookie('name', $name, time() + 60*60*24*30);

    $dsn = 'mysql:dbname=fc.divariet;host=localhost';
    $user = 'root';
    $password='';

    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare("
            INSERT INTO bbs (name, title, body, date, pass)
            VALUES (:name, :title, :body, now(), :pass)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':body', $body, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->execute();
        header('Location:bbs.php');
        exit();
    } catch (PDOException $e) {
        exit('エラー:' . $e->getMessage());
    }

?>