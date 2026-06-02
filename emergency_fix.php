<?php
// Pure PHP script to fix missing slug column
$env = parse_ini_file('.env');
$host = $env['DB_HOST'] ?? '127.0.0.1';
$db   = $env['DB_DATABASE'];
$user = $env['DB_USERNAME'];
$pass = $env['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully to database: $db\n";
    
    // Check categories table
    $stmt = $pdo->query("SHOW COLUMNS FROM categories LIKE 'slug'");
    if (!$stmt->fetch()) {
        echo "Adding 'slug' column to categories...\n";
        $pdo->exec("ALTER TABLE categories ADD slug VARCHAR(255) NULL AFTER name");
        $pdo->exec("CREATE INDEX categories_slug_index ON categories (slug)");
    } else {
        echo "Column 'slug' already exists in 'categories'.\n";
    }
    
    // Check indices
    $indices = [
        'is_active' => 'categories',
        'is_home'   => 'categories',
        'category_id' => 'business_listings',
        'status'      => 'business_listings',
        'is_allowed'  => 'business_listings',
        'is_featured' => 'business_listings'
    ];
    
    foreach ($indices as $col => $table) {
        try {
            echo "Attempting to add index for $col on $table...\n";
            $pdo->exec("CREATE INDEX {$table}_{$col}_index ON $table ($col)");
        } catch (Exception $e) {
            echo "Index for $col on $table likely exists or failed: " . $e->getMessage() . "\n";
        }
    }
    
    // Populate slugs if empty
    echo "Populating empty slugs...\n";
    $stmt = $pdo->query("SELECT id, name FROM categories WHERE slug IS NULL OR slug = ''");
    $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cats as $cat) {
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($cat['name']));
        $slug = trim($slug, '-');
        $pdo->prepare("UPDATE categories SET slug = ? WHERE id = ?")->execute([$slug, $cat['id']]);
        echo "Updated slug for: " . $cat['name'] . " -> $slug\n";
    }
    
    echo "DATABASE FIX COMPLETED SUCCESSFULLY.\n";
} catch (Exception $e) {
    echo "DATABASE FIX FAILED: " . $e->getMessage() . "\n";
}
