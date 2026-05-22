<!DOCTYPE html>
<html lang="<?php echo $current_language ?? 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="LUKU Farm - Professional poultry farm and feed supply in Addis Ababa, Ethiopia">
    <meta name="keywords" content="poultry, chicken, feed, vaccines, medicines, Ethiopia, Addis Ababa">
    <meta name="author" content="LUKU Farm">
    
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>LUKU Farm</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo url('/public/assets/css/style.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo url('/public/assets/images/favicon.png'); ?>">
    
    <!-- Theme initialization - prevents flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            // Set initial meta theme-color
            let metaThemeColor = document.querySelector('meta[name="theme-color"]');
            if (!metaThemeColor) {
                metaThemeColor = document.createElement('meta');
                metaThemeColor.name = 'theme-color';
                document.head.appendChild(metaThemeColor);
            }
            metaThemeColor.content = savedTheme === 'dark' ? '#121212' : '#28a745';
        })();
    </script>
</head>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    
    <main class="main-content" style="padding-top: 100px;">