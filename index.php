<?php
require_once('../wp-load.php');
$path = '/pdf';
if(is_user_logged_in()){ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventry Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <style>body{font-family: Helvetica, sans-serif;}</style>
</head>
<body class="d-flex align-items-center justify-content-center pt-5">
    <div class="container d-flex flex-column align-items-center justify-content-center">
        <img src="logo.png" class="d-block" alt="" srcset="">
        <h2 class="h4 text-center text-capitalize">Enter Details for Shipping Label</h2>
    <form action="shipping-label.php" target="_blank" method="post" class="d-flex flex-column">
        <label for="order_number" class="pb-3">Order number
        <input type="number" name="order_number" id="order_number" class="form-control" required></label>
        <label for="weight" class="pb-3"> Weight of the box
        <input type="number" step="any" name="weight" id="weight" class="form-control" required>
    </label>
        <button class="btn btn-primary" type="submit"><i class="bi bi-file-earmark-pdf"></i> Print</button>
        </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" defer></script>
</body>
</html>


<?php
} else {
   echo "<script>location.href='".site_url()."/wp-login.php?redirect_to=".site_url().$path."/'</script>"; 
   }
