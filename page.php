<?php
require_once 'db.php'; // Database connection

// Get the page slug from the URL
$page_slug = $_GET['slug'] ?? '';
if (empty($page_slug)) {
    // Optionally, redirect to the homepage if no slug is provided
    header('Location: index.php');
    exit;
}

// Fetch the page data from the database
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
$stmt->execute([$page_slug]);
$page = $stmt->fetch();

// If page not found, show a 404 error
if (!$page) {
    http_response_code(404);
    // You can create a more styled 404 page if you want
    die("<h1>404 - Page Not Found</h1><p>The page you are looking for does not exist.</p><a href='index.php'>Go to Homepage</a>");
}

// Load site config for header/footer elements if needed
$config_file_path = 'config.json';
if (!file_exists($config_file_path)) file_put_contents($config_file_path, '{}');
$site_config = json_decode(file_get_contents($config_file_path), true);
$site_logo = $site_config['site_logo'] ?? 'YOUR_DEFAULT_LOGO.png'; // Provide a default logo
$favicon = $site_config['favicon'] ?? 'YOUR_DEFAULT_FAVICON.ico'; // Provide a default favicon
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['title']) ?></title>
    <link rel="icon" href="<?= htmlspecialchars($favicon) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #6D28D9; }
        body { font-family: 'Inter', sans-serif; }
        .prose h1, .prose h2, .prose h3 { color: #374151; }
        .prose a { color: var(--primary-color); }
        .prose ul { list-style-type: disc; padding-left: 1.5em; }
        .prose ol { list-style-type: decimal; padding-left: 1.5em; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Simple Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php">
                <img src="<?= htmlspecialchars($site_logo) ?>" alt="Site Logo" class="h-10">
            </a>
            <a href="index.php" class="text-lg font-semibold text-gray-700 hover:text-[var(--primary-color)]">
                <i class="fa-solid fa-home"></i> Home
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto p-4 md:p-8">
        <div class="bg-white rounded-lg shadow-md p-6 md:p-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 border-b pb-4">
                <?= htmlspecialchars($page['title']) ?>
            </h1>
            <div class="prose max-w-none">
                <?php
                // The content is expected to be HTML, so we output it directly.
                // Note: This assumes the content saved by the admin is safe.
                // For production, a library like HTML Purifier is recommended to prevent XSS.
                echo $page['content'];
                ?>
            </div>
        </div>
    </main>

    <!-- Simple Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto p-6 text-center">
            <p>&copy; <?= date('Y') ?> Your Company Name. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>