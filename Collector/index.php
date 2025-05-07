<?php include 'data.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="index.css">
</head>

<body>

    <nav class="col-md-3 col-lg-2 bg-dark text-white p-4 min-vh-50 navigation">

        <!-- Top Row: Collector + Menu button (small screens only) -->
        <div class="d-flex justify-content-between align-items-center mb-3 d-md-none">
            <!-- Collector title on the left -->
            <h4 class="m-0 text-white">⚙️ Collector</h4>

            <!-- Menu button on the right (label "Menu" for small screens) -->
            <div class="dropdown ms-auto">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-list me-2"></i> Menu
                </button>
                <ul class="dropdown-menu collapse w-100 bg-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item text-white" href="#" onclick="toggleContainer('dashboard-container')">
                            <i class="bi bi-house-door me-2"></i> Dashboard
                        </a></li>
                    <li><a class="dropdown-item text-white" href="#" onclick="toggleContainer('schedule-section')">
                            <i class="bi bi-calendar me-2"></i> Schedule
                        </a></li>
                    <li><a class="dropdown-item text-white" href="#" onclick="toggleContainer('map-container')">
                            <i class="bi bi-map me-2"></i> Location
                        </a></li>

                    <li><a class="dropdown-item text-danger text-center" href="#" onclick="logout()">Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Collector title for large screens -->
        <h4 class="d-none d-md-block">⚙️ Collector</h4>

        <!-- Navigation Items for Large Screens -->
        <ul class="nav flex-column mt-3 nav-menu d-none d-md-block">
            <li class="nav-item">
                <a href="#" class="nav-link text-white small" onclick="toggleContainer('dashboard-container')">
                    <i class="bi bi-house-door me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-white small" onclick="toggleContainer('schedule-section')">
                    <i class="bi bi-calendar me-2"></i> Schedule
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-white small" onclick="toggleContainer('map-container')">
                    <i class="bi bi-map me-2"></i> Location
                </a>
            </li>


            <li class="nav-item">
                <button class="btn btn-danger w-100 logout " onclick="logout()">
                    <i class="bi bi-box-arrow-right me-2 "></i> Logout
                </button>
            </li>
        </ul>

    </nav>


    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Dashboard Section -->
        <div id="dashboard-container" class="section">
            <h2 class="dashboard">Dashboard</h2>
            <?php if ($collector): ?>
                <div class="card details">
                    <div class="card-header">
                        <h3>Welcome, <?php echo htmlspecialchars($collector['name']); ?>!</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($collector['email']); ?></p>
                        <p><strong>Collector ID:</strong> <?php echo htmlspecialchars($collector['collector_id']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($collector['address']); ?></p>
                        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($collector['contact_number']); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">Collector information not found.</div>
            <?php endif; ?>
        </div>

        <!-- Schedule Section -->
        <div id="schedule-section" class="section d-none">
            <h2>Schedule</h2>
            <table id="schedule-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Trashcan</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedule as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['trashcan']); ?></td>
                            <td><?php echo htmlspecialchars($item['location']); ?></td>
                            <td><?php echo htmlspecialchars($item['date']); ?></td>
                            <td><?php echo htmlspecialchars($item['status']); ?></td>
                            <td>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                                    <select name="status" class="form-select" required>
                                        <option value="complete" <?php echo ($item['status'] == 'complete') ? 'selected' : ''; ?>>Complete</option>
                                        <option value="uncomplete" <?php echo ($item['status'] == 'uncomplete') ? 'selected' : ''; ?>>Uncomplete</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Update Status</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Map Container -->
        <div id="map-container" class="section d-none"
            style=" background-image: url('../bigaamap.png'); background-size: cover; background-position: center; position: relative;">
            <div class="container h-100 d-flex justify-content-center align-items-center">
                <div class="text-center text-white">
                    <h2>Map</h2>
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
        <div class="bottom-links text-center mt-4">
            <a href="../help/Introduction.php" target="_blank">Help</a> /
            <a href="#" data-bs-toggle="modal" data-bs-target="#aboutUsModal">About Us</a>
        </div>
    </div>

    <!-- About Us Modal -->
    <div class="modal fade" id="aboutUsModal" tabindex="-1" role="dialog" aria-labelledby="aboutUsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">About Us</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h1>ABOUT US</h1>
                    <p>Trashcan Monitoring System helps you manage garbage collection efficiently.</p>
                    <div class="about">
                        <img src="../css/trashbin.png" alt="Trashcan Image" class="img-fluid mb-3">
                        <h2>Trashcan Monitoring System</h2>
                        <p>
                            Even the smallest actions can create a wave of positive change.
                            We are united by our passion to protect the planet and promote a greener future.
                        </p>
                    </div>
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

    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Status Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessage">
                    <!-- Message injected by JS -->
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['modal_message'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modal = new bootstrap.Modal(document.getElementById("statusModal"));
                document.getElementById("modalMessage").innerText = <?= json_encode($_SESSION['modal_message']) ?>;
                modal.show();
            });
        </script>
        <?php unset($_SESSION['modal_message']); ?>
    <?php endif; ?>


    <script src="index.js"></script>
</body>

</html>