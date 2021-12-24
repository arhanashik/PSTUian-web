<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Database</div>
                <!-- Default collapsed menu: add .collapsed to .nav-link and remove .show from .collapse class -->
                <!-- Default expanded menu: remove .collapsed to .nav-link and add .show from .collapse class -->
                <!-- Academic tables -->
                <a class="nav-link" id="nav-link-academic" href="#" data-bs-toggle="collapse" data-bs-target="#collapse-academic" aria-expanded="false" aria-controls="collapseAcademic">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Academic
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse show" id="collapse-academic" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link" href="faculty.php">Faculty</a>
                        <a class="nav-link" href="batch.php">Batch</a>
                        <a class="nav-link" href="student.php">Student</a>
                        <a class="nav-link" href="teacher.php">Teacher</a>
                        <a class="nav-link" href="course.php">Course</a>
                        <a class="nav-link" href="employee.php">Employee</a>
                    </nav>
                </div>
                <!-- Data tables -->
                <a class="nav-link collapsed" id="nav-link-data" href="#" data-bs-toggle="collapse" data-bs-target="#collapse-data" aria-expanded="false" aria-controls="collapseTables">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapse-data" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link" href="slider.php">Slider</a>
                        <a class="nav-link" href="check_in.php">Check In</a>
                        <a class="nav-link" href="check_in_location.php">Check In Location</a>
                        <a class="nav-link" href="donation.php">Donation</a>
                        <a class="nav-link" href="info.php">Info</a>
                    </nav>
                </div>
                <!-- Blood Donation tables -->
                <a class="nav-link collapsed" id="nav-link-blood-donation" href="#" data-bs-toggle="collapse" data-bs-target="#collapse-blood-donation" aria-expanded="false" aria-controls="collapseTables">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Blood Donation
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapse-blood-donation" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link" href="blood_donation_request.php">Request</a>
                        <a class="nav-link" href="blood_donation.php">Donation</a>
                    </nav>
                </div>
                <!-- Action tables -->
                <a class="nav-link collapsed" id="nav-link-action" href="#" data-bs-toggle="collapse" data-bs-target="#collapse-action" aria-expanded="false" aria-controls="collapseTables">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Action
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapse-action" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link" href="device.php">Device</a>
                        <a class="nav-link" href="auth.php">Authentication</a>    
                        <a class="nav-link" href="verification.php">Verification</a>    
                        <a class="nav-link" href="notification.php">Notification</a>
                        <a class="nav-link" href="user_inquiry.php">User Inquiry</a>
                        <a class="nav-link" href="password_reset.php">Password Reset</a>
                        <a class="nav-link" href="email.php">Email</a>
                        <a class="nav-link" href="log.php">Log</a>
                    </nav>
                </div>
                <div class="sb-sidenav-menu-heading">System</div>
                <a class="nav-link" href="admin.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                    Admin
                </a>
                <!-- <a class="nav-link" href="device.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-mobile-alt"></i></div>
                    Device
                </a> -->
                <!-- <a class="nav-link" href="auth.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-unlock-alt"></i></div>
                    Authentication
                </a> -->
                <!-- <a class="nav-link" href="user_inquiry.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-question-circle"></i></div>
                    User Inquiry
                </a> -->
                <!-- <a class="nav-link" href="notification.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-bell"></i></div>
                    Notification
                </a> -->
                <!-- <a class="nav-link" href="log.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Log
                </a> -->
                <a class="nav-link" href="settings.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                    Settings
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?php echo $admin['email']; ?>
        </div>
    </nav>
</div>