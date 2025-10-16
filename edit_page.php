<?php
session_start();
require_once 'security.php'; // Session security check
require_once 'db.php';       // Database connection

// Get the page slug from the URL
$page_slug = $_GET['slug'] ?? '';
if (empty($page_slug)) {
    header('Location: admin.php?view=pages');
    exit;
}

// Fetch the page data from the database
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
$stmt->execute([$page_slug]);
$page = $stmt->fetch();

// If page not found, redirect back
if (!$page) {
    header('Location: admin.php?view=pages');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page: <?= htmlspecialchars($page['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #6D28D9; --primary-color-darker: #5B21B6; }
        body { font-family: 'Inter', sans-serif; }
        .form-input, .form-textarea { width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.6rem 0.8rem; transition: all 0.2s ease-in-out; background-color: #F9FAFB; }
        .form-input:focus, .form-textarea:focus { border-color: var(--primary-color); box-shadow: 0 0 0 2px #E9D5FF; outline: none; background-color: white; }
        .btn { padding: 0.6rem 1.2rem; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .btn-primary { background-color: var(--primary-color); color: white; } .btn-primary:hover { background-color: var(--primary-color-darker); }
        .btn-secondary { background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db; } .btn-secondary:hover { background-color: #e5e7eb; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 md:p-6 max-w-4xl">
        <header class="flex justify-between items-center mb-6">
            <div>
                <a href="admin.php?view=pages" class="inline-flex items-center gap-2 text-gray-600 font-semibold hover:text-[var(--primary-color)] transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Back to Pages
                </a>
                <h1 class="text-3xl font-bold text-gray-800 mt-2">Edit Page: <span class="text-[var(--primary-color)]"><?= htmlspecialchars($page['title']) ?></span></h1>
            </div>
            <a href="logout.php" class="btn btn-secondary"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </header>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="api.php" method="POST">
                <input type="hidden" name="action" value="update_page">
                <input type="hidden" name="page_slug" value="<?= htmlspecialchars($page['slug']) ?>">

                <div class="mb-6">
                    <label for="page_title" class="block mb-2 text-sm font-medium text-gray-700">Page Title</label>
                    <input type="text" id="page_title" name="page_title" class="form-input" value="<?= htmlspecialchars($page['title']) ?>" required>
                </div>

                <div class="mb-6">
                    <label for="page_content" class="block mb-2 text-sm font-medium text-gray-700">Page Content</label>
                    <textarea id="page_content" name="page_content" class="form-textarea" rows="20" placeholder="Enter your page content here. You can use HTML for formatting."><?= htmlspecialchars($page['content']) ?></textarea>
                    <p class="text-xs text-gray-500 mt-2">You can use basic HTML tags like &lt;b&gt;, &lt;i&gt;, &lt;p&gt;, &lt;br&gt;, &lt;h1&gt;-&lt;h6&gt;, &lt;ul&gt;, &lt;li&gt; for formatting.</p>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>