<?php
require_once 'config.php';

try {
    $username = 'admin';
    $password = 'admin123'; // คุณสามารถเปลี่ยนรหัสผ่านเริ่มต้นตรงนี้ได้
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // ตรวจสอบว่ามีผู้ใช้ชื่อ admin อยู่แล้วหรือไม่
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // ถ้ายังไม่มี ให้ทำการเพิ่มใหม่
        $insert = $pdo->prepare("INSERT INTO users (username, password, role, first_name, last_name) VALUES (?, ?, 'admin', 'System', 'Admin')");
        $insert->execute([$username, $hashedPassword]);
        echo "<h2 style='color:green;'>✅ สร้างบัญชี Admin เริ่มต้นสำเร็จ!</h2>";
    } else {
        // ถ้ามีอยู่แล้ว ให้อัปเดตรหัสผ่านใหม่ให้ถูกต้อง
        $update = $pdo->prepare("UPDATE users SET password = ?, role = 'admin' WHERE id = ?");
        $update->execute([$hashedPassword, $user['id']]);
        echo "<h2 style='color:orange;'>✅ อัปเดตรหัสผ่านของ Admin ให้ใหม่เรียบร้อยแล้ว!</h2>";
    }
    echo "<p>Username: <b>{$username}</b><br>Password: <b>{$password}</b></p>";
    echo "<a href='login.php'>คลิกที่นี่เพื่อไปหน้าเข้าสู่ระบบ</a>";
} catch(PDOException $e) {
    echo "<h2 style='color:red;'>เกิดข้อผิดพลาดกับฐานข้อมูล:</h2><p>" . $e->getMessage() . "</p>";
}
?>