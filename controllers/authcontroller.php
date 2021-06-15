<?php

require "config/db.php";

session_start();

$errors = array();
$success = array();
$name = "";
$surname = "";
$email = "";

if (isset($_POST['kaydol'])) {

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if (empty($name))
        $errors['name'] = "Lütfen İsim Giriniz!";
    if (empty($surname))
        $errors['surname'] = "Lütfen Soyisim Giriniz!";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = "Geçerli Bir Email Adresi Giriniz";
    if (empty($email))
        $errors['email'] = "Lütfen Mail Adresi Giriniz!";
    if (empty($pass1))
        $errors['pass1'] = "Lütfen Şifre Giriniz!";
    if (empty($pass2))
        $errors['pass2'] = "Lütfen Şifreyi tekrar Giriniz!";
    if ($pass1 !== $pass2 and !empty($pass2) and !empty($pass1))
        $errors['şifre'] = "İki Şifre Eşleşmiyor";

    $sql = "SELECT * FROM users where email=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors['email'] = "Bu Email Başka Bir Hesaba Ait";
    }

    if (count($errors) === 0) {
        $password = password_hash($pass1, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (`name`,`surname`,`email`,`password`) VALUES(?,?,?,?) ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $surname, $email, $password);
        if ($stmt->execute()) {
            $id = $conn->insert_id;
            $_SESSION['id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['surname'] = $surname;
            $_SESSION['email'] = $email;

            header('location: index.php');
            exit();
        } else {
            $errors['hata'] = "Veritabanı Hatası: Kaydolma Başarısız";
        }
    }
}


if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = "Geçerli Bir Email Adresi Giriniz";
    if (empty($email))
        $errors['email'] = "Lütfen Mail Adresi Giriniz!";
    if (empty($password))
        $errors['password'] = "Lütfen Şifre Giriniz!";

    $sql = "SELECT * FROM users where email=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($result->num_rows === 0) {
        $errors['email'] = "Bu Email Sisteme Kayıtlı Değil!";
    }

    if (count($errors) === 0) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['surname'] = $user['surname'];
            $_SESSION['email'] = $user['email'];


            header('location: index.php');
            exit();
        } else {
            $errors['password'] = "Hatalı Şifre Girdiniz!";
        }
    }
}


if (isset($_POST['share'])) {

    $complaint = $_POST['complaint'];
    $corp = $_POST['corp'];
    $subject = $_POST['subject'];

    if (empty($complaint)) {
        $errors['text'] = "Lütfen Şikayetinizi Yazınız";
    }
    if (empty($corp)) {
        $errors['corp'] = "Lütfen İlgili Kurumu Yazınız";
    }
    if (empty($subject)) {
        $errors['subject'] = "Lütfen Konu Başlığı Yazınız";
    }
    if (count($errors) === 0) {
        $date = date("Y-m-d H:i:s", strtotime('now'));
        $sql = "INSERT INTO `complaint` (`subject`,`corp`,`date`,`text`,`author`) VALUES(?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $subject, $corp, $date, $complaint, $_SESSION['id']);
        $stmt->execute();
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['name']);
    unset($_SESSION['surname']);
    unset($_SESSION['email']);
    header('location:login.php');
    exit();
}
if (isset($_GET['delete'])) {
    $complaint = $_GET['delete'];
    $sql = "DELETE FROM `complaint` where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $complaint);
    $stmt->execute();
}

if (isset($_POST['edit'])) {

    $id = $_GET['edit'];
    $sql = "SELECT * FROM `complaint` where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $complaint = $_POST['complaint'];
    $corp = $_POST['corp'];
    $subject = $_POST['subject'];

    if (empty($complaint)) {
        $errors['text'] = "Lütfen Şikayetinizi Yazınız";
    }
    if (empty($corp)) {
        $errors['corp'] = "Lütfen İlgili Kurumu Yazınız";
    }
    if (empty($subject)) {
        $errors['subject'] = "Lütfen Konu Başlığı Yazınız";
    }
    if (count($errors) === 0) {
        $sql = "UPDATE `complaint` SET `subject`=? , `corp`=?,`text`=?,`edited`=1 where id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $subject, $corp, $complaint, $id);
        $stmt->execute();
        $success['edited'] = "Başarı ile düzenlendi";

    }
}
