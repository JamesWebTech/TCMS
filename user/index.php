<?php
include 'data.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <title>Schedule Management</title>
</head>

<body>
    <nav class="col-md-3 col-lg-2 bg-dark text-white p-4 min-vh-50 navigation">
        <!-- Small screens only -->
        <div class="d-flex justify-content-between align-items-center mb-3 d-md-none">
            <h4 class="m-0 text-white">⚙️ User</h4>
            <div class="dropdown ms-auto">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-list me-2"></i> Menu
                </button>
                <ul class="dropdown-menu w-100 bg-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item text-white nav-link" href="#" data-target="dashboard-container" onclick="toggleContainer('dashboard-container')">
                            <i class="bi bi-house-door me-2"></i> Dashboard</a></li>
                    <li><a class="dropdown-item text-white nav-link" href="#" data-target="map-container" onclick="toggleContainer('map-container')">
                            <i class="bi bi-map me-2"></i> Location</a></li>
                    <li><a class="dropdown-item text-danger text-center" href="#" onclick="logout()">Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Large screens only -->
        <h4 class="d-none d-md-block">⚙️ User</h4>

        <ul class="nav flex-column mt-3 nav-menu d-none d-md-block">
            <li class="nav-item">
                <a href="#" class="nav-link text-white small" data-target="dashboard-container" onclick="toggleContainer('dashboard-container')">
                    <i class="bi bi-house-door me-2"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-white small" data-target="map-container" onclick="toggleContainer('map-container')">
                    <i class="bi bi-map me-2"></i> Location</a>
            </li>
            <li class="nav-item">
                <button class="btn btn-danger w-100 logout" onclick="logout()">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout</button>
            </li>
        </ul>
    </nav>


    <!-- Main Content -->
    <div class="main-content">
        <div id="dashboard-container" class="section">
            <h2 class="dashboard">Dashboard</h2>

            <?php if ($user): ?>
                <!-- User Details Card -->
                <div class="card details">
                    <div class="card-header">
                        <h3>Welcome, <?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?>!</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contact']); ?></p>
                    </div>
                </div>

                <!-- Schedule Card -->
                <div class="card mt-4 schedule_card">
                    <div class="card-header">
                        <h4>Schedule</h4>
                    </div>
                    <div class="card-body schedule-body">
                        <div class="table-responsive">
                            <table class="table table-striped ">
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
            <?php else: ?>
                <div class="alert alert-danger">User information not found.</div>
            <?php endif; ?>
        </div>

    </div>



    <!-- Map Container -->
    <div id="map-container" class="section d-none"
        style=" background-image: url('../bigaamap.png'); background-size: cover; background-position: center; position: relative;">
        <div class="container h-100 d-flex justify-content-center align-items-center">
            <div class="text-center text-white">
                <h2 class="Map">Map</h2>
            </div>
        </div>

        <!-- Trash Bin Icon (Clickable) -->
        <img src="../trashbin.png" id="trash-bin" alt="Trash Bin"
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50px; cursor: pointer;"
            data-bs-toggle="modal" data-bs-target="#binModal">

        <!-- Trash Bin Icon (Clickable) -->
        <img src="../trashbin.png" id="trash-bin" alt="Trash Bin"
            style="position: absolute; top: 30%; left: 30%; transform: translate(-50%, -50%); width: 50px; cursor: pointer;"
            data-bs-toggle="modal" data-bs-target="#binModal">
    </div>

    <!-- Bottom Links -->
    <div class="bottom-links">
        <a href="../help/Introduction.php" target="_blank">Help</a> /
        <a href="#" data-bs-toggle="modal" data-bs-target="#aboutUsModal">About Us</a>
    </div>
    </div>
    </div>

    <!-- About Us Modal -->
    <div class="modal fade" id="aboutUsModal" tabindex="-1" role="dialog" aria-labelledby="aboutUsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="aboutUsModalLabel">About Us</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h1>ABOUT US</h1>
                    <p>Trashcan Monitoring System is here to help you make garbage collection easier.</p>
                    <section class="about">
                        <div class="about_image">
                            <img src="../css/trashbin.png" alt="Trashcan Image" class="img-fluid">
                        </div>
                        <div class="about-content">
                            <h2>Trashcan Monitoring System</h2>
                            <p class="dis">
                                We believe that even the smallest actions, when multiplied by many, can create a powerful wave of positive change.
                                That's why we've come together, united by a passion for protecting our planet.
                                Through collaboration and dedication, we strive to build a greener future, one step at a time.
                                Together, we can ensure a clean and healthy environment for ourselves and generations to come.
                            </p>
                        </div>
                    </section>
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
                                <circle class="circle-background" cx="18" cy="18" r="16"></circle>
                                <circle class="circle-progress empty" cx="18" cy="18" r="16" stroke-dasharray="100, 100"></circle>
                            </svg>
                            <strong>Empty</strong>
                        </div>

                        <!-- Medium Circle -->
                        <div class="col">
                            <svg class="circle-indicator" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <circle class="circle-background" cx="18" cy="18" r="16"></circle>
                                <circle class="circle-progress medium" cx="18" cy="18" r="16" stroke-dasharray="50, 100"></circle>
                            </svg>
                            <strong>Medium</strong>
                        </div>

                        <!-- High Circle -->
                        <div class="col">
                            <svg class="circle-indicator" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <circle class="circle-background" cx="18" cy="18" r="16"></circle>
                                <circle class="circle-progress high" cx="18" cy="18" r="16" stroke-dasharray="75, 100"></circle>
                            </svg>
                            <strong>High</strong>
                        </div>

                        <!-- Full Circle -->
                        <div class="col">
                            <svg class="circle-indicator" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <circle class="circle-background" cx="18" cy="18" r="16"></circle>
                                <circle class="circle-progress full" cx="18" cy="18" r="16" stroke-dasharray="100, 100"></circle>
                            </svg>
                            <strong>Full</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="index.js"></script>
</body>

</html>