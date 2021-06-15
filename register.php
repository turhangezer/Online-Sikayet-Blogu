<?php require_once "controllers/authController.php"; 

if (isset($_SESSION['id'])) {
	header('location: index.php');
	exit();
}

?>

<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaydol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">

    <style>
        body {
            background-image: url(assets/baloncuk.png);
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            height: fit-content;
            margin-top: auto;
            margin-bottom: auto;
            max-width: 600px;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }

        .card-header h3 {
            color: white;
        }
    </style>

</head>

<body>


    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                    <h3>Kaydol</h3>
                </div>
                <div class="card-body mx-auto">
                    <form action="register.php" method="POST">
                        <div class="row g-3 px-lg-5 ">
                            <div class="form-floating  col-lg-6">
                                <input type="text" class="form-control text-capitalize" id="floatingInput" name="name" value="<?php echo $name ?>" placeholder="name" maxlength="30">
                                <label for="floatingInput">Adınız</label>
                            </div>
                            <div class="form-floating  col-lg-6">
                                <input type="text" class="form-control text-capitalize" id="floatingPassword" name="surname" value="<?php echo $surname ?>" placeholder="surname" maxlength="30">
                                <label for="floatingPassword">Soyadınız</label>
                            </div>
                            <div class="form-floating  col-lg-12">
                                <input type="text" class="form-control" id="floatingInput" name="email" value="<?php echo $email ?>" placeholder="name@example.com" maxlength="50">
                                <label for="floatingInput">Email adresiniz</label>
                            </div>
                            <div class="form-floating  col-lg-6">
                                <input type="password" class="form-control" id="floatingPassword"  name="pass1" placeholder="Password" maxlength="100">
                                <label for="floatingPassword">Şifreniz</label>
                            </div>
                            <div class="form-floating  col-lg-6">
                                <input type="password" class="form-control" id="floatingPassword"  name="pass2" placeholder="Password" maxlength="100">
                                <label for="floatingPassword">Şifre tekrar</label>
                            </div>
                            <div class="form-floating  col-lg-12">
                                <?php if (count($errors) > 0) : ?>
                                    <div class="alert alert-danger col-lg-12">
                                        <?php foreach ($errors as $error) : ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group ">
                                <input class="btn float-right btn-primary" style="width: 100px;" name="kaydol" type="submit" value="Kaydol">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="card-footer text-white">
                    <div class="d-flex justify-content-center links">
                        <p>Zaten bir hesabınız var mı? <a href="login.php" class="link-light">Giriş Yap</a> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>