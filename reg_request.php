<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // Fetch pending registrations
    $sql = "SELECT * FROM tblstudents WHERE Status = 'Pending'";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>MERU UNIVERSITY OF SCIENCE AND TECHNOLOGY | Manage Reg Students</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Registration Request</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Regisration Request List
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Registartion Number</th>
                                            <th>Email id </th>
                                            <th>Mobile Number</th>
                                            <th>Reg Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($results as $result) {
                                            // Skip this row if the status is "Approved"
                                            if ($result->Status == 'Approved') {
                                                  continue;
                                             }
                                        ?>
                                            <tr class="odd gradeX">
                                                <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                <td class="center"><?php echo htmlentities($result->StudentId); ?></td>
                                                <td class="center"><?php echo htmlentities($result->EmailId); ?></td>
                                                <td class="center"><?php echo htmlentities($result->MobileNumber); ?></td>
                                                <td class="center"><?php echo htmlentities($result->RegDate); ?></td>
                                                <td class="center"><?php if($result->Status=='Pending'){
                                                    echo htmlentities("Pending");
                                                }else{
                                                    echo htmlentities("Approved");
                                                }                                                                                
                                                ?></td>
                                                <td class="center">
                                                    <!-- You can add any additional actions related to pending registrations here -->
                                                <?php if ($result->Status == 'Pending') { ?>
                                                         <a href="approve.php?id=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to approve this registration?');">
                                                         <button class="btn btn-success"> Approve</button>
                                                        </a>
                                                        <a href="reject.php?id=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to reject this registration?');"">
                                                         <button class="btn btn-danger"> Reject</button>
                                                         </a>
                                                         <?php } else { ?>
                                                          <!-- Additional actions for approved registrations if needed -->
                                                        <?php } ?>
                                            </tr>
                                        <?php
                                            $cnt = $cnt + 1;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
<?php } ?>