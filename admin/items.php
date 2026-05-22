<!DOCTYPE html>
<html lang="en">
<?php
include('../config.php');
requireAdmin();

// Handle form submissions
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $itemName = mysqli_real_escape_string($conn, $_POST['itemName']);
                $itemPrice = floatval($_POST['itemPrice']);
                $itemDescription = mysqli_real_escape_string($conn, $_POST['itemDescription']);
                $itemCategory = intval($_POST['itemCategory']);
                
                $q = "INSERT INTO items (itemName, itemPrice, itemDescription, itemCategory) 
                      VALUES ('$itemName', $itemPrice, '$itemDescription', $itemCategory)";
                
                if(mysqli_query($conn, $q)){
                    $message = '<div class="alert alert-success">Item added successfully!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                }
                break;
                
            case 'edit':
                $itemid = intval($_POST['itemid']);
                $itemName = mysqli_real_escape_string($conn, $_POST['itemName']);
                $itemPrice = floatval($_POST['itemPrice']);
                $itemDescription = mysqli_real_escape_string($conn, $_POST['itemDescription']);
                $itemCategory = intval($_POST['itemCategory']);
                
                $q = "UPDATE items 
                      SET itemName = '$itemName', 
                          itemPrice = $itemPrice, 
                          itemDescription = '$itemDescription', 
                          itemCategory = $itemCategory 
                      WHERE itemid = $itemid";
                
                if(mysqli_query($conn, $q)){
                    $message = '<div class="alert alert-success">Item updated successfully!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                }
                break;
                
            case 'delete':
                $itemid = intval($_POST['itemid']);
                $q = "DELETE FROM items WHERE itemid = $itemid";
                
                if(mysqli_query($conn, $q)){
                    $message = '<div class="alert alert-success">Item deleted successfully!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                }
                break;
        }
    }
}

// Get item for editing if edit mode
$editItem = null;
if(isset($_GET['edit'])){
    $editId = intval($_GET['edit']);
    $editQuery = "SELECT * FROM items WHERE itemid = $editId";
    $editResult = mysqli_query($conn, $editQuery);
    $editItem = mysqli_fetch_assoc($editResult);
}

// Get all categories for dropdown
$catQuery = "SELECT * FROM categories ORDER BY catName";
$catResult = mysqli_query($conn, $catQuery);
$categories = [];
while($cat = mysqli_fetch_assoc($catResult)){
    $categories[] = $cat;
}

include('head.php');
?>
<body>
    <?php include('nav.php'); ?>
    
    <div class="admin-container">
        <h1 class="mt-4"><i class="fa fa-list"></i> Items Management</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Items</li>
        </ol>
        
        <?= $message ?>
        
        <!-- Add/Edit Item Form -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-<?= $editItem ? 'edit' : 'plus' ?>"></i> 
                <?= $editItem ? 'Edit Item' : 'Add New Item' ?>
                <?php if($editItem): ?>
                    <a href="items.php" class="btn btn-secondary btn-sm" style="float: right;">
                        <i class="fa fa-times"></i> Cancel Edit
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <input type="hidden" name="action" value="<?= $editItem ? 'edit' : 'add' ?>">
                    <?php if($editItem): ?>
                        <input type="hidden" name="itemid" value="<?= $editItem['itemid'] ?>">
                    <?php endif; ?>
                    
                    <div class="col-md-6">
                        <label class="form-label">Item Name *</label>
                        <input type="text" name="itemName" class="form-control" 
                               value="<?= $editItem ? htmlspecialchars($editItem['itemName']) : '' ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Price ($) *</label>
                        <input type="number" name="itemPrice" class="form-control" step="0.01" min="0"
                               value="<?= $editItem ? $editItem['itemPrice'] : '' ?>" required>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label">Category *</label>
                        <select name="itemCategory" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['catid'] ?>" 
                                    <?= ($editItem && $editItem['itemCategory'] == $cat['catid']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['catName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label">Description *</label>
                        <textarea name="itemDescription" class="form-control" rows="4" required><?= $editItem ? htmlspecialchars($editItem['itemDescription']) : '' ?></textarea>
                    </div>
                    
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-<?= $editItem ? 'primary' : 'success' ?>">
                            <i class="fa fa-<?= $editItem ? 'save' : 'plus' ?>"></i> 
                            <?= $editItem ? 'Update Item' : 'Add Item' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Items List -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-table"></i> All Items
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $q = "SELECT i.*, c.catName 
                                  FROM items i 
                                  LEFT JOIN categories c ON i.itemCategory = c.catid 
                                  ORDER BY i.itemid DESC";
                            $results = mysqli_query($conn, $q);
                            
                            while($row = mysqli_fetch_array($results)):
                                extract($row);
                            ?>
                            <tr>
                                <td><?= $itemid ?></td>
                                <td><strong><?= htmlspecialchars($itemName) ?></strong></td>
                                <td>$<?= number_format($itemPrice, 2) ?></td>
                                <td><?= isset($catName) ? htmlspecialchars($catName) : '-' ?></td>
                                <td><?= substr(htmlspecialchars($itemDescription), 0, 80) ?>...</td>
                                <td>
                                    <a href="items.php?edit=<?= $itemid ?>" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="itemid" value="<?= $itemid ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    <a href="../item.php?itemid=<?= $itemid ?>" class="btn btn-secondary btn-sm" target="_blank">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('footer.php'); ?>
</body>
</html>
