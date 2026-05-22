<?php
/**
 * eBusiness Configuration File
 * This file contains site-wide configuration settings
 */

// Start session for login functionality
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Site Configuration
define('SITE_NAME', 'ebusiness');
define('SITE_URL', 'http://localhost/eBusiness/');

// Database Configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ebusiness';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Helper Functions
function getActivePage() {
    $current_page = basename($_SERVER['PHP_SELF']);
    return $current_page;
}

function isActive($page) {
    $current_page = getActivePage();
    return ($current_page == $page) ? 'active' : '';
}



function getPortfolioCategories() {
    global $conn;
    $categories = [];
    
    $query = "SELECT * FROM portfolio_categories ORDER BY category_order";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
    }
    
    return $categories;
}

function getPortfolioItems($category_id = 0) {
    global $conn;
    $items = [];
    
    $query = "SELECT * FROM portfolio_items WHERE is_active = 1 ORDER BY item_order";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $item_id = $row['item_id'];
            $cat_query = "SELECT pc.category_slug 
                         FROM portfolio_item_categories pic 
                         JOIN portfolio_categories pc ON pic.category_id = pc.category_id 
                         WHERE pic.item_id = $item_id";
            $cat_result = mysqli_query($conn, $cat_query);
            
            $categories = [];
            while ($cat_row = mysqli_fetch_assoc($cat_result)) {
                $categories[] = $cat_row['category_slug'];
            }
            $row['categories'] = implode(' ', $categories);
            $items[] = $row;
        }
    }
    
    return $items;
}

function displayPortfolioFilters() {
    $categories = getPortfolioCategories();
    
    echo '<div class="portfolio-menu">';
    echo '<button class="active btn" type="button" data-filter="*">All</button>';
    
    foreach ($categories as $category) {
        $slug = htmlspecialchars($category['category_slug']);
        $name = htmlspecialchars($category['category_name']);
        echo '<button class="btn" type="button" data-filter=".' . $slug . '">' . $name . '</button>';
    }
    
    echo '</div>';
}

function displayPortfolioItems() {
    $items = getPortfolioItems();
    
    foreach ($items as $item) {
        $image = htmlspecialchars($item['image_path']);
        $title = htmlspecialchars($item['item_title']);
        $categories = htmlspecialchars($item['categories']);
        $item_id = intval($item['item_id']);
        
        echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3 column_single_gallery_item ' . $categories . '">';
        echo '    <img src="' . $image . '" alt="' . $title . '">';
        echo '    <div class="hover_overlay">';
        echo '        <a href="product_detail.php?id=' . $item_id . '"><i class="fa fa-eye"></i> View Details</a>';
        echo '    </div>';
        echo '</div>';
    }
}

function getFeaturedPortfolioItems($limit = 6) {
    global $conn;
    $items = [];
    
    $query = "SELECT * FROM portfolio_items WHERE is_active = 1 ORDER BY item_order LIMIT " . intval($limit);
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $item_id = $row['item_id'];
            $cat_query = "SELECT pc.category_name 
                         FROM portfolio_item_categories pic 
                         JOIN portfolio_categories pc ON pic.category_id = pc.category_id 
                         WHERE pic.item_id = $item_id";
            $cat_result = mysqli_query($conn, $cat_query);
            
            $categories = [];
            while ($cat_row = mysqli_fetch_assoc($cat_result)) {
                $categories[] = $cat_row['category_name'];
            }
            $row['category_names'] = implode(', ', $categories);
            $items[] = $row;
        }
    }
    
    return $items;
}

function displayFeaturedPortfolio() {
    $items = getFeaturedPortfolioItems(6);
    
    foreach ($items as $item) {
        $image = htmlspecialchars($item['image_path']);
        $title = htmlspecialchars($item['item_title']);
        $categories = htmlspecialchars($item['category_names']);
        $item_id = intval($item['item_id']);
        
        echo '<div class="col-lg-4 col-md-6 mb-4">';
        echo '    <div class="single-project-item">';
        echo '        <img src="' . $image . '" alt="' . $title . '" class="img-fluid">';
        echo '        <div class="project-overlay">';
        echo '            <div class="project-content">';
        echo '                <h5 class="text-white text-uppercase">' . $title . '</h5>';
        echo '                <p class="text-white">' . $categories . '</p>';
        echo '                <a href="product_detail.php?id=' . $item_id . '" class="btn btn-primary btn-sm">View Details</a>';
        echo '            </div>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
}

function getCarouselItems() {
    global $conn;
    $items = [];
    
    $query = "SELECT * FROM portfolio_items 
              WHERE is_active = 1 
              ORDER BY item_order 
              LIMIT 10";
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
    }
    
    return $items;
}

function displayCarouselSlides() {
    $items = getCarouselItems();
    $count = 0;
    
    foreach ($items as $item) {
        $image = htmlspecialchars($item['image_path']);
        $title = htmlspecialchars($item['item_title']);
        $count++;
        $active = ($count === 1) ? 'active' : '';
        
        echo '<div class="carousel-item h-100 bg-img ' . $active . '" style="background-image: url(' . $image . ');">';
        echo '    <div class="carousel-content h-100">';
        echo '        <div class="slide-text">';
        echo '            <span>' . str_pad($count, 2, '0', STR_PAD_LEFT) . '.</span>';
        echo '            <h2>' . $title . '</h2>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
}

function displayCarouselIndicators() {
    $items = getCarouselItems();
    $count = 0;
    
    foreach ($items as $item) {
        $image = htmlspecialchars($item['image_path']);
        $active = ($count === 0) ? 'active' : '';
        
        echo '<li data-target="#welcomeSlider" data-slide-to="' . $count . '" class="' . $active . ' bg-img" style="background-image: url(' . $image . ');"></li>';
        $count++;
    }
}

function getProductById($id) {
    global $conn;
    $id = intval($id);
    
    $query = "SELECT * FROM portfolio_items WHERE item_id = $id AND is_active = 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        $cat_query = "SELECT pc.category_name, pc.category_slug 
                     FROM portfolio_item_categories pic 
                     JOIN portfolio_categories pc ON pic.category_id = pc.category_id 
                     WHERE pic.item_id = $id";
        $cat_result = mysqli_query($conn, $cat_query);
        
        $cat_names = [];
        $cat_slugs = [];
        while ($cat_row = mysqli_fetch_assoc($cat_result)) {
            $cat_names[] = $cat_row['category_name'];
            $cat_slugs[] = $cat_row['category_slug'];
        }
        
        $product['category_names'] = implode(', ', $cat_names);
        $product['category_slugs'] = implode(' ', $cat_slugs);
        
        return $product;
    }
    
    return null;
}

/**
 * Get related products (same category, excluding current)
 */
function getRelatedProducts($product_id, $limit = 3) {
    global $conn;
    $product_id = intval($product_id);
    $items = [];
    
    // Step 1: Get categories of current product
    $cat_query = "SELECT category_id FROM portfolio_item_categories WHERE item_id = $product_id";
    $cat_result = mysqli_query($conn, $cat_query);
    $category_ids = [];
    while ($cat_row = mysqli_fetch_assoc($cat_result)) {
        $category_ids[] = $cat_row['category_id'];
    }
    
    if (empty($category_ids)) {
        return $items; // No categories, return empty
    }
    
    $cat_list = implode(',', $category_ids);
    
    // Step 2: Get items in same categories
    $query = "SELECT DISTINCT p.* 
             FROM portfolio_items p
             JOIN portfolio_item_categories pic ON p.item_id = pic.item_id
             WHERE p.item_id != $product_id 
             AND p.is_active = 1
             AND pic.category_id IN ($cat_list)
             ORDER BY RAND()
             LIMIT " . intval($limit);
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Get category names for this item
            $item_id = $row['item_id'];
            $name_query = "SELECT pc.category_name 
                          FROM portfolio_item_categories pic 
                          JOIN portfolio_categories pc ON pic.category_id = pc.category_id 
                          WHERE pic.item_id = $item_id";
            $name_result = mysqli_query($conn, $name_query);
            
            $categories = [];
            while ($name_row = mysqli_fetch_assoc($name_result)) {
                $categories[] = $name_row['category_name'];
            }
            $row['category_names'] = implode(', ', $categories);
            $items[] = $row;
        }
    }
    
    return $items;
}


function processContactForm() {
    global $conn;
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
        $message = mysqli_real_escape_string($conn, trim($_POST['message']));
        
        $errors = [];
        
        if (empty($name)) {
            $errors[] = "Name is required";
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required";
        }
        
        if (empty($subject)) {
            $errors[] = "Subject is required";
        }
        
        if (empty($message)) {
            $errors[] = "Message is required";
        }
        
        if (empty($errors)) {
            $query = "INSERT INTO contact_messages (name, email, subject, message) 
                     VALUES ('$name', '$email', '$subject', '$message')";
            
            if (mysqli_query($conn, $query)) {
                return ['success' => true, 'message' => 'Thank you! Your message has been sent successfully.'];
            } else {
                return ['success' => false, 'message' => 'Error: Could not save message. Please try again.'];
            }
        } else {
            return ['success' => false, 'message' => implode('<br>', $errors)];
        }
    }
    
    return null;
}

// ============================================
// LOGIN FUNCTIONALITY (Following CakeZone2025 Pattern)
// ============================================

/**
 * Login user function - Simple like CakeZone2025
 */
function loginUser($username, $password) {
    global $conn;
    
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    
    // Build query - check if is_active column exists
    $q = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $checkActive = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'is_active'");
    if (mysqli_num_rows($checkActive) > 0) {
        $q .= " AND (is_active = 1 OR is_active IS NULL)";
    }
    
    $result = mysqli_query($conn, $q);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // Set role if column exists, otherwise determine from username
        if (isset($row['role'])) {
            $_SESSION['role'] = $row['role'];
        } else {
            // Default to admin if username is admin, otherwise user
            $_SESSION['role'] = ($row['username'] == 'admin') ? 'admin' : 'user';
        }
        
        // Set email if column exists
        if (isset($row['email'])) {
            $_SESSION['email'] = $row['email'];
        }
        
        // Update last_login if column exists
        $checkLastLogin = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'last_login'");
        if (mysqli_num_rows($checkLastLogin) > 0) {
            $updateQuery = "UPDATE users SET last_login = NOW() WHERE id = " . intval($row['id']);
            mysqli_query($conn, $updateQuery);
        }
        
        // Store session in database if user_sessions table exists
        $checkSessionsTable = mysqli_query($conn, "SHOW TABLES LIKE 'user_sessions'");
        if (mysqli_num_rows($checkSessionsTable) > 0) {
            $sessionId = session_id();
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $expiresAt = date('Y-m-d H:i:s', time() + (24 * 60 * 60)); // 24 hours
            
            $sessionQuery = "INSERT INTO user_sessions (session_id, user_id, ip_address, user_agent, expires_at) 
                            VALUES ('$sessionId', {$row['id']}, '$ipAddress', '$userAgent', '$expiresAt')
                            ON DUPLICATE KEY UPDATE 
                            last_activity = NOW(), 
                            expires_at = '$expiresAt'";
            mysqli_query($conn, $sessionQuery);
        }
        
        return true;
    }
    
    return false;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Logout user
 */
function logoutUser() {
    global $conn;
    
    // Clean up session from database if table exists
    if (isset($_SESSION['user_id']) && isset($conn)) {
        $sessionId = session_id();
        $checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'user_sessions'");
        if (mysqli_num_rows($checkTable) > 0) {
            $deleteQuery = "DELETE FROM user_sessions WHERE session_id = '$sessionId'";
            mysqli_query($conn, $deleteQuery);
        }
    }
    
    session_unset();
    session_destroy();
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Check if user is admin
 */
function isAdmin() {
    // Check session role first
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        return true;
    }
    
    // Fallback: check if username is admin (for existing database without role column)
    if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') {
        return true;
    }
    
    return false;
}

/**
 * Require admin access - redirect if not admin
 */
function requireAdmin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }
    
    if (!isAdmin()) {
        header('Location: ../index.php');
        exit;
    }
}

// ============================================
// SIGNUP FUNCTIONALITY
// ============================================

/**
 * Signup user function
 */
function signupUser($username, $password, $email, $full_name = '', $phone = '') {
    global $conn;
    
    $errors = [];
    
    // Validate inputs
    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    // Check if username already exists
    $checkUser = "SELECT id FROM users WHERE username = '" . mysqli_real_escape_string($conn, $username) . "'";
    $result = mysqli_query($conn, $checkUser);
    if ($result && mysqli_num_rows($result) > 0) {
        $errors[] = "Username already exists";
    }
    
    // Check if email column exists and if email already exists
    $checkEmailCol = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'email'");
    if (mysqli_num_rows($checkEmailCol) > 0) {
        $checkEmail = "SELECT id FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
        $result = mysqli_query($conn, $checkEmail);
        if ($result && mysqli_num_rows($result) > 0) {
            $errors[] = "Email already exists";
        }
    }
    
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }
    
    // Insert new user - build query based on available columns
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password); // In production, use password_hash()
    
    // Check which columns exist
    $columns = [];
    $values = [];
    
    $columns[] = 'username';
    $values[] = "'$username'";
    
    $columns[] = 'password';
    $values[] = "'$password'";
    
    // Add optional columns if they exist
    $checkEmailCol = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'email'");
    if (mysqli_num_rows($checkEmailCol) > 0 && !empty($email)) {
        $email = mysqli_real_escape_string($conn, $email);
        $columns[] = 'email';
        $values[] = "'$email'";
    }
    
    $checkFullNameCol = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'full_name'");
    if (mysqli_num_rows($checkFullNameCol) > 0 && !empty($full_name)) {
        $full_name = mysqli_real_escape_string($conn, $full_name);
        $columns[] = 'full_name';
        $values[] = "'$full_name'";
    }
    
    $checkPhoneCol = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'phone'");
    if (mysqli_num_rows($checkPhoneCol) > 0 && !empty($phone)) {
        $phone = mysqli_real_escape_string($conn, $phone);
        $columns[] = 'phone';
        $values[] = "'$phone'";
    }
    
    $checkRoleCol = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
    if (mysqli_num_rows($checkRoleCol) > 0) {
        $columns[] = 'role';
        $values[] = "'user'";
    }
    
    $query = "INSERT INTO users (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
    
    if (mysqli_query($conn, $query)) {
        // Auto-login after signup
        $userId = mysqli_insert_id($conn);
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = 'user';
        
        return ['success' => true, 'message' => 'Account created successfully!'];
    } else {
        return ['success' => false, 'errors' => ['Database error: Could not create account - ' . mysqli_error($conn)]];
    }
}


// ============================================
// DATABASE UPDATE FUNCTIONS
// ============================================

/**
 * Update database value - generic function
 */
function updateDBValue($table, $field, $value, $whereField, $whereValue) {
    global $conn;
    
    $table = mysqli_real_escape_string($conn, $table);
    $field = mysqli_real_escape_string($conn, $field);
    $value = mysqli_real_escape_string($conn, $value);
    $whereField = mysqli_real_escape_string($conn, $whereField);
    $whereValue = mysqli_real_escape_string($conn, $whereValue);
    
    $query = "UPDATE $table SET $field = '$value' WHERE $whereField = '$whereValue'";
    
    if (mysqli_query($conn, $query)) {
        return ['success' => true, 'message' => 'Database updated successfully'];
    } else {
        return ['success' => false, 'message' => 'Error updating database: ' . mysqli_error($conn)];
    }
}

/**
 * Update order status
 */
function updateOrderStatus($orderId, $status, $notes = '') {
    global $conn;
    
    $orderId = intval($orderId);
    $status = mysqli_real_escape_string($conn, $status);
    $notes = mysqli_real_escape_string($conn, $notes);
    
    // Check if status_notes column exists
    $checkStatusNotes = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'status_notes'");
    $hasStatusNotes = mysqli_num_rows($checkStatusNotes) > 0;
    
    // Check if payment_date column exists
    $checkPaymentDate = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'payment_date'");
    $hasPaymentDate = mysqli_num_rows($checkPaymentDate) > 0;
    
    // Check if updated_at column exists
    $checkUpdatedAt = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'updated_at'");
    $hasUpdatedAt = mysqli_num_rows($checkUpdatedAt) > 0;
    
    $updates = [];
    $updates[] = "payment_status = '$status'";
    
    if ($hasStatusNotes && !empty($notes)) {
        $updates[] = "status_notes = '$notes'";
    }
    
    if ($hasUpdatedAt) {
        $updates[] = "updated_at = NOW()";
    }
    
    if ($status === 'completed' && $hasPaymentDate) {
        $updates[] = "payment_date = NOW()";
    }
    
    $query = "UPDATE orders SET " . implode(', ', $updates) . " WHERE order_id = $orderId";
    
    if (mysqli_query($conn, $query)) {
        return ['success' => true, 'message' => 'Order status updated'];
    } else {
        return ['success' => false, 'message' => 'Error updating order: ' . mysqli_error($conn)];
    }
}

/**
 * Update user information
 */
function updateUserInfo($userId, $data) {
    global $conn;
    
    $userId = intval($userId);
    $updates = [];
    
    $allowedFields = ['email', 'full_name', 'phone', 'address', 'password'];
    
    foreach ($data as $field => $value) {
        if (in_array($field, $allowedFields)) {
            // Check if column exists
            $checkCol = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE '$field'");
            if (mysqli_num_rows($checkCol) > 0) {
                $value = mysqli_real_escape_string($conn, $value);
                $updates[] = "$field = '$value'";
            }
        }
    }
    
    if (empty($updates)) {
        return ['success' => false, 'message' => 'No valid fields to update'];
    }
    
    // Check if updated_at column exists
    $checkUpdatedAt = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'updated_at'");
    if (mysqli_num_rows($checkUpdatedAt) > 0) {
        $updates[] = "updated_at = NOW()";
    }
    
    $query = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = $userId";
    
    if (mysqli_query($conn, $query)) {
        return ['success' => true, 'message' => 'User information updated'];
    } else {
        return ['success' => false, 'message' => 'Error updating user: ' . mysqli_error($conn)];
    }
}

// ============================================
// TRANSACTION SAVING FUNCTIONS
// ============================================

/**
 * Save transaction information
 */
function saveTransaction($orderId, $transactionNumber, $amount, $paymentMethod, $paymentStatus = 'pending', $gatewayTransactionId = '', $notes = '') {
    global $conn;
    
    // Check if transactions table exists
    $checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'transactions'");
    if (mysqli_num_rows($checkTable) == 0) {
        // Table doesn't exist, just update order status
        updateOrderStatus($orderId, $paymentStatus, $notes);
        return ['success' => true, 'message' => 'Order status updated (transactions table not available)'];
    }
    
    $orderId = intval($orderId);
    $transactionNumber = mysqli_real_escape_string($conn, $transactionNumber);
    $amount = floatval($amount);
    $paymentMethod = mysqli_real_escape_string($conn, $paymentMethod);
    $paymentStatus = mysqli_real_escape_string($conn, $paymentStatus);
    $gatewayTransactionId = mysqli_real_escape_string($conn, $gatewayTransactionId);
    $notes = mysqli_real_escape_string($conn, $notes);
    
    $userId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    
    $query = "INSERT INTO transactions (order_id, transaction_number, user_id, amount, payment_method, payment_status, payment_gateway, gateway_transaction_id, notes) 
             VALUES ($orderId, '$transactionNumber', $userId, $amount, '$paymentMethod', '$paymentStatus', 'PayPal', '$gatewayTransactionId', '$notes')";
    
    if (mysqli_query($conn, $query)) {
        $transactionId = mysqli_insert_id($conn);
        
        // Update order with transaction info
        updateOrderStatus($orderId, $paymentStatus, $notes);
        
        return ['success' => true, 'transaction_id' => $transactionId, 'message' => 'Transaction saved successfully'];
    } else {
        return ['success' => false, 'message' => 'Error saving transaction: ' . mysqli_error($conn)];
    }
}

/**
 * Save order and transaction information (complete function)
 */
function saveOrderTransaction($cartItems, $userId = 0) {
    global $conn;
    
    if (empty($cartItems)) {
        return ['success' => false, 'message' => 'Cart is empty'];
    }
    
    // Generate order number
    $orderNumber = 'ORD-' . date('YmdHis') . '-' . rand(1000, 9999);
    $userId = $userId > 0 ? intval($userId) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0);
    
    // Calculate total
    $grandTotal = 0;
    foreach ($cartItems as $item) {
        $grandTotal += floatval($item['price']) * intval($item['quantity']);
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert order
        $insertOrder = "INSERT INTO orders (order_number, user_id, total_amount, payment_status, payment_method) 
                       VALUES ('$orderNumber', $userId, $grandTotal, 'pending', 'PayPal')";
        
        if (!mysqli_query($conn, $insertOrder)) {
            throw new Exception('Error creating order: ' . mysqli_error($conn));
        }
        
        $orderId = mysqli_insert_id($conn);
        
        // Insert order items
        foreach ($cartItems as $item) {
            $itemId = isset($item['item_id']) ? intval($item['item_id']) : 0;
            $itemName = mysqli_real_escape_string($conn, $item['name']);
            $itemDesc = mysqli_real_escape_string($conn, $item['description'] ?? '');
            $itemPrice = floatval($item['price']);
            $quantity = intval($item['quantity']);
            $itemTotal = $itemPrice * $quantity;
            
            $insertItem = "INSERT INTO order_items (order_id, item_id, item_name, item_description, item_price, quantity, item_total) 
                          VALUES ($orderId, $itemId, '$itemName', '$itemDesc', $itemPrice, $quantity, $itemTotal)";
            
            if (!mysqli_query($conn, $insertItem)) {
                throw new Exception('Error creating order item: ' . mysqli_error($conn));
            }
        }
        
        // Create transaction record
        $transactionNumber = 'TXN-' . date('YmdHis') . '-' . rand(1000, 9999);
        $saveResult = saveTransaction($orderId, $transactionNumber, $grandTotal, 'PayPal', 'pending');
        
        if (!$saveResult['success']) {
            throw new Exception($saveResult['message']);
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        return [
            'success' => true,
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'transaction_number' => $transactionNumber,
            'total' => $grandTotal,
            'message' => 'Order and transaction saved successfully'
        ];
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// ============================================
// THANK YOU PAGE FUNCTIONS
// ============================================

/**
 * Get order details for thank you page
 */
function getOrderDetails($orderNumber) {
    global $conn;
    
    $orderNumber = mysqli_real_escape_string($conn, $orderNumber);
    
    $query = "SELECT o.*, u.username, u.email, u.full_name 
             FROM orders o 
             LEFT JOIN users u ON o.user_id = u.id 
             WHERE o.order_number = '$orderNumber'";
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $order = mysqli_fetch_assoc($result);
        
        // Get order items
        $itemsQuery = "SELECT * FROM order_items WHERE order_id = " . intval($order['order_id']);
        $itemsResult = mysqli_query($conn, $itemsQuery);
        
        $items = [];
        while ($item = mysqli_fetch_assoc($itemsResult)) {
            $items[] = $item;
        }
        
        $order['items'] = $items;
        
        return $order;
    }
    
    return null;
}

/**
 * Send order confirmation email (placeholder - implement with actual email service)
 */
function sendOrderConfirmationEmail($orderNumber, $userEmail) {
    // This is a placeholder function
    // In production, implement with PHPMailer or similar
    // For now, just log or return success
    
    $order = getOrderDetails($orderNumber);
    if ($order) {
        // Email would be sent here
        // mail($userEmail, "Order Confirmation - $orderNumber", "Your order has been confirmed...");
        return true;
    }
    
    return false;
}

/**
 * Complete order processing (called from thank you page)
 */
function completeOrderProcessing($orderNumber) {
    global $conn;
    
    $order = getOrderDetails($orderNumber);
    
    if (!$order) {
        return ['success' => false, 'message' => 'Order not found'];
    }
    
    // Update order status to completed
    $updateResult = updateOrderStatus($order['order_id'], 'completed', 'Payment confirmed via PayPal');
    
    if ($updateResult['success']) {
        // Update transaction status
        $transactionQuery = "UPDATE transactions SET payment_status = 'completed' WHERE order_id = " . intval($order['order_id']);
        mysqli_query($conn, $transactionQuery);
        
        // Send confirmation email
        if (isset($order['email']) && !empty($order['email'])) {
            sendOrderConfirmationEmail($orderNumber, $order['email']);
        }
        
        return ['success' => true, 'message' => 'Order processing completed'];
    }
    
    return $updateResult;
}

?>

