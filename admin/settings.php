<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';
require_once '../includes/helpers.php';

$database = new Database();
$conn = $database->getConnection();

// Create settings table if it doesn't exist
$create_table = "CREATE TABLE IF NOT EXISTS site_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_group VARCHAR(50),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key),
    INDEX idx_group (setting_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$conn->exec($create_table);

// Check if setting_group column exists and add it if not
$check_column = "SHOW COLUMNS FROM site_settings LIKE 'setting_group'";
$column_exists = $conn->query($check_column)->rowCount() > 0;
if (!$column_exists) {
    $add_column = "ALTER TABLE site_settings ADD COLUMN setting_group VARCHAR(50) AFTER setting_value, ADD INDEX idx_group (setting_group)";
    $conn->exec($add_column);
}

// Initialize default settings if table is empty
$check_query = "SELECT COUNT(*) as count FROM site_settings";
$count = $conn->query($check_query)->fetch(PDO::FETCH_ASSOC)['count'];

if ($count == 0) {
    $default_settings = [
        // General Settings
        ['site_name', 'GeoTrans', 'general'],
        ['site_tagline', 'Your One-Stop Tech Shop', 'general'],
        ['site_email', 'info@geotrans.com', 'general'],
        ['site_phone', '+94 11 234 5678', 'general'],
        ['site_address', 'Colombo, Sri Lanka', 'general'],
        
        // Social Media
        ['facebook_url', '', 'social'],
        ['instagram_url', '', 'social'],
        ['tiktok_url', '', 'social'],
        
        // Business Settings
        ['currency_symbol', 'Rs', 'business'],
        ['tax_rate', '0', 'business'],
        ['shipping_cost', '350', 'business'],
        ['free_shipping_threshold', '5000', 'business'],
        
        // Email Settings
        ['smtp_host', '', 'email'],
        ['smtp_port', '587', 'email'],
        ['smtp_username', '', 'email'],
        ['smtp_password', '', 'email'],
        ['smtp_from_email', '', 'email'],
        ['smtp_from_name', 'GeoTrans', 'email'],
        
        // SEO Settings
        ['meta_description', 'Shop the latest electronics and gadgets at GeoTrans', 'seo'],
        ['meta_keywords', 'electronics, gadgets, computers, laptops, phones', 'seo'],
        ['google_analytics_id', '', 'seo'],
    ];
    
    $insert = "INSERT INTO site_settings (setting_key, setting_value, setting_group) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert);
    foreach ($default_settings as $setting) {
        $stmt->execute($setting);
    }
}

// Ensure tiktok_url exists for existing databases (FB/Instagram already existed).
$conn->exec("INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_group) VALUES ('tiktok_url', '', 'social')");

// Define which settings belong to which group (for upsert)
$setting_groups = [
    // General
    'site_name' => 'general',
    'site_tagline' => 'general',
    'site_email' => 'general',
    'site_phone' => 'general',
    'site_address' => 'general',
    // Social
    'facebook_url' => 'social',
    'instagram_url' => 'social',
    'tiktok_url' => 'social',
    // Business
    'currency_symbol' => 'business',
    'tax_rate' => 'business',
    'shipping_cost' => 'business',
    'free_shipping_threshold' => 'business',
    // Email
    'smtp_host' => 'email',
    'smtp_port' => 'email',
    'smtp_username' => 'email',
    'smtp_password' => 'email',
    'smtp_from_email' => 'email',
    'smtp_from_name' => 'email',
    // SEO
    'meta_description' => 'seo',
    'meta_keywords' => 'seo',
    'google_analytics_id' => 'seo',
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $upsert = "INSERT INTO site_settings (setting_key, setting_value, setting_group)
                   VALUES (?, ?, ?)
                   ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
        $stmt = $conn->prepare($upsert);

        foreach ($_POST as $key => $value) {
            if ($key !== 'submit' && isset($setting_groups[$key])) {
                $group = $setting_groups[$key];
                $stmt->execute([$key, $value, $group]);
            }
        }
        setFlashMessage('success', 'Settings updated successfully');
        header('Location: settings.php');
        exit;
    } catch (Exception $e) {
        setFlashMessage('error', 'Failed to update settings: ' . $e->getMessage());
    }
}

// Fetch all settings grouped by category
$query = "SELECT * FROM site_settings ORDER BY setting_group, setting_key";
$stmt = $conn->query($query);
$all_settings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group settings by category
$settings = [];
foreach ($all_settings as $setting) {
    $settings[$setting['setting_group']][$setting['setting_key']] = $setting['setting_value'];
}

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-cog mr-2"></i>Site Settings
        </h1>
    </div>

    <?php 
    $flash = getFlashMessage();
    if ($flash): 
    ?>
    <div class="bg-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-100 border border-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-400 text-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <!-- General Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">
                <i class="fas fa-info-circle mr-2"></i>General Settings
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                    <input type="text" name="site_name" value="<?= htmlspecialchars($settings['general']['site_name'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Tagline</label>
                    <input type="text" name="site_tagline" value="<?= htmlspecialchars($settings['general']['site_tagline'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                    <input type="email" name="site_email" value="<?= htmlspecialchars($settings['general']['site_email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                    <input type="text" name="site_phone" value="<?= htmlspecialchars($settings['general']['site_phone'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                    <input type="text" name="site_address" value="<?= htmlspecialchars($settings['general']['site_address'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Social Media Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">
                <i class="fas fa-share-alt mr-2"></i>Social Media Links
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-facebook text-blue-600 mr-1"></i>Facebook URL
                    </label>
                    <input type="url" name="facebook_url" value="<?= htmlspecialchars($settings['social']['facebook_url'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="https://facebook.com/yourpage">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-instagram text-pink-600 mr-1"></i>Instagram URL
                    </label>
                    <input type="url" name="instagram_url" value="<?= htmlspecialchars($settings['social']['instagram_url'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="https://instagram.com/yourpage">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-tiktok text-black mr-1"></i>TikTok URL
                    </label>
                    <input type="url" name="tiktok_url" value="<?= htmlspecialchars($settings['social']['tiktok_url'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="https://www.tiktok.com/@youraccount">
                </div>
            </div>
        </div>

        <!-- Business Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">
                <i class="fas fa-store mr-2"></i>Business Settings
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency Symbol</label>
                    <input type="text" name="currency_symbol" value="<?= htmlspecialchars($settings['business']['currency_symbol'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Rs">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="<?= htmlspecialchars($settings['business']['tax_rate'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           step="0.01" min="0">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Standard Shipping Cost</label>
                    <input type="number" name="shipping_cost" value="<?= htmlspecialchars($settings['business']['shipping_cost'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           step="0.01" min="0">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Free Shipping Threshold</label>
                    <input type="number" name="free_shipping_threshold" value="<?= htmlspecialchars($settings['business']['free_shipping_threshold'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           step="0.01" min="0">
                    <p class="text-xs text-gray-500 mt-1">Orders above this amount get free shipping</p>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">
                <i class="fas fa-search mr-2"></i>SEO Settings
            </h2>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Site description for search engines"><?= htmlspecialchars($settings['seo']['meta_description'] ?? '') ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="<?= htmlspecialchars($settings['seo']['meta_keywords'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="keyword1, keyword2, keyword3">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                    <input type="text" name="google_analytics_id" value="<?= htmlspecialchars($settings['seo']['google_analytics_id'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="G-XXXXXXXXXX or UA-XXXXXXXXX-X">
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">
                <i class="fas fa-envelope mr-2"></i>Email Settings (SMTP)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                    <input type="text" name="smtp_host" value="<?= htmlspecialchars($settings['email']['smtp_host'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="smtp.gmail.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                    <input type="number" name="smtp_port" value="<?= htmlspecialchars($settings['email']['smtp_port'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="587">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                    <input type="text" name="smtp_username" value="<?= htmlspecialchars($settings['email']['smtp_username'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                    <input type="password" name="smtp_password" value="<?= htmlspecialchars($settings['email']['smtp_password'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                    <input type="email" name="smtp_from_email" value="<?= htmlspecialchars($settings['email']['smtp_from_email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                    <input type="text" name="smtp_from_name" value="<?= htmlspecialchars($settings['email']['smtp_from_name'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="submit" name="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                <i class="fas fa-save mr-2"></i>Save All Settings
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
