<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - <?= SITE_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="../img/core-img/favicon.ico">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Admin Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
        }
        
        /* Navigation */
        .admin-nav {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .admin-nav h2 {
            display: inline-block;
            margin: 0;
            font-size: 24px;
        }
        
        .admin-nav ul {
            list-style: none;
            display: inline-block;
            margin: 0 0 0 50px;
            padding: 0;
        }
        
        .admin-nav ul li {
            display: inline-block;
            margin: 0 15px;
        }
        
        .admin-nav ul li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .admin-nav ul li a:hover, 
        .admin-nav ul li a.active {
            background: #34495e;
        }
        
        .admin-nav .nav-right {
            float: right;
        }
        
        /* Container */
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .admin-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .page-header h1 {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .page-header p {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            border-left: 4px solid #3498db;
        }
        
        .stat-card.stat-primary { border-left-color: #3498db; }
        .stat-card.stat-success { border-left-color: #27ae60; }
        .stat-card.stat-info { border-left-color: #f39c12; }
        .stat-card.stat-warning { border-left-color: #e74c3c; }
        
        .stat-icon {
            font-size: 40px;
            margin-right: 20px;
            opacity: 0.8;
        }
        
        .stat-card.stat-primary .stat-icon { color: #3498db; }
        .stat-card.stat-success .stat-icon { color: #27ae60; }
        .stat-card.stat-info .stat-icon { color: #f39c12; }
        .stat-card.stat-warning .stat-icon { color: #e74c3c; }
        
        .stat-details h3 {
            font-size: 32px;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .stat-details p {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Today's Stats */
        .today-stats {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 40px;
        }
        
        .today-stats h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .today-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .today-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        
        .today-item strong {
            display: block;
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .today-item span {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Tables */
        .table-responsive {
            overflow-x: auto;
            margin-top: 15px;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        .admin-table thead {
            background: #34495e;
            color: white;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        .admin-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Recent Orders */
        .recent-orders {
            margin-bottom: 40px;
        }
        
        .recent-orders h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        /* Quick Actions */
        .quick-actions h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .action-btn.btn-primary {
            background: #3498db;
        }
        
        .action-btn.btn-success {
            background: #27ae60;
        }
        
        .action-btn.btn-info {
            background: #f39c12;
        }
        
        .action-btn.btn-warning {
            background: #e67e22;
        }
        
        .action-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        
        /* Charts */
        .charts-section {
            margin-bottom: 40px;
        }
        
        .chart-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }
        
        .chart-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .chart-card h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .chart-card canvas {
            max-height: 300px;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: #34495e;
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background: #ecf0f1;
            padding: 10px 15px;
            border-radius: 6px;
            list-style: none;
            margin-bottom: 20px;
        }
        
        .breadcrumb-item {
            display: inline;
            color: #7f8c8d;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "/";
            padding: 0 8px;
        }
        
        .breadcrumb-item.active {
            color: #2c3e50;
        }
        
        .breadcrumb-item a {
            color: #3498db;
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            text-decoration: underline;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        /* Footer */
        .admin-footer {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            margin-top: 40px;
        }
    </style>
</head>

