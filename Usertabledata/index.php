<?php
include 'config/database.php';

$id=isset($_GET['id']);


 
// read current record's data
try {
    // prepare select query
    $query = "SELECT id, uname, email, password FROM users WHERE id = ? LIMIT 0,1";
    $stmt = $con->prepare( $query );
 
    // this is the first question mark
    $id=htmlspecialchars(strip_tags($_GET['id']));
    $stmt->bindParam(1, $id);

 
    // execute our query
    $stmt->execute();
 
    // store retrieved row to a variable
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // values to fill up our form
    $uname = $row['uname'];
    $email = $row['email'];
    $password = $row['password'];
}
 
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
     
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
         
    <!-- custom css -->
    <style>
      
    .m-r-1em{ margin-right:1em; }
    .m-b-1em{ margin-bottom:1em; }
    .m-l-1em{ margin-left:1em; }
    .mt0{ margin-top:0; }
    .btn-export{
        float: right;
        background-color: #6577b7;
        color: #fff;
    }


    .btn-search{
    margin-top: -34px;
    margin-left: 385px;
    float: right;
}
.form-control{
    width: 91%;

}
.btn-danger{
    float: right;
}
    </style>
 
</head>
<body>
 
    <!-- container -->
    <div class="container">
  
        <div class="page-header">
            <h1>Read Products</h1>
            <div class="row">
                <div class="col-md-8">

    <form action="" method="get">
        <input type="text" class="form-control" placeholder="Search for..." name="id" >
        <span class="input-group-btn">
            <button class="btn btn-search" type="submit"><i class="fa fa-search fa-fw"></i> Search</button>
        </span>
        <table class='table table-hover table-responsive table-bordered'>
<tr>
        <td>id</td>
        <td>username</td>
        <td>email</td>
        <td>password</td>
    </tr>
    <tr>
       <td><?php echo htmlspecialchars($id, ENT_QUOTES);  ?></td>
        <td><?php echo htmlspecialchars($uname, ENT_QUOTES);  ?></td>
        <td><?php echo htmlspecialchars($email, ENT_QUOTES);  ?></td>
        <td><?php echo htmlspecialchars($password, ENT_QUOTES);  ?></td>
    </tr>

   
</table>
    </form>
    </div>
    <div class="col-md-4">
        <button class="btn btn-primary btn-danger" onclick="exportTableToCSV('members.csv')">Export CSV</button>
    </div>
</div>
</div>
     
        <?php

include 'config/database.php';
// PAGINATION VARIABLES
// page is the current page, if there's nothing set, default is page 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;
// set records or rows of data per page
$records_per_page = 10;
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
   
// select data for current page
$query = "SELECT id, uname, email, password FROM users ORDER BY id DESC
    LIMIT :from_record_num, :records_per_page";
 
$stmt = $con->prepare($query);
$stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
$stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$num = $stmt->rowCount();
if($num>0){
    echo "<table class='table table-striped table-hover'>";//start table
 
    //creating our table heading
    echo "<tr>";
        echo "<th>id</th>";
        echo "<th>uname</th>";
        echo "<th>email</th>";
        echo "<th>password</th>";
    echo "</tr>";
  // retrieve our table contents
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    extract($row);
     
    // creating new table row per record
    echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$uname}</td>";
        echo "<td>{$email}</td>";
        echo "<td>{$password}</td>";
    echo "</tr>";
}
 
// end table
echo "</table>"
;

// PAGINATION
// count total number of rows
$query = "SELECT COUNT(*) as total_rows FROM users";
$stmt = $con->prepare($query);
 
// execute query
$stmt->execute();
 
// get total rows
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['total_rows'];
// paginate records
$page_url="index.php?";
include_once "paging.php";
}
else{
    echo "<div class='alert alert-danger'>No records found.</div>";
}

?>
    </div> <!-- end .container -->
     
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
   <script>
       function downloadCSV(csv, filename) {
        var csvFile;
        var downloadLink;

        // CSV file
        csvFile = new Blob([csv], {type: "text/csv"});

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Hide download link
        downloadLink.style.display = "none";

        // Add the link to DOM
        document.body.appendChild(downloadLink);

        // Click download link
        downloadLink.click();
    }
function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
        csv.push(row.join(","));        
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}
   </script>
<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
<!-- confirm delete record will be here -->
</body>
</html>
