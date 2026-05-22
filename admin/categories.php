<!DOCTYPE html>
<html lang="en">
<?php
include('../config.php');
requireAdmin();

// Handle form submissions
$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['add_category'])){
        $catName = mysqli_real_escape_string($conn, $_POST['catName']);
        $q = "INSERT INTO categories (catName) VALUES ('$catName')";
        if(mysqli_query($conn, $q)){
            $message = '<div class="alert alert-success">Category added successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
        }
    }
    
    // Handle category deletion
    foreach($_POST as $k => $v){
        if(substr($k, 0, 4) == 'btn_'){
            $catid = intval(substr($k, 4));
            $deleteQuery = "DELETE FROM categories WHERE catid = $catid";
            if(mysqli_query($conn, $deleteQuery)){
                $message = '<div class="alert alert-success">Category deleted successfully!</div>';
            } else {
                $message = '<div class="alert alert-danger">Error: Cannot delete category that has items assigned.</div>';
            }
            break;
        }
    }
}

include('head.php');
?>
<body>
    <?php include('nav.php'); ?>
    
    <div class="admin-container">
        <h1 class="mt-4"><i class="fa fa-folder"></i> Categories Management</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Categories</li>
        </ol>
        
        <?= $message ?>
        
        <!-- Add Category Form -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-plus"></i> Add New Category
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="catName" class="form-control" 
                               placeholder="e.g., Web Development" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" name="add_category" class="btn btn-success form-control">
                            <i class="fa fa-plus"></i> Add Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Categories List -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-table"></i> All Categories
            </div>
            <div class="card-body">
                <form method="post">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Category ID</th>
                                <th>Category Name</th>
                                <th>Items Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            global $conn;
                            $q = "SELECT c.*, COUNT(i.itemid) as item_count 
                                  FROM categories c 
                                  LEFT JOIN items i ON c.catid = i.itemCategory 
                                  GROUP BY c.catid 
                                  ORDER BY c.catid";
                            $results = mysqli_query($conn, $q);
                            
                            while($row = mysqli_fetch_array($results)){
                                extract($row);
                                echo "<tr>";
                                echo "<td>$catid</td>";
                                echo "<td><strong>" . htmlspecialchars($catName) . "</strong></td>";
                                echo "<td>$item_count items</td>";
                                echo "<td>";
                                echo "<button type='submit' name='btn_$catid' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this category?\")'>";
                                echo "<i class='fa fa-trash'></i> Delete";
                                echo "</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    
    <?php include('footer.php'); ?>
</body>
</html>
