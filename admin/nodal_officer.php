<?php
session_start();

// Include database configuration (already has $dbh)
include('inc/config.php'); // <- this replaces the manual $dbh setup

// Check login status - MUST be before ANY output
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Handle Delete operation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM nodal_officers WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    if ($query->execute()) {
        $successMsg = "Nodal Officer deleted successfully!";
    } else {
        $errorMsg = "Failed to delete Nodal Officer.";
    }
}

// Handle Add/Edit operations
$editMode = false;
$editId = 0;
$editRole = '';
$editNameDesignation = '';

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $editMode = true;
    $editId = intval($_GET['edit']);
    $sql = "SELECT * FROM nodal_officers WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $editId, PDO::PARAM_INT);
    $query->execute();
    if ($query->rowCount() > 0) {
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $editRole = $row['role'];
        $editNameDesignation = $row['name_designation'];
    } else {
        $editMode = false;
        $errorMsg = "Record not found.";
    }
}

if (isset($_POST['submit'])) {
    $role = trim($_POST['role']);
    $nameDesignation = trim($_POST['name_designation']);
    
    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        // Update existing record
        $editId = intval($_POST['edit_id']);
        $sql = "UPDATE nodal_officers SET role = :role, name_designation = :name_designation WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        $query->bindParam(':name_designation', $nameDesignation, PDO::PARAM_STR);
        $query->bindParam(':id', $editId, PDO::PARAM_INT);
        if ($query->execute()) {
            $successMsg = "Nodal Officer updated successfully!";
            $editMode = false;
        } else {
            $errorMsg = "Failed to update Nodal Officer.";
        }
    } else {
        // Insert new record
        $sql = "INSERT INTO nodal_officers (role, name_designation) VALUES (:role, :name_designation)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        $query->bindParam(':name_designation', $nameDesignation, PDO::PARAM_STR);
        if ($query->execute()) {
            $successMsg = "Nodal Officer added successfully!";
        } else {
            $errorMsg = "Failed to add Nodal Officer.";
        }
    }
}
?>
<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>Admin-panel - Nodal Officer Management</title>

    <!-- Font awesome -->
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap4.css">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php 
        // Include sidebar
        include('inc/sidebar.php'); 
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('inc/top.php'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Nodal Officer Management</h1>
                    </div>

                    <!-- Display Success/Error Messages -->
                    <?php if(isset($successMsg)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $successMsg; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($errorMsg)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $errorMsg; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Add/Edit Form Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <?php echo $editMode ? 'Edit Nodal Officer' : 'Add New Nodal Officer'; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <?php if($editMode): ?>
                                    <input type="hidden" name="edit_id" value="<?php echo $editId; ?>">
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="role">Role <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="role" name="role" 
                                           value="<?php echo htmlspecialchars($editRole); ?>" required>
                                    <small class="form-text text-muted">e.g., Chairman, Secretary, Coordinator, etc.</small>
                                </div>
                                <div class="form-group">
                                    <label for="name_designation">Nodal Officer Name & Designation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name_designation" name="name_designation" 
                                           value="<?php echo htmlspecialchars($editNameDesignation); ?>" required>
                                    <small class="form-text text-muted">e.g., Dr. John Doe (Professor & Head)</small>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <?php echo $editMode ? 'Update Nodal Officer' : 'Add Nodal Officer'; ?>
                                </button>
                                <?php if($editMode): ?>
                                    <a href="nodal_officer.php" class="btn btn-secondary">Cancel</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <!-- Data Table Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Nodal Officers List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Role</th>
                                            <th>Nodal Officer Name & Designation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM nodal_officers ORDER BY id DESC";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $row) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($row->role); ?></td>
                                                    <td><?php echo htmlentities($row->name_designation); ?></td>
                                                    <td>
                                                        <a href="nodal_officer.php?edit=<?php echo $row->id; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row->id; ?>)" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $cnt++;
                                            }
                                        } else { ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No Nodal Officers found.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
        </div>
    </div>

    <?php include('inc/footer.php'); ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this Nodal Officer? This action cannot be undone.')) {
                window.location.href = 'nodal_officer.php?action=delete&id=' + id;
            }
        }
    </script>
</body>
</html>