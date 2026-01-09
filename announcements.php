<?php
// Fetch announcements from posting table
// This file is included in dashboards to display recent announcements

if (!isset($pdo)) {
    require_once __DIR__ . '/../db.php';
}

try {
    $stmt = $pdo->prepare("SELECT id, title, message, audience, department, created_at, image FROM posting ORDER BY created_at DESC LIMIT 6");
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($announcements)) {
        echo '<h3 style="color:#1f8a2b; margin-top:30px; margin-bottom:15px;">📢 Recent Announcements</h3>';
        echo '<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:15px;">';
        
        foreach ($announcements as $post) {
            $audience_text = ($post['audience'] === 'all') ? 'All Employees' : 'Department: ' . htmlspecialchars($post['department'] ?? 'General');
            $created_date = date('M d, Y h:i A', strtotime($post['created_at'] ?? 'now'));
            $title = htmlspecialchars($post['title'] ?? 'Untitled', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $message = htmlspecialchars($post['message'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $message_preview = strlen($message) > 150 ? substr($message, 0, 150) . '...' : $message;
            
            echo '<div style="background:#f9f9f9; border-radius:10px; padding:15px; border-left:4px solid #1f8a2b; box-shadow:0 2px 8px rgba(0,0,0,0.1);">';
            
            // Display image if exists
            if (!empty($post['image'])) {
                $image_path = htmlspecialchars($post['image'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                echo '<img src="../hr/uploads/announcements/' . $image_path . '" alt="Announcement" style="width:100%; height:140px; object-fit:cover; border-radius:8px; margin-bottom:10px;">';
            }
            
            echo '<h4 style="margin:0 0 8px 0; color:#1f8a2b; font-size:16px;">' . $title . '</h4>';
            echo '<p style="margin:0 0 10px 0; color:#555; font-size:14px; line-height:1.4;">' . nl2br($message_preview) . '</p>';
            echo '<small style="color:#999; font-size:12px;">' . $audience_text . ' • ' . $created_date . '</small>';
            
            echo '</div>';
        }
        
        echo '</div>';
    } else {
        echo '<p style="color:#999; text-align:center; margin-top:20px;">No announcements yet.</p>';
    }
} catch (PDOException $e) {
    echo '<p style="color:red; text-align:center;">Error loading announcements: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
