<?php
session_start();
include('db.php'); // Include database connection

// REGISTRATION PROCESS
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
  $user_type = trim($_POST['user_type']);
  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $address = trim($_POST['address']);
  $contact = trim($_POST['contact']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Check if fields are empty
  if (empty($user_type) || empty($firstname) || empty($lastname) || empty($address) || empty($contact) || empty($email) || empty($password)) {
    $_SESSION['registration_error'] = "All fields are required.";
    header("Location: registration.php");
    exit();
  }

  // ✅ Check if an admin already exists
  if ($user_type === 'admin') {
    $check_admin_query = "SELECT COUNT(*) as admin_count FROM registration WHERE user_type = 'admin'";
    $result = $conn->query($check_admin_query);
    $admin_count = $result->fetch_assoc()['admin_count'];

    if ($admin_count > 0) {
      $_SESSION['registration_error'] = "Only one admin is allowed.";
      header("Location: registration.php");
      exit();
    }
  }

  // ✅ Check if email is already registered
  $check_query = "SELECT * FROM registration WHERE email = ?";
  $stmt = $conn->prepare($check_query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $_SESSION['registration_error'] = "Email is already registered.";
    header("Location: registration.php");
    exit();
  }

  // ✅ Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // ✅ Insert new user
  $insert_query = "INSERT INTO registration (user_type, fname, lname, address, contact, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($insert_query);
  $stmt->bind_param("sssssss", $user_type, $firstname, $lastname, $address, $contact, $email, $hashed_password);

  if ($stmt->execute()) {
    $_SESSION['registration_success'] = "Registration successful! You can now login.";
    header("Location: registration.php");
    exit();
  } else {
    $_SESSION['registration_error'] = "Registration failed. Please try again.";
    header("Location: registration.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Garbage Monitoring - Registration</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/login&signup.css">
  <link rel="icon" href="css/bin.png">
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-lg">
          <div class="card-body registration_body">
            <form action="registration.php" method="post" class="registration-card">
              <h2 class="text-center h2_register">Registration Form</h2>

              <div class="form-group registration">
                <label for="user_type">Register As</label>
                <select name="user_type" id="user_type" class="form-control registration_control" required>
                  <option value="">Select User Type</option>
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                </select>
              </div>

              <!-- Input group for First Name and Last Name -->
              <div class="form-group registration">
                <div class="d-flex justify-content-between">
                  <div class="col-5">
                    <label for="firstname">First Name</label>
                    <div class="input-group">
                      <input type="text" id="firstname" name="firstname" class="form-control registration_control" required>
                    </div>
                  </div>

                  <div class="col-5">
                    <label for="lastname">Last Name</label>
                    <div class="input-group">
                      <input type="text" id="lastname" name="lastname" class="form-control registration_control" required>
                    </div>
                  </div>
                </div>
              </div>


              <div class="form-group registration">
                <div class="d-flex justify-content-between">
                  <div class="col-5">
                    <label for="address">Address</label>
                    <div class="input-group">
                      <input type="text" id="address" name="address" class="form-control registration_control" required>
                    </div>
                  </div>

                  <div class="col-5">
                    <label for="contact">Contact</label>
                    <div class="input-group">
                      <input type="text" id="contact" name="contact" class="form-control registration_control" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group registration">
                <div class="d-flex justify-content-between">
                  <div class="col-5">
                    <label for="email">Email</label>
                    <div class="input-group">
                      <input type="email" id="email" name="email" class="form-control registration_control" required>
                    </div>
                  </div>

                  <div class="col-5">
                    <label for="password">Password</label>
                    <div class="input-group">
                      <input type="password" id="password" name="password" class="form-control registration_control" required>
                    </div>
                  </div>
                </div>
              </div>


              <button type="submit" name="submit" class="btn btn-primary w-100 mt-3 register_button">Register</button>

              <div class="Already_login text-center mt-3">
                <p>ALREADY HAVE AN ACCOUNT? <a href="login.php" class="register-link">Login</a></p>
              </div>
            </form>

            <!-- Error/Success Modal -->
            <div class="modal fade" id="messageModal" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body text-center">
                    <p id="modalMessage"></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap & jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
      <?php if (isset($_SESSION['registration_error'])): ?>
        $("#messageModalLabel").text("Registration Error");
        $("#modalMessage").text("<?= $_SESSION['registration_error'] ?>");
        $("#messageModal").modal("show");
        <?php unset($_SESSION['registration_error']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['registration_success'])): ?>
        $("#messageModalLabel").text("Registration Successful");
        $("#modalMessage").text("<?= $_SESSION['registration_success'] ?>");
        $("#messageModal").modal("show");
        <?php unset($_SESSION['registration_success']); ?>
      <?php endif; ?>
    });
  </script>

</body>

</html>