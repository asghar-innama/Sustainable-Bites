<?php
session_start();
include '../connection.php';

// Use the correct database connection details
$connection = mysqli_connect("localhost:3307", "root", "", "demo");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$msg = 0;

if (isset($_POST['sign'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sanitized_emailid = mysqli_real_escape_string($connection, $email);
    $sanitized_password = mysqli_real_escape_string($connection, $password);

    $sql = "SELECT * FROM admin WHERE email='$sanitized_emailid'";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Error executing query: " . mysqli_error($connection));
    }

    $num = mysqli_num_rows($result);

    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($sanitized_password, $row['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['location'] = $row['location'];
                $_SESSION['Aid'] = $row['Aid'];
                header("location: admin.php");
            } else {
                $msg = 1;
            }
        }
    } else {
        echo "<h1><center>Account does not exist</center></h1>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../home.css">
    <link rel="stylesheet" href="delivery.css">
</head>
<body>
<header>
    <div class="logo">Sustainable <b style="color: #FFD700;">Bites</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class "line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="openmap.php">Map</a></li>
            <li><a href="deliverymyord.php">Orders</a></li>
        </ul>
    </nav>
</header>
<br>
<script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
        navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>
<style>
    .itm {
        background-color: white;
        display: grid;
    }
    .itm img {
        width: 400px;
        height: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    p {
        text-align: center;
        font-size: 30px;
        color: black;
        margin-top: 50px;
    }
    a {
        background-color: #FFD700;
        color: black;
    }
    @media (max-width: 767px) {
        .itm img {
            width: 350px;
            height: 350px;
        }
    }
</style>

<h2><center>Welcome <?php echo $_SESSION['name']; ?></center></h2>

<div class="itm">
    <img src="../img/rider.gif" alt="" width="400" height="400">
</div>

<div class="get">
    <?php
    // Define the SQL query to fetch unassigned orders
    $sql = "SELECT fd.Fid AS Fid, fd.location AS cure, fd.name, fd.phoneno, fd.date, fd.delivery_by, fd.address as From_address, ad.name AS delivery_person_name, ad.address AS To_address FROM food_donations fd LEFT JOIN admin ad ON fd.assigned_to = ad.Aid WHERE assigned_to IS NOT NULL AND delivery_by IS NULL AND fd.location='" . $_SESSION['location'] . "';";

    // Execute the query
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        
        die("Error executing query: " . mysqli_error($connection));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    ?>

    <div class="log">
        <a href="deliverymyord.php" style="background-color: #FFD700;">My Orders</a>
    </div>

    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr style="background-color: #FFD700;">
                        <th>Name</th>
                        <th>Phoneno</th>
                        <th>Date/Time</th>
                        <th>Pickup Address</th>
                        <th>Delivery Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row) { ?>
                        <tr>
                            <td data-label="Name"><?= $row['name'] ?></td>
                            <td data-label="Phoneno"><?= $row['phoneno'] ?></td>
                            <td data-label="Date/Time"><?= $row['date'] ?></td>
                            <td data-label="Pickup Address"><?= $row['From_address'] ?></td>
                            <td data-label="Delivery Address"><?= $row['To_address'] ?></td>
                            <td data-label="Action">
                                <?php if ($row['delivery_by'] === null) { ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="order_id" value="<?= $row['Fid'] ?>">
                                        <input type="hidden" name="delivery_person_id" value="<?= $_SESSION['Did'] ?>">
                                        <button type="submit" name="food">Take Order</button>
                                    </form>
                                <?php } else if ($row['delivery_by'] === $_SESSION['Did']) { ?>
                                    Order assigned to you
                                <?php } else { ?>
                                    Order assigned to another delivery person
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
