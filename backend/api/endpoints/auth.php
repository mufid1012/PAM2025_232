<?php
/**
 * Authentication Endpoints
 * POST /auth/login - Login
 * POST /auth/logout - Logout
 * GET /auth/me - Get current user
 */

function handleAuth($method, $action) {
    switch ($action) {
        case 'login':
            if ($method === 'POST') {
                login();
            } else {
                sendResponse(false, 'Method not allowed', null, 405);
            }
            break;
            
        case 'register':
            if ($method === 'POST') {
                register();
            } else {
                sendResponse(false, 'Method not allowed', null, 405);
            }
            break;
            
        case 'logout':
            if ($method === 'POST') {
                logout();
            } else {
                sendResponse(false, 'Method not allowed', null, 405);
            }
            break;
            
        case 'me':
            if ($method === 'GET') {
                getCurrentUser();
            } else {
                sendResponse(false, 'Method not allowed', null, 405);
            }
            break;
            
        default:
            sendResponse(false, 'Endpoint not found', null, 404);
    }
}

/**
 * Login
 */
function login() {
    $data = getRequestBody();
    
    if (empty($data['email']) || empty($data['password'])) {
        sendResponse(false, 'Email dan password harus diisi', null, 400);
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch();
    
    // Check if email exists
    if (!$user) {
        sendResponse(false, 'Email belum terdaftar. Silakan daftar terlebih dahulu.', null, 401);
    }
    
    // Check if password is correct
    if (!password_verify($data['password'], $user['password'])) {
        sendResponse(false, 'Password salah. Silakan coba lagi.', null, 401);
    }
    
    // Generate JWT token
    $token = JWT::encode([
        'user_id' => $user['id'],
        'email' => $user['email'],
        'role' => $user['role']
    ], JWT_SECRET);
    
    // Send login response with token and user
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Login berhasil',
        'token' => $token,
        'user' => [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'photo_url' => $user['photo_url']
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

/**
 * Register
 */
function register() {
    $data = getRequestBody();
    
    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        sendResponse(false, 'Nama, email, dan password harus diisi', null, 400);
    }
    
    if (strlen($data['password']) < 6) {
        sendResponse(false, 'Password minimal 6 karakter', null, 400);
    }
    
    $db = getDB();
    
    // Check if email exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        sendResponse(false, 'Email sudah terdaftar', null, 400);
    }
    
    // Create user
    $stmt = $db->prepare("INSERT INTO users (name, email, password, role, photo_url) VALUES (?, ?, ?, 'user', ?)");
    $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($data['name']) . '&background=FF5722&color=fff';
    $stmt->execute([
        $data['name'],
        $data['email'],
        password_hash($data['password'], PASSWORD_DEFAULT),
        $photoUrl
    ]);
    
    sendResponse(true, 'Registrasi berhasil. Silakan login.');
}

/**
 * Logout
 */
function logout() {
    // With JWT, logout is handled client-side by removing the token
    // Server can optionally blacklist tokens here
    sendResponse(true, 'Logout berhasil');
}

/**
 * Get Current User
 */
function getCurrentUser() {
    $user = requireAuth();
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'User found',
        'token' => null,
        'user' => [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'photo_url' => $user['photo_url']
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit();
}
