<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Handle mark as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
    $message_id = $_POST['message_id'];
    $update_query = "UPDATE contact_messages SET is_read = 1 WHERE message_id = :id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':id', $message_id);
    $update_stmt->execute();
    
    $_SESSION['success'] = 'Message marked as read';
    header('Location: contact-messages.php');
    exit;
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $message_id = $_POST['message_id'];
    $delete_query = "DELETE FROM contact_messages WHERE message_id = :id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':id', $message_id);
    $delete_stmt->execute();
    
    $_SESSION['success'] = 'Message deleted';
    header('Location: contact-messages.php');
    exit;
}

// Get messages with filters
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM contact_messages WHERE 1=1";

if ($filter === 'unread') {
    $query .= " AND is_read = 0";
} elseif ($filter === 'read') {
    $query .= " AND is_read = 1";
}

if ($search) {
    $query .= " AND (name LIKE :search OR email LIKE :search OR subject LIKE :search)";
}

$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);

if ($search) {
    $search_param = "%$search%";
    $stmt->bindParam(':search', $search_param);
}

$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get unread count
$unread_query = "SELECT COUNT(*) as total FROM contact_messages WHERE is_read = 0";
$unread_stmt = $conn->query($unread_query);
$unread_count = $unread_stmt->fetch(PDO::FETCH_ASSOC)['total'];

include 'includes/header.php';
?>

<div class="p-6">
    <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Contact Messages</h1>
            <p class="text-gray-600"><?= $unread_count ?> unread messages</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <select name="filter" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All Messages</option>
                <option value="unread" <?= $filter === 'unread' ? 'selected' : '' ?>>Unread</option>
                <option value="read" <?= $filter === 'read' ? 'selected' : '' ?>>Read</option>
            </select>
            
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Search messages..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
            
            <button type="submit" class="bg-purple-custom text-white px-6 py-2 rounded-lg hover:bg-[#4f0a4f]">
                Filter
            </button>
        </form>
    </div>

    <!-- Messages Table -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Status</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Name</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Email</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Subject</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Date</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($messages as $msg): ?>
                <tr class="<?= $msg['is_read'] ? 'bg-white' : 'bg-blue-50' ?> hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <?php if ($msg['is_read']): ?>
                        <span class="inline-block w-3 h-3 bg-gray-300 rounded-full"></span>
                        <?php else: ?>
                        <span class="inline-block w-3 h-3 bg-blue-500 rounded-full"></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm <?= $msg['is_read'] ? 'text-gray-700' : 'font-semibold text-gray-900' ?>">
                        <?= htmlspecialchars($msg['name']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="text-purple-custom hover:underline">
                            <?= htmlspecialchars($msg['email']) ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        <?= htmlspecialchars($msg['subject'] ?: 'General Inquiry') ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= date('M d, Y h:i A', strtotime($msg['created_at'])) ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button onclick="viewMessage(<?= $msg['message_id'] ?>)" 
                                class="text-purple-custom hover:underline mr-3">View</button>
                        <?php if (!$msg['is_read']): ?>
                        <form method="POST" class="inline">
                            <input type="hidden" name="message_id" value="<?= $msg['message_id'] ?>">
                            <button type="submit" name="mark_read" class="text-green-600 hover:underline mr-3">Mark Read</button>
                        </form>
                        <?php endif; ?>
                        <form method="POST" class="inline" onsubmit="return confirm('Delete this message?')">
                            <input type="hidden" name="message_id" value="<?= $msg['message_id'] ?>">
                            <button type="submit" name="delete" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($messages)): ?>
        <div class="text-center py-8 text-gray-500">
            No messages found
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Message Modal -->
<div id="messageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex justify-between items-start">
                <h2 class="text-2xl font-bold text-gray-900">Message Details</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="messageContent" class="p-6">
            <!-- Content loaded dynamically -->
        </div>
    </div>
</div>

<script>
const messages = <?= json_encode($messages) ?>;

function viewMessage(messageId) {
    const message = messages.find(m => m.message_id == messageId);
    if (!message) return;
    
    const content = `
        <div class="space-y-4">
            <div class="border-b pb-4">
                <p class="text-sm text-gray-600">From</p>
                <p class="font-semibold text-gray-900">${message.name}</p>
                <p class="text-sm text-purple-custom">${message.email}</p>
                ${message.phone ? `<p class="text-sm text-gray-600">${message.phone}</p>` : ''}
            </div>
            
            <div class="border-b pb-4">
                <p class="text-sm text-gray-600">Subject</p>
                <p class="font-semibold text-gray-900">${message.subject || 'General Inquiry'}</p>
            </div>
            
            <div class="border-b pb-4">
                <p class="text-sm text-gray-600">Date</p>
                <p class="text-gray-900">${new Date(message.created_at).toLocaleString()}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-600 mb-2">Message</p>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-900 whitespace-pre-wrap">${message.message}</p>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4">
                <a href="mailto:${message.email}?subject=Re: ${message.subject || 'Your Inquiry'}" 
                   class="bg-purple-custom text-white px-6 py-2 rounded-lg hover:bg-[#4f0a4f]">
                    Reply via Email
                </a>
                ${!message.is_read ? `
                <form method="POST" class="inline">
                    <input type="hidden" name="message_id" value="${message.message_id}">
                    <button type="submit" name="mark_read" 
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                        Mark as Read
                    </button>
                </form>
                ` : ''}
            </div>
        </div>
    `;
    
    document.getElementById('messageContent').innerHTML = content;
    document.getElementById('messageModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('messageModal').classList.add('hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Close modal on outside click
document.getElementById('messageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
