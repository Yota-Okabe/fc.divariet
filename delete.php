<?php
    $id = intval($_POST['id']);
    $pass = $_POST['pass'];
    $token = $_POST['token'];

    if ($token != hash("sha256", session_id())) {
        header('Location: bbs.php');
        exit();
    }

    if ($id == '' || $pass == '') {
        header('Location: bbs.php');
        exit();
    }

    $dsn = 'mysql:dbname=fc.divariet;host=localhost';
    $user = 'root';
    $password='';

    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare("DELETE FROM bbs WHERE id=:id AND pass=:pass");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->execute();

    } catch (PDOException $e) {
        exit('エラー：' . $e->getMessage());
    }
    header('Location: bbs.php');
    exit();
?>

