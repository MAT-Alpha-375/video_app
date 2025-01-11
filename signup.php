<?php
include "db_conn.php";

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/style.css">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
</head>

<body class="img js-fullheight" style="background-image: url(assets/images/bg.jpg);">
    <!-- <form action="signup.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="submit">Sign Up</button>
    </form> -->
    <?php include "includes/navbar.php"; ?>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Don't have an account?</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <h3 class="mb-4 text-center">Sign Up</h3>
                        <form action="signup.php" method="post" class="signin-form">
                            <div class="form-group">
                                <input type="text" name="username" placeholder="Username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <input id="password-field" type="password" name="password" class="form-control" placeholder="Password" required>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="form-control btn btn-primary submit px-3">Sign Up</button>
                            </div>
                        </form>

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

</html>