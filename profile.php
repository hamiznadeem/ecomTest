<?php
include "inc_header.php";
$countries_json = file_get_contents("countries.json");
$countries = json_decode($countries_json, true);

$firstNameErr = $lastNameErr = $countryErr = $addressErr = $phoneErr = $emailErr = $dobErr = "";
$firstName = $lastName = $about = $company = $country = $address = $phone = $email = $dob = $gender = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_profile_btn"])) {
    $all_input_ok = true;
    if (empty($_POST["firstName"])) {
        $firstNameErr = "First Name required";
        $all_input_ok = false;
    } else {
        $firstName = safe_input($_POST["firstName"]);
    }

    if (empty($_POST["lastName"])) {
        $lastNameErr = "Last Name required";
        $all_input_ok = false;
    } else {
        $lastName = safe_input($_POST["lastName"]);
    }

    $about = safe_input($_POST["about"]);
    $company = safe_input($_POST["company"]);
    $gender = safe_input($_POST["gender"]);

    if (empty($_POST["country"])) {
        $countryErr = "Country is required";
        $all_input_ok = false;
    } else {
        $country = safe_input($_POST["country"]);
    }

    if (empty($_POST["address"])) {
        $addressErr = "Address is required";
        $all_input_ok = false;
    } else {
        $address = safe_input($_POST["address"]);
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Phone Number is required";
        $all_input_ok = false;
    } else {
        $phone = safe_input($_POST["phone"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $all_input_ok = false;
    } else {
        $email = safe_input($_POST["email"]);
    }

    if (empty($_POST["DOB"])) {
        $dobErr = "Date of Birth is required";
        $all_input_ok = false;
    } else {
        $dob = safe_input($_POST["DOB"]);
    }
    if($all_input_ok){
        $sql = "UPDATE `employees` SET `FirstName`=?, `LastName`=?, `About`=?, `Company`=?, `Gender`=?, `Country`=?, `Address`=?, `Phone`=?, `Email`=?, `BirthDate`=? WHERE EmployeeID=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            'ssssssssssi', $firstName, $lastName, $about,$company,$gender, $country, $address, $phone, $email, $dob, $_SESSION["user_id"]);
        if(!mysqli_stmt_execute($stmt)){
            echo "<script>alert('Error Occurred".mysqli_stmt_error($stmt)."');</script>";
        }else{
            header("Location: profile.php");
            exit();
        }
    }
}     

$oldPassword = $newPassword = $renewPassword = "";
$oldPasswordErr = $newPasswordErr = $renewPasswordErr = "";
if($_SERVER ["REQUEST_METHOD"] == "POST" && isset($_POST["change_password_btn"]) ) {
        $all_password_input_ok = true;

        if (empty($_POST["password"])) {
            $oldPasswordErr = "Enter Your old Password";
            $all_password_input_ok = false;
        } else {
            $oldPassword = safe_input($_POST["password"]);
        }

        if (empty($_POST["newPassword"])) {
            $newPasswordErr = "Enter New Password";
            $all_password_input_ok = false;
        } else {
            $newPassword = safe_input($_POST["newPassword"]);
        }

        if (empty($_POST["renewPassword"])) {
            $renewPasswordErr = " Re-Enter New Password";
            $all_password_input_ok = false;
        } else {
            $renewPassword = safe_input($_POST["renewPassword"]);
        }

        if($all_password_input_ok){
            $sql = "SELECT * FROM logins WHERE emp_id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $_SESSION["user_id"]);
            mysqli_stmt_execute($stmt);
            $login_row = mysqli_fetch_array(mysqli_stmt_get_result($stmt));
            if (password_verify($oldPassword, $login_row["password"])){

                if($newPassword === $renewPassword){
                    $sql = "UPDATE `logins` SET `password`=? WHERE emp_id=?";
                    $stmt = mysqli_prepare($conn, $sql);
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $_SESSION["user_id"]);
                    if(mysqli_stmt_execute($stmt)){
                        echo "<script>alert('Password Changed Successfully');</script>";
                    }else{
                        echo "<script>alert('Error Occurred".mysqli_stmt_error($stmt)."');</script>";
                    }
                }else{
                    $renewPasswordErr = "New Password and Re-enter New Password do not match";
                }

            }else{
                $oldPasswordErr = "incorrect old password";
            }
        } 
}



$sql = "SELECT * FROM `employees` WHERE EmployeeID=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$emp_row = mysqli_fetch_array(mysqli_stmt_get_result($stmt));

$sql = "SELECT * FROM `role` WHERE role_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $_SESSION["role_id"]);
mysqli_stmt_execute($stmt);
$role_row = mysqli_fetch_array(mysqli_stmt_get_result($stmt));

?>

<script>

</script>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Admin Panel</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="<?php echo $emp_row["Photo"] ?>" alt="<?php echo $emp_row["FirstName"] . " " . $emp_row["LastName"] ?>" class="rounded-circle">
                        <h2><?php echo $emp_row["FirstName"] . " " . $emp_row["LastName"] ?></h2>
                        <h3><?php echo $role_row["description"] ?></h3>
                        <div class="social-links mt-2">
                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">About</h5>
                                <p class="small fst-italic"><?php echo $emp_row["About"] ?></p>

                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $emp_row["FirstName"] . " " . $emp_row["LastName"] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Company</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $emp_row["Company"] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Job</div>
                                    <div class="col-lg-9 col-md-8"> <?php echo $role_row["description"] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Country</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $emp_row["Country"] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Address</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $emp_row["Address"] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $emp_row["Phone"] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $emp_row["Email"] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Date of Birth</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $emp_row["BirthDate"] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Gender</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $emp_row["Gender"] ?></div>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" enctype="multipart/form-data">
                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                        <div class="col-md-8 col-lg-9">
                                            <img src="<?php echo $emp_row["Photo"] ?>" alt="<?php echo $emp_row["FirstName"] . " " . $emp_row["LastName"] ?>">
                                            <div class="pt-2">
                                                <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="firstName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($firstNameErr) ? '<p class="text-danger">' . $firstNameErr . '</p>' : "" ?>
                                            <input name="firstName" type="text" class="form-control" id="firstName" value="<?php echo !empty($firstName) ? $firstName : $emp_row["FirstName"] ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="lastName" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($lastNameErr) ? '<p class="text-danger">' . $lastNameErr . '</p>' : "" ?>
                                            <input name="lastName" type="text" class="form-control" id="lastName" value="<?php echo !empty($lastName) ? $lastName : $emp_row["LastName"] ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                                        <div class="col-md-8 col-lg-9">
                                            <textarea name="about" class="form-control" id="about" style="height: 100px"><?php echo !empty($about) ? $about : $emp_row["About"] ?></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="company" type="text" class="form-control" id="company" value="<?php echo !empty($company) ? $company : $emp_row["Company"] ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($countryErr) ? '<p class="text-danger">' . $countryErr . '</p>' : "" ?>
                                            <select name="country" id="country" class="form-control">
                                                <option value="<?php echo !empty($country) ? $country : $emp_row["Country"] ?>"><?php echo !empty($country) ? $country : $emp_row["Country"] ?></option>
                                                <?php foreach ($countries as $country): ?>
                                                    <option value="<?= htmlspecialchars($country['code']) ?>">
                                                        <?= htmlspecialchars($country['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($addressErr) ? '<p class="text-danger">' . $addressErr . '</p>' : "" ?>
                                            <input name="address" type="text" class="form-control" id="Address" value="<?php echo !empty($address) ? $address : $emp_row["Address"] ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($phoneErr) ? '<p class="text-danger">' . $phoneErr . '</p>' : "" ?>
                                            <input name="phone" type="tel" class="form-control" id="Phone" value="<?php echo !empty($phone) ? $phone : $emp_row["Phone"] ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($emailErr) ? '<p class="text-danger">' . $emailErr . '</p>' : "" ?>
                                            <input name="email" type="email" class="form-control" id="Email" value="<?php echo !empty($email) ? $email : $emp_row["Email"] ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="gender" class="col-md-4 col-lg-3 col-form-label">Date of Birth</label>
                                        <div class="col-md-8 col-lg-9">
                                            <select class="form-control" name="gender" id="gender">
                                                <?php if($emp_row["Gender"] == "Male"){?>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="other">Other</option>
                                                <?php ;}else if($emp_row["Gender"] == "Female"){?>
                                                    <option value="Female">Female</option>
                                                    <option value="Male">Male</option>
                                                    <option value="other">Other</option>
                                                <?php ;}else{?>
                                                    <option value="other">Other</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                <?php ;}?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="DOB" class="col-md-4 col-lg-3 col-form-label">Date of Birth</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($dobErr) ? '<p class="text-danger">' . $dobErr . '</p>' : "" ?>
                                            <input name="DOB" type="date" class="form-control" id="DOB" value="<?php echo !empty($dob) ? $dob : $emp_row["BirthDate"] ?>">
                                        </div>
                                    </div>

                                    <!-- <div class="row mb-3">
                                        <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="twitter" type="text" class="form-control" id="Twitter" value="https://twitter.com/#">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="facebook" type="text" class="form-control" id="Facebook" value="https://facebook.com/#">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="instagram" type="text" class="form-control" id="Instagram" value="https://instagram.com/#">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="linkedin" type="text" class="form-control" id="Linkedin" value="https://linkedin.com/#">
                                        </div>
                                    </div> -->

                                    <div class="text-center">
                                        <button type="submit" name="edit_profile_btn" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-settings">

                                <!-- Settings Form -->
                                <form>

                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="changesMade" checked>
                                                <label class="form-check-label" for="changesMade">
                                                    Changes made to your account
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="newProducts" checked>
                                                <label class="form-check-label" for="newProducts">
                                                    Information on new products and services
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="proOffers">
                                                <label class="form-check-label" for="proOffers">
                                                    Marketing and promo offers
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                                                <label class="form-check-label" for="securityNotify">
                                                    Security alerts
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form><!-- End settings Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" enctype="multipart/form-data">

                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($oldPasswordErr) ? '<p class="text-danger">' . $oldPasswordErr . '</p>' : "" ?>
                                            <input name="password" type="password" class="form-control" id="currentPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($newPasswordErr) ? '<p class="text-danger">' . $newPasswordErr . '</p>' : "" ?>
                                            <input name="newPassword" type="password" class="form-control" id="newPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo !empty($renewPasswordErr) ? '<p class="text-danger">' . $renewPasswordErr . '</p>' : "" ?>
                                            <input name="renewPassword" type="password" class="form-control" id="renewPassword">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" name="change_password_btn" class="btn btn-primary">Change Password</button>
                                    </div>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</main><!-- End #main -->

<?php
include "inc_footer.php"; 
?>