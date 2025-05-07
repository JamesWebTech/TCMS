<?php
session_start();
include('db.php'); // Ensure db.php is correctly included

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["form_type"]) && $_POST["form_type"] == "login") {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $user_type = isset($_POST['user_type']) ? trim($_POST['user_type']) : null;

  if (empty($email) || empty($password) || empty($user_type)) {
    $_SESSION['login_error'] = "Please fill in all fields and select a user type.";
    header("Location: login.php");
    exit();
  }

  // Prepare SQL query based on user type
  if ($user_type === 'collector') {
    $query = "SELECT * FROM collectors WHERE email = ?";
  } else {
    $query = "SELECT * FROM registration WHERE email = ? AND user_type = ?";
  }

  $stmt = $conn->prepare($query);

  if ($user_type === 'collector') {
    $stmt->bind_param("s", $email);
  } else {
    $stmt->bind_param("ss", $email, $user_type);
  }

  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $email;
      $_SESSION['user_type'] = $user_type;
      $_SESSION['user_logged_in'] = true;

      // Redirect based on user type
      if ($user_type === 'admin') {
        header("Location: /TCMS/Admin/index.php");
        exit();
      } elseif ($user_type === 'collector') {
        header("Location: /TCMS/collector/index.php");
        exit();
      } else {
        header("Location: /TCMS/user/index.php");
        exit();
      }
    } else {
      $_SESSION['login_error'] = "Invalid email or password.";
    }
  } else {
    $_SESSION['login_error'] = "Invalid email, password, or user type.";
  }

  // Redirect back to login page if login fails
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>

  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/login&signup.css" />

  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>



</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
        <div class="card p-4">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Login Form</h2>
            <form action="login.php" method="post">
              <input type="hidden" name="form_type" value="login">

              <!-- User Type Selection -->
              <div class="form-group">
                <label for="user_type">Login As</label>
                <select id="user_type" name="user_type" class="form-control" required>
                  <option value="" disabled selected hidden>Select User Type</option>
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                  <option value="collector">Collector</option>
                </select>
              </div>

              <!-- Email Input -->
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email"
                  autocomplete="email" required />
              </div>

              <!-- Password Input -->
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password"
                  autocomplete="current-password" required />
              </div>

              <!-- Login Button -->
              <button type="submit" class="btn btn-primary btn-block mt-3">Login</button>
            </form>

            <!-- Forgot Password Link -->
            <div class="text-center mt-3">
              <a href="forgot.php" class="text-decoration-none forgot">Forgot password?</a>
            </div>

            <!-- Registration Link -->
            <div class="text-center mt-3">
              <p>Don't have an account? <a href="Registration.php" class="text-decoration-none">Sign Up</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Modal for Login Error Messages -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger">Login Error</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <p id="errorMessage"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Trigger Modal if Login Error Exists -->
  <script>
    $(document).ready(function() {
      <?php if (isset($_SESSION['login_error'])): ?>
        $("#errorMessage").text("<?php echo $_SESSION['login_error']; ?>");
        $("#errorModal").modal("show");
        <?php unset($_SESSION['login_error']); ?>
      <?php endif; ?>
    });
  </script>
</body>

</html>