<?php
session_start();
include "db_conn.php";

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            header("Location: view.php");
            exit();
        }
    }
    $error = "Invalid credentials";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
</head>
<style>
    /* Set text color to black when input fields are not focused or hovered */
    .form-control {
        background-color: transparent !important;
        /* Text color when not hovered or focused */
    }

    /* Retain default behavior on hover and focus (default Bootstrap styling) */
    .form-control:hover,
    .form-control:focus {
        color: inherit;
        /* Retain the default color during hover and focus */
    }
</style>

<body class="img js-fullheight" style="background-image: url(assets/images/bg.jpg);">
    <!-- <form action="login.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="submit">Login</button>
    </form> -->
    <?php include "includes/navbar.php"; ?>

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Login</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <h3 class="mb-4 text-center">Have an account?</h3>
                        <form action="login.php" class="signin-form" method="post">
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input id="password-field" type="password" name="password" class="form-control" placeholder="Password" required>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <!-- <div class="w-50">
                                    <label class="checkbox-wrap checkbox-primary">Remember Me
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div> -->
                                <!-- <div class="w-50 text-md-right">
                                    <a href="#" style="color: #fff">Forgot Password</a>
                                </div> -->
                            </div>
                        </form>
                        <p class="w-100 text-center">&mdash; Or Sign Up &mdash;</p>
                        <div class="social d-flex text-center">
                            <a href="signup.php" class="px-2 py-2 mr-md-1 rounded"><span class="ion-logo-email mr-2"></span> Sign Up</a>
                            <!-- <a href="#" class="px-2 py-2 ml-md-1 rounded"><span class="ion-logo-twitter mr-2"></span> Twitter</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</body>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<!-- <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous">
</script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous">
</script> -->

</html>