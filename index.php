<?php
session_start();
include './config/db_connect.php';

$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $sql = "SELECT * FROM users WHERE email = '$email' AND role = 'user'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    $correct_password = password_verify($password, mysqli_fetch_assoc($result)['password']);
    if ($correct_password) {
      $_SESSION['email'] = $email;
      $_SESSION['user_id'] = mysqli_fetch_assoc($result)['id'];
      $_SESSION['user_role'] = 'user';
      header("Location: user/dashboard.php");
      exit();
    } else {
      $error = "Email atau password salah";
    }
  } else {
    $error = "Email atau password salah";
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>



  <?php include './config/links_cdn.php' ?>
</head>

<body>
  <script src="assets/static/js/initTheme.js"></script>
  <div id="auth">

    <div class="row h-100 bg-white">
      <div class="col-lg-5 col-12 container py-5 px-4">
        <div id="auth-left" class="d-flex flex-column justify-content-center mx-auto align-items-center">
          <div class="auth-logo"><img src="./assets/logo.jpg" alt="Logo">
          </div>

          <h3 class="auth-title">Log in.</h3>
          <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

          <form action="admin.php" method="post" style="width: 80%;">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="row px-5 mt-2">
              <button type="submit" class="btn btn-warning" style="background-color: #f59e0b;">Login</button>
            </div>
            <?php
            if (!empty($error)) {
              echo "<p class='text-danger'>$error</p>";
            }
            ?>
          </form>
          <div class="text-center mt-5">
            <p class="text-gray-600">Don't have an account? <a href="register.php" class="font-bold">Sign
                up</a>.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right" style="background-image: url('./assets/auth-image.jpg'); background-size: cover;height: 100%">
        </div>
      </div>
    </div>

  </div>
</body>

</html>