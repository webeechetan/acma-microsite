<?php
session_start();
if(isset($_SESSION['admin'])) {
    header("Location: ./");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Acma Mobility Foundation Admin</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/snackbar.css">
    <link rel="stylesheet" href="../css/style.css"

</head>

<body class="bg-primary">
    <div id="snackbar">Some text some message..</div>
    <header class="bg-primary py-3 text-center">
        <div class="container">
            <img src="../images/logo.png" class="logo" alt="ACMA Logo">
        </div>
    </header>
    <section class="mt-3 overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-12 mx-auto">
                    <div class="login-box p-5 bg-white shadow">
                        <h2 class="mb-3 mb-md-4 text-primary text-uppercase"><b>Mobility Foundation Login</b></h2>
                        <form class="login-form" action="./php/login.php" method="POST">
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    
    <script src="../js/common.js"></script>
    
    <?php if(isset($_SESSION['response'])){ ?> <script>snackbar('<?php echo $_SESSION['response']['message']; ?>')</script> <?php unset($_SESSION['response']); } ?>
</body>

</html>