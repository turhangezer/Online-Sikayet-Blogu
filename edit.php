<?php require_once "controllers/authController.php";

if (!isset($_SESSION['id'])) {
    header('location: login.php');
    exit();
}



?>


<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şikayetim Var</title>
    <!-- bootstrap için gerekli olan js ve css linkleri -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <!-- kullandıgım semboller için style linki -->
    <script src="https://kit.fontawesome.com/ea90b6fae9.js" crossorigin="anonymous"></script>
    <!-- google font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">



    <style>
        * {
            box-sizing: border-box;
        }



        .form-control {
            border-radius: 0px;
        }

        .btn-primary {
            border-radius: 0px;
        }

        body {
            background-image: url(assets/baloncuk.png);
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            color: white;
        }

        section {
            background-color: rgb(0, 0, 0, 0.5);
        }
    </style>

</head>

<body>

    <!-- navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Şikayetim Var</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Anasayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?logout=1">Çıkış Yap</a>
                    </li>
                </ul>
                <form action="index.php" method="post" class="my-auto d-flex" style="min-width: 50%;">
                    <input type="text" class="w-100 form-control" name="corp" placeholder="İlgili Kurum Adı ile Ara" disabled>
                    <button type="submit" name="search" class="btn btn-dark"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </nav>



    <?php

    $id = $_GET['edit'];
    $sql = "SELECT * FROM `complaint` where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($_SESSION['id']!== $row['author']) {
        header('location: index.php');
        exit();
    }

    ?>





    <!-- Sayfanın gövdesi -->
    <section class="mt-5 mx-auto p-3 text-center" style="max-width: 800px; height: fit-content;">
        <div class="row g-3 p-3">
            <!-- GÖnderi Paylaşma -->
            <div class="col-12 text-break text-dark bg-light rounded-3 p-3">
                <form action="edit.php?edit=<?php echo $row['id'] ?>" method="post">
                    <div class="form-floating">
                        <input type="text" class="form-control overflow-hidden" placeholder="Konu" name="subject" id="floatingTextarea1" value="<?php echo $row['subject'] ?>" maxlength="30"></input>
                        <label for="floatingTextarea1">Konu Başlığı</label>
                    </div>
                    <div class="form-floating mt-2">
                        <textarea class="form-control overflow-hidden" placeholder="Şikayetiniz mi var?" name="complaint" id="floatingTextarea2" style="height: 100px" maxlength="1000"><?php echo $row['text'] ?></textarea>
                        <label for="floatingTextarea2">Şikayetiniz mi var?</label>
                    </div>
                    <div class="form-floating mt-2">
                        <input type="text" class="form-control overflow-hidden" placeholder="İlgili Kurum" name="corp" id="floatingTextarea3" maxlength="30" value="<?php echo $row['corp'] ?>"></input>
                        <label for="floatingTextarea3">İlgili Kurum</label>
                    </div>
                    <div class="form-floating mt-2 text-start">
                        <?php if (count($errors) > 0) : ?>
                            <div class="alert alert-danger col-lg-12">
                                <?php foreach ($errors as $error) : ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-floating mt-2 text-start">
                        <?php if (count($success) > 0) : ?>
                            <div class="alert alert-success col-lg-12">
                                <?php foreach ($success as $message) : ?>
                                    <li><?php echo $message; ?></li>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="submit" value="Düzenle" name="edit" class="btn btn-primary mt-2">
                </form>
            </div>
    </section>


</body>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</html>