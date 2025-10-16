<?php
$db_host = 'localhost';
$db_name = 'u802637580_submont';
$db_user = 'u802637580_submonthmysql';
$db_pass = 'submontH2:)';
$charset = 'utf8mb4';
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";
$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false, ];
try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);

    // --- Auto-creation of 'pages' table ---
    // Check if the 'pages' table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'pages'");
    if ($stmt->rowCount() == 0) {
        // Table does not exist, so create it
        $pdo->exec("
            CREATE TABLE pages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                slug VARCHAR(255) NOT NULL UNIQUE,
                title VARCHAR(255) NOT NULL,
                content TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");

        // Populate the table with initial pages
        $initial_pages = [
            ['slug' => 'about-us', 'title' => 'About Us', 'content' => ''],
            ['slug' => 'terms-and-conditions', 'title' => 'Terms and Conditions', 'content' => ''],
            ['slug' => 'privacy-policy', 'title' => 'Privacy Policy', 'content' => ''],
            ['slug' => 'refund-policy', 'title' => 'Refund Policy', 'content' => '']
        ];

        $insert_stmt = $pdo->prepare("INSERT INTO pages (slug, title, content) VALUES (?, ?, ?)");
        foreach ($initial_pages as $page) {
            $insert_stmt->execute([$page['slug'], $page['title'], $page['content']]);
        }
    }

} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>