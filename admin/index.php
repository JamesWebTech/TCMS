<?php
include __DIR__ . '/../fbdb.php'; // Adjust the path if needed
include 'data.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="index.css">
    <style>
        .circle-indicator {
            width: 50px;
            height: 50px;
        }

        .circle-background {
            fill: none;
            stroke: #eee;
            stroke-width: 3;
        }

        .circle-progress {
            fill: none;
            stroke-width: 3;
            stroke-linecap: round;
            transition: stroke 0.3s;
            stroke: #ccc;
            /* Default inactive */
        }

        .circle-progress.active.empty {
            stroke: #4caf50;
        }

        /* Green */
        .circle-progress.active.medium {
            stroke: #ffeb3b;
        }

        /* Yellow */
        .circle-progress.active.high {
            stroke: orange;
        }

        /* Orange */
        .circle-progress.active.full {
            stroke: #f44336;
        }

        /* Red */
        .circle-background.empty {
            stroke: #c8e6c9;
        }

        /* Light green */
        .circle-background.medium {
            stroke: #fff9c4;
        }

        /* Light yellow */
        .circle-background.high {
            stroke: #ffe0b2;
        }

        /* Light orange */
        .circle-background.full {
            stroke: #ffcdd2;
        }

        /* Light red */
    </style>
</head>

<body onload="toggleContainer('dashboard-container')">
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 bg-dark text-white p-4 navigation min-vh-50">

                <!-- Top Row: Collector + Menu button (small screens only) -->
                <div class="d-flex justify-content-between align-items-center mb-3 d-md-none">
                    <!-- Collector title on the left -->
                    <h4 class="m-0 text-white">⚙️ Admin</h4>

                    <!-- Menu button on the right (label "Menu" for small screens) -->
                    <div class="dropdown ms-auto">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-list me-2"></i> Menu
                        </button>
                        <ul class="dropdown-menu w-100 bg-dark" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-white" href="#" onclick="toggleContainer('dashboard-container')">
                                    <i class="bi bi-house-door me-2"></i> Dashboard
                                </a></li>
                            <li><a class="dropdown-item text-white" href="#" onclick="toggleContainer('schedule-container')">
                                    <i class="bi bi-calendar me-2"></i> Schedule
                                </a></li>
                            <li><a class="dropdown-item text-white" href="#" onclick="toggleContainer('map-container')">
                                    <i class="bi bi-map me-2"></i> Map
                                </a></li>
                            <li><a class="dropdown-item text-white" href="#" onclick="toggleContainer('collector-container')">
                                    <i class="bi bi-collection me-2"></i> Collector
                                </a></li>
                            <li><a class="dropdown-item text-danger text-center" href="#" onclick="logout()">Logout</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Collector title for large screens -->
                <h4 class="d-none d-md-block">⚙️ Admin</h4>

                <!-- Navigation Items for Large Screens -->
                <ul class="nav flex-column mt-3 nav-menu d-none d-md-block">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white small" onclick="toggleContainer('dashboard-container')">
                            <i class="bi bi-house-door me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white small" onclick="toggleContainer('schedule-container')">
                            <i class="bi bi-calendar me-2"></i> Schedule
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white small" onclick="toggleContainer('map-container')">
                            <i class="bi bi-map me-2"></i> Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white small" onclick="toggleContainer('collector-container')">
                            <i class="bi bi-collection me-2"></i> Collector
                        </a>
                    </li>

                    <li class="nav-item">
                        <button class="btn btn-danger w-100 logout" onclick="logout()">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </li>
                </ul>

            </nav>




            <!-- Main Content -->
            <div id="dashboard-container" class="section d-none">
                <h2 class="dashboard">Dashboard</h2>
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-start">
                        <div class="card mb-3 square-card collector_card">
                            <div class="card-body">
                                <h4> Collectors</h4>
                                <p class="h3"><?php echo $collector_count['collectors']; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-end">
                        <div class="card mb-3 square-card user_card">
                            <div class="card-body">
                                <h4> Users</h4>
                                <p class="h3"><?php echo $collector_count['users']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Table (Only visible on the Dashboard) -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card schedule_card">
                            <div class="card-body">
                                <h2>Schedule</h2>
                                <div class="schedule-table-container">
                                    <table class="table table-striped table_card">
                                        <thead>
                                            <tr>
                                                <th>Trashcan</th>
                                                <th>Location</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Collector</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT ls.trashcan, ls.location, ls.date, ls.status, c.name AS collector_name
                                                FROM location_schedule ls
                                                JOIN collectors c ON ls.collector_id = c.collector_id";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                        <td>" . htmlspecialchars($row["trashcan"]) . "</td>
                                                        <td>" . htmlspecialchars($row["location"]) . "</td>
                                                        <td>" . htmlspecialchars($row["date"]) . "</td>
                                                        <td>" . htmlspecialchars($row["status"]) . "</td>
                                                        <td>" . htmlspecialchars($row["collector_name"]) . "</td>
                                                    </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center'>No schedule available</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-9 col-lg-10 p-4">
                <!-- Schedule Management -->
                <div id="schedule-container" class="section d-none">
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="card schedule">
                                <div class="card-body">
                                    <h2>Schedule Management</h2>
                                    <form method="POST" action="index.php">
                                        <!-- Trashcan & Location -->
                                        <div class="form-group mt-2">
                                            <div class="d-flex justify-content-between flex-wrap">
                                                <div class="col-5">
                                                    <label for="trashcan">Trashcan</label>
                                                    <select id="trashcan" name="trashcan" class="form-control" required>
                                                        <option value="TCMS-1">TCMS-1</option>
                                                        <option value="TCMS-2">TCMS-2</option>
                                                    </select>
                                                </div>

                                                <div class="col-5">
                                                    <label for="location">Location</label>
                                                    <input type="text" id="location" name="location" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Date & Collector -->
                                        <div class="form-group mt-3">
                                            <div class="d-flex justify-content-between flex-wrap">
                                                <div class="col-5">
                                                    <label for="date">Date</label>
                                                    <input type="date" id="date" name="date" class="form-control select-control" required>
                                                </div>

                                                <div class="col-5">
                                                    <label for="collector_id">Collector</label>
                                                    <select id="collector_id" name="collector_id" class="form-control select-control" required>
                                                        <option value="">Select a Collector</option>
                                                        <?php
                                                        $query = "SELECT collector_id, name FROM collectors";
                                                        $collectorResult = $conn->query($query);
                                                        while ($row = $collectorResult->fetch_assoc()) {
                                                            echo "<option value='" . $row['collector_id'] . "'>" . $row['name'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" name="add_schedule" class="btn btn-primary mt-4 save">Save Schedule</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- Map Container -->
                <div id="map-container" class="section d-none">
                    <div class="map-wrapper">
                        <div class="container h-100 d-flex justify-content-center align-items-center">
                            <div class="text-center text-white">
                                <h2>Map</h2>
                            </div>
                        </div>

                        <!-- Trash Bin Icons -->
                        <img src="../trashbin.png" class="trash-bin bin-1" alt="Trash Bin" data-bs-toggle="modal" data-bs-target="#binModal">
                        <img src="../trashbin.png" class="trash-bin bin-2" alt="Trash Bin" data-bs-toggle="modal" data-bs-target="#binModal">
                    </div>
                </div>



                <!-- Collector Container -->
                <div id="collector-container" class="section d-none">
                    <div class="row">
                        <!-- Add Collector Form -->
                        <div class="col-md-12">
                            <div class="card collector_card2">
                                <div class="card-body collector_body">
                                    <h2>Register New Collector</h2>
                                    <form method="POST" action="index.php" class="needs-validation" novalidate>

                                        <div class="form-group mb-3">
                                            <label for="name" class="form-label">Full Name:</label>
                                            <input type="text" name="name" id="name" class="form-control" autocomplete="name" required>
                                            <div class="invalid-feedback">Please enter the collector's full name.</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="d-flex justify-content-between gap-3">
                                                <div class="col">
                                                    <label for="contact_number" class="form-label">Contact Number:</label>
                                                    <input type="tel" name="contact_number" id="contact_number" class="form-control" autocomplete="tel" required>
                                                    <div class="invalid-feedback">Please enter a valid contact number.</div>
                                                </div>
                                                <div class="col">
                                                    <label for="email" class="form-label">Email:</label>
                                                    <input type="email" name="email" id="email" class="form-control" autocomplete="email" required>
                                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="d-flex justify-content-between gap-3">
                                                <div class="col">
                                                    <label for="address" class="form-label">Address:</label>
                                                    <input type="text" name="address" id="address" class="form-control" autocomplete="street-address" required>
                                                    <div class="invalid-feedback">Please enter the collector's address.</div>
                                                </div>
                                                <div class="col">
                                                    <label for="password" class="form-label">Password:</label>
                                                    <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" required>
                                                    <div class="invalid-feedback">Please enter a password.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" name="add_collector" class="btn btn-primary w-100 add">Register Collector</button>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Collector List Table -->
                        <div class="col-md-6">
                            <div class="card collector_list">
                                <div class="card-body">
                                    <h2>Registered Collectors</h2>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Contact Number</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch collectors from the database and display them
                                                if ($collector_result->num_rows > 0) {
                                                    while ($row = $collector_result->fetch_assoc()) {
                                                        echo "<tr>
                                                             <td>" . htmlspecialchars($row["name"]) . "</td>
                                                            <td>" . htmlspecialchars($row["contact_number"]) . "</td>
                                                            <td>" . htmlspecialchars($row["email"]) . "</td>
                                                            <td>" . htmlspecialchars($row["address"]) . "</td>
                                                            <td><button class='btn btn-danger btn-sm'>Delete</button></td>
                                                        </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center'>No collectors available</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="bottom-links fixed-bottom text-end p-3 link">
        <a href="../help/Introduction.php" target="_blank">Help</a>
        <span style="margin: 0 10px; font-weight: bold;"> / </span>
        <a href="#" data-bs-toggle="modal" data-bs-target="#aboutUsModal">About Us</a>
    </div>

    <!-- About Us Modal -->
    <div class="modal fade" id="aboutUsModal" tabindex="-1" aria-labelledby="aboutUsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="aboutUsModalLabel">About Us</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h1>ABOUT US</h1>
                    <p>Trashcan Monitoring System is here to help you make garbage collection easier.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Ensure Popper.js is included) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <!--  Modal for Alerts -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (isset($_SESSION['modal_message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['modal_message']['type'] == 'success' ? 'success' : 'danger'; ?>">
                            <?php echo $_SESSION['modal_message']['text']; ?>
                        </div>
                        <?php unset($_SESSION['modal_message']); ?>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="binModal" tabindex="-1" aria-labelledby="binModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="binModalLabel">Trash Bin Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="../trashbin.png" alt="Trash Bin" style="width: 100px; display: block; margin: 0 auto;">
                    <p><strong>Trashcan ID:</strong> TCMS-1</p>

                    <!-- Circular Indicators -->
                    <div class="row text-center mt-3">
                        <!-- Empty Circle -->
                        <div class="col">
                            <svg class="circle-indicator" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <circle class="circle-background<?php if ($level == 'empty') echo ' empty'; ?>" cx="18" cy="18" r="16"></circle>
                                <circle class="circle-progress<?php if ($level == 'empty') echo ' active empty'; ?>" cx="18" cy="18" r="16" stroke-dasharray="100, 100"></circle>
                            </svg>
                            <strong>Empty</strong>
                        </div>

                        <!-- Med Circle -->
                        <div class="col">
                            <svg class="circle-indicator" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <circle class="circle-background<?php if ($level == 'med') echo ' med'; ?>" cx="18" cy="18" r="16"></circle>
                                <circle class="circle-progress<?php if ($level == 'med') echo ' active med'; ?>" cx="18" cy="18" r="16" stroke-dasharray="50, 100"></circle>
                            </svg>
                            <strong>Med</strong>
                        </div>

                        <!-- High Circle -->
                        <div class="col">
                            <svg class="circle-indicator" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <circle class="circle-background<?php if ($level == 'high') echo ' high'; ?>" cx="18" cy="18" r="16"></circle>
                                <circle class="circle-progress<?php if ($level == 'high') echo ' active high'; ?>" cx="18" cy="18" r="16" stroke-dasharray="75, 100"></circle>
                            </svg>
                            <strong>High</strong>
                        </div>

                        <!-- Full Circle -->
                        <div class="col">
                            <svg class="circle-indicator" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <circle class="circle-background<?php if ($level == 'full') echo ' full'; ?>" cx="18" cy="18" r="16"></circle>
                                <circle class="circle-progress<?php if ($level == 'full') echo ' active full'; ?>" cx="18" cy="18" r="16" stroke-dasharray="100, 100"></circle>
                            </svg>
                            <strong>Full</strong>
                        </div>
                    </div>

                    <!-- Example usage in your modal -->
                    <div>
                        Current bin level: <strong><?php echo ucfirst($level); ?></strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="index.js"></script>

    <!-- Add these in your <head> or before </body> -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>
</body>

</html>