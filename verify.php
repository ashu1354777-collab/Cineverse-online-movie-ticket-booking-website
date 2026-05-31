<?php
include "connection.php";
session_start();

// variables
$fname = $_POST['fName'];
$lname = $_POST['lName'];
$email = $_POST['email'];
$mobile = $_POST['pNumber'];
$theatre = $_POST['theatre'];
$type = $_POST['type'];
$date = $_POST['date'];
$time = $_POST['hour'];
$movieid = (int)$_POST['movie_id'];
$ticket_qty = isset($_POST['ticket_qty']) ? max(1, min(10, (int)$_POST['ticket_qty'])) : 1;

// price matrix (can be moved to DB later)
if ($theatre == "main-hall") {
    $ticket_price = 200;
} elseif ($theatre == "vip-hall") {
    $ticket_price = 500;
} elseif ($theatre == "private-hall") {
    $ticket_price = 900;
} else {
    $ticket_price = 200;
}

$tx_amount = number_format($ticket_price * $ticket_qty, 2, '.', '');

$order = "ARVR" . rand(10000, 99999999);
$cust  = "CUST" . rand(1000, 999999);

//sessions
$_SESSION['ORDERID'] = $order;


//conditions
if (!isset($_POST['submit'])) {
    echo "<script>alert('You are Not Suppose to come Here Directly');window.location.href='index.php';</script>";
}

if (isset($_POST['submit'])) {

    $amount_status = 'Pending';
    $stmt = mysqli_prepare($con, "INSERT INTO bookingtable(`movieID`, `bookingTheatre`, `bookingType`, `bookingDate`, `bookingTime`, `bookingFName`, `bookingLName`, `bookingPNumber`, `bookingEmail`,`amount`, `ORDERID`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issssssssss", $movieid, $theatre, $type, $date, $time, $fname, $lname, $mobile, $email, $amount_status, $order);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$result) {
        echo "<script>alert('Booking failed - please retry'); window.location.href='booking.php?id=$movieid';</script>";
        exit;
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Likesh Sahu</title>
    <script src="_.js "></script>
</head>

<body>
    <center>
        <br><br>
        <h1>Proceed for Payment </h1>
        <br><br>

        <form method="post" action="pgRedirect.php">
            <table border="1" style="text-align: center;">
                <tbody>
                    <tr>
                        <th>S.No</th>
                        <th>Label</th>
                        <th>Value</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td><label>ORDER_ID::*</label></td>
                        <td><?php echo $order; ?>
                            <input type="hidden" name="ORDER_ID" value="<?php echo $order; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td><label>Name</label></td>
                        <td><?php echo $_POST['fName'] . " " . $_POST['lName']; ?></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><label>Website ::*</label></td>
                        <td>
                            <?php echo "Cineverse"; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><label>THEATRE ::*</label></td>
                        <td>
                            <?php echo $_POST['theatre']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><label>TYPE ::*</label></td>
                        <td>
                            <?php echo $_POST['type']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td><label>Tickets Qty</label></td>
                        <td><?php echo $ticket_qty; ?></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td><label>Ticket Price</label></td>
                        <td><?php echo number_format($ticket_price,2); ?> INR</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td><label>Total Amount*</label></td>
                        <td>
                            <input type="text" name="TXN_AMOUNT_DISPLAY" value="<?php echo $tx_amount; ?>" readonly>
                            <input type="hidden" name="TXN_AMOUNT" value="<?php echo $tx_amount; ?>">
                            <input type="hidden" name="CUST_ID" value="<?php echo $cust; ?>">
                            <input type="hidden" name="INDUSTRY_TYPE_ID" value="retail">
                            <input type="hidden" name="CHANNEL_ID" value="WEB">

                        </td>
                    </tr>


                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <button value="Book Now!" type="submit" class="btn btn-danger">Pay Now!</button>
                            <!-- <input value="CheckOut" type="submit"\tonclick=""></td> -->
                    </tr>
                </tbody>
            </table>
            * - Mandatory Fields
        </form>
    </center>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>