<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>IT Projektas - Dashboard</title>
        <link href="css/dashboard_style.css" rel="stylesheet" type="text/css">
        <link href="lib/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head>
    <body class="loggedin">
        <?php include 'generic/navbar.php';?>
        <?php
            require_once "generic/config.php";
            $id = $_SESSION['id'];
            $sql = "SELECT * FROM vartotojai WHERE id=$id";
            $result = $link->query($sql);
            $row = $result->fetch_assoc();

            if($row['admin'] == 1){
                include 'admin/client_list.php';
            } elseif ($row['accountant'] == 1) {
                include 'accountant/bill_list.php';
            } else {
                include 'client/bill_list.php';
            }
        ?>
    </body>
</html>