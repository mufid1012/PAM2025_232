<?php
/**
 * User Endpoints
 * GET /users/profile - Get current user's profile
 * PUT /users/profile - Update profile
 * PUT /users/password - Change password
 */

function handleUsers($method, $action) {
    switch ($action) {
        case 'profile':
            if ($method === 'GET') {
                getProfile();
            } elseif ($method === 'PUT') {
                updateProfile();
            } else {
                sendResponse(false, 'Method not allowed', null, 405);
            }
            break;
            
        case 'password':
            if ($method === 'PUT') {
                changePassword();
            } else {
                sendResponse(false, 'Method not allowed', null, 405);
            }
            break;
            
        default:
            sendResponse(false, 'Endpoint not found', null, 404);
    }
}

/**
 * Get User Profile
 */
function getProfile() {
    $user = requireAuth();
    
    $formattedUser = [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
        'photo_url' => $user['photo_url']
    ];
    
    sendResponse(true, 'Profile retrieved', $formattedUser);
}

/**
 * Update User Profile
 */
function updateProfile() {
    $user = requireAuth();
    $data = getRequestBody();
    
    if (empty($data['name'])) {
        sendResponse(false, 'Nama harus diisi', null, 400);
    }
    
    $db = getDB();
    
    $stmt = $db->prepare("UPDATE users SET name = ?, photo_url = ? WHERE id = ?");
    $stmt->execute([
        $data['name'],
        $data['photo_url'] ?? $user['photo_url'],
        $user['id']
    ]);
    
    // Get updated user
    $stmt = $db->prepare("SELECT id, name, email, role, photo_url FROM users WHERE id = ?");
    $stmt->execute([$user['id']]);
    $updatedUser = $stmt->fetch();
    
    $formattedUser = [
        'id' => (int) $updatedUser['id'],
        'name' => $updatedUser['name'],
        'email' => $updatedUser['email'],
        'role' => $updatedUser['role'],
        'photo_url' => $updatedUser['photo_url']
    ];
    
    sendResponse(true, 'Profil berhasil diperbarui', $formattedUser);
}

/**
 * Change Password
 */
function changePassword() {
    $user = requireAuth();
    $data = getRequestBody();
    
    if (empty($data['current_password']) || empty($data['new_password'])) {
        sendResponse(false, 'Password lama dan baru harus diisi', null, 400);
    }
    
    if (strlen($data['new_password']) < 6) {
        sendResponse(false, 'Password baru minimal 6 karakter', null, 400);
    }
    
    $db = getDB();
    
    // Get current password hash
    $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user['id']]);
    $userData = $stmt->fetch();
    
    // Verify current password
    if (!password_verify($data['current_password'], $userData['password'])) {
        sendResponse(false, 'Password lama salah', null, 400);
    }
    
    // Update password
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([
        password_hash($data['new_password'], PASSWORD_DEFAULT),
        $user['id']
    ]);
    
    sendResponse(true, 'Password berhasil diubah');
}
