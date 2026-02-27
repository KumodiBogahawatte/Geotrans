<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php';
require_once '../includes/helpers.php';
$database = new Database();
$conn = $database->getConnection();

// Handle upload and save
// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM banners WHERE banner_id = ?");
    $stmt->execute([$delete_id]);
    setFlashMessage('success', 'Banner deleted successfully');
    header('Location: banners.php');
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_id'])) {
    $banner_type = $_POST['banner_type'] ?? 'product';
    $banner_title = $_POST['banner_title'] ?? '';
    $banner_link = $_POST['banner_link'] ?? '';
    $banner_order = intval($_POST['banner_order'] ?? 0);
    $media_path = '';
    if (isset($_FILES['banner_media']) && $_FILES['banner_media']['error'] === 0) {
        $ext = pathinfo($_FILES['banner_media']['name'], PATHINFO_EXTENSION);
        $filename = 'banner_' . time() . '_' . rand(1000,9999) . '.' . $ext;
        $target = '../assets/images/banners/' . $filename;
        if (move_uploaded_file($_FILES['banner_media']['tmp_name'], $target)) {
            $media_path = 'assets/images/banners/' . $filename;
        }
    }
    if (isset($_POST['edit_id'])) {
        // Update existing banner
        $edit_id = intval($_POST['edit_id']);
        if ($media_path) {
            $stmt = $conn->prepare("UPDATE banners SET banner_type=?, banner_title=?, banner_media=?, banner_link=?, banner_order=? WHERE banner_id=?");
            $stmt->execute([$banner_type, $banner_title, $media_path, $banner_link, $banner_order, $edit_id]);
        } else {
            $stmt = $conn->prepare("UPDATE banners SET banner_type=?, banner_title=?, banner_link=?, banner_order=? WHERE banner_id=?");
            $stmt->execute([$banner_type, $banner_title, $banner_link, $banner_order, $edit_id]);
        }
        setFlashMessage('success', 'Banner updated successfully');
    } else {
        // Add new banner
        $stmt = $conn->prepare("INSERT INTO banners (banner_type, banner_title, banner_media, banner_link, banner_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$banner_type, $banner_title, $media_path, $banner_link, $banner_order]);
        setFlashMessage('success', 'Banner added successfully');
    }
    header('Location: banners.php');
    exit;
}

// Fetch banners
$stmt = $conn->prepare("SELECT * FROM banners ORDER BY banner_order ASC, updated_at DESC LIMIT 10");
$stmt->execute();
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Manage Product Page Banners</h1>
    <?php $flash = getFlashMessage(); if ($flash): ?>
    <div class="bg-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-100 border border-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-400 text-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6 mb-8">
            <?php
            $edit_mode = false;
            $edit_banner = null;
            if (isset($_GET['edit'])) {
                $edit_id = intval($_GET['edit']);
                $stmt = $conn->prepare("SELECT * FROM banners WHERE banner_id = ?");
                $stmt->execute([$edit_id]);
                $edit_banner = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($edit_banner) $edit_mode = true;
            }
            ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Banner Title</label>
                    <input type="text" name="banner_title" class="w-full px-4 py-2 border rounded-lg" value="<?= $edit_mode ? htmlspecialchars($edit_banner['banner_title']) : '' ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Banner Link (optional)</label>
                    <input type="text" name="banner_link" class="w-full px-4 py-2 border rounded-lg" value="<?= $edit_mode ? htmlspecialchars($edit_banner['banner_link']) : '' ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Banner Media (Image/Video)</label>
                    <input type="file" name="banner_media" accept="image/*,video/*" class="w-full">
                    <?php if ($edit_mode && $edit_banner['banner_media']): ?>
                        <div class="mt-2 text-xs text-gray-500">Current:
                            <?php if (preg_match('/\.(mp4|webm|ogg)$/i', $edit_banner['banner_media'])): ?>
                                <video src="../<?= $edit_banner['banner_media'] ?>" controls class="w-full h-32 object-cover"></video>
                            <?php else: ?>
                                <img src="../<?= $edit_banner['banner_media'] ?>" alt="Banner" class="w-full h-32 object-cover">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Order</label>
                    <input type="number" name="banner_order" value="<?= $edit_mode ? intval($edit_banner['banner_order']) : 0 ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <input type="hidden" name="banner_type" value="product">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="edit_id" value="<?= intval($edit_banner['banner_id']) ?>">
                <?php endif; ?>
            </div>
            <button type="submit" class="mt-6 bg-purple-custom text-white px-6 py-2 rounded-lg font-semibold">
                <?= $edit_mode ? 'Update Banner' : 'Add Banner' ?>
            </button>
    </form>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($banners as $banner): ?>
        <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
            <?php if (preg_match('/\.(mp4|webm|ogg)$/i', $banner['banner_media'])): ?>
                <video src="../<?= $banner['banner_media'] ?>" controls class="w-full h-64 object-cover mb-3"></video>
            <?php else: ?>
                <img src="../<?= $banner['banner_media'] ?>" alt="Banner" class="w-full h-64 object-cover mb-3">
            <?php endif; ?>
            <div class="text-center">
                <div class="font-semibold text-lg mb-1"><?= htmlspecialchars($banner['banner_title']) ?></div>
                <?php if ($banner['banner_link']): ?>
                <a href="<?= htmlspecialchars($banner['banner_link']) ?>" class="text-purple-custom hover:underline text-sm">Visit Link</a>
                <?php endif; ?>
                    <div class="mt-3 flex justify-center gap-2">
                        <a href="banners.php?edit=<?= $banner['banner_id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">Edit</a>
                        <form method="POST" action="banners.php" onsubmit="return confirm('Delete this banner?');" style="display:inline-block">
                            <input type="hidden" name="delete_id" value="<?= $banner['banner_id'] ?>">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Delete</button>
                        </form>
                    </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
