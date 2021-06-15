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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
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

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Şikayetim Var</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="index.php">Anasayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="profil.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?logout=1">Çıkış Yap</a>
                    </li>
                </ul>
                <form action="profil.php" method="post" class="my-auto w-50 d-flex">
                    <input type="text" class="w-100 form-control" name="corp" placeholder="İlgili Kurum Adı ile Ara">
                    <button type="submit" name="search" class="btn btn-dark"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </nav>

    <section class="mt-5 mx-auto p-3 text-center" style="max-width: 800px; height: fit-content;">
        <div class="row g-3 p-3">





            <?php if (count($errors) > 0) : ?>
                <div class="alert alert-danger col-lg-12">
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <!-- Gönderiler -->

            <?php




            if (isset($_POST['search'])) {
                $corp = "%" . $_POST['corp'] . "%";
                $sql = "SELECT *,complaint.id AS complaintid, users.id AS usersid  FROM `complaint` JOIN users ON complaint.author=users.id where corp LIKE ? and author=? ORDER BY complaint.date DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $corp, $_SESSION['id']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 0) {
                    $errors['yok'] = "Aramanıza Uygun Bir Sonuç Bulamadık. Üzgünüz.";
                }
            } else {
                $sql = "SELECT *,complaint.id AS complaintid, users.id AS usersid  FROM `complaint` JOIN users ON complaint.author=users.id where author=? ORDER BY complaint.date DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $_SESSION['id']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 0) {
                    $errors['yok'] = "Hiç Gönderi Yok!";
                }
            }



            ?>

            <div class="alert alert-info col-lg-12 text-start">
                <p> <b><?php echo $_SESSION['name'] . " " . $_SESSION['surname']; ?></b> <br>
                    <b>Paylaşım Sayısı:</b> <?php echo $result->num_rows ?> <br>
                    <b>Mail Adresi:</b> <?php echo $_SESSION['email']; ?>
                </p>
            </div>
            <?php if (count($errors) > 0) : ?>
                <div class="alert alert-danger col-lg-12">
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>



            <?php while ($row = $result->fetch_assoc()) : ?>

                <div class="col-12 text-break text-dark bg-light rounded-3 p-3">
                    <p class="text-start text-capitalize"><b><?php echo $row['name'] . " " . $row['surname'];  ?> </b> <span style="float: right;"><?php echo $row['corp'] ?></span> </p>
                    <h5 class="text-start text-capitalize"><b> <?php echo $row['subject'] ?></b></h5>
                    <p class="text-start" id="complaint<?php echo $row['complaintid'] ?>">
                        <span class="text-start text-wrap" id="text<?php echo $row['complaintid'] ?>" style="display: inline;">
                            <?php
                            if (strlen($row['text']) < 100) {
                                echo htmlspecialchars($row['text']);
                            } else {
                                echo  htmlspecialchars(substr($row['text'], 0, 99));
                            }
                            ?></span>
                        <span id="textmore<?php echo $row['complaintid'] ?>" style="display: none; text-align:justify ;"> <?php echo htmlspecialchars($row['text']); ?> </span>
                        <?php if (strlen($row['text']) >= 100) : ?>
                            <span id="dots<?php echo $row['complaintid'] ?>">...</span>
                            <a href="#text<?php echo $row['complaintid'] ?>" onclick="myFunction<?php echo $row['complaintid'] ?>()" id="myBtn<?php echo $row['complaintid'] ?>" style="display: inline;">Devamını Oku</a>



                            <script>
                                function myFunction<?php echo $row['complaintid'] ?>() {
                                    var dots = document.getElementById("dots<?php echo $row['complaintid'] ?>");
                                    var btnText = document.getElementById("myBtn<?php echo $row['complaintid'] ?>");
                                    var text = document.getElementById("text<?php echo $row['complaintid'] ?>");
                                    var textmore = document.getElementById("textmore<?php echo $row['complaintid'] ?>");

                                    dots.style.display = "none";
                                    btnText.style.display = "none";
                                    text.style.display = "none";
                                    textmore.style.display = "inline";

                                }
                            </script>
                        <?php endif; ?>
                        <?php if ($row['edited'] === 1) : ?>
                            <span class="text-muted">(Düzenlendi).</span>
                        <?php endif; ?>
                    </p>
                    <?php $date = date("d.m.Y H:i:s", strtotime($row['date'])); ?>
                    <p class="text-muted text-start"> <?php echo $date ?>
                        <?php if ($_SESSION['id'] === $row['author']) : ?>
                            <span style="float: right;">
                                <a class="mx-3" href="edit.php?edit=<?php echo $row['complaintid'] ?>"><i class="fa fa-edit text-primary"></i></a>
                                <a href="profil.php?delete=<?php echo $row['complaintid'] ?>"><i class="text-danger fa fa-trash"></i></a>
                            </span>
                        <?php endif; ?>

                    </p>


                </div>
            <?php endwhile; ?>






    </section>


</body>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</html>