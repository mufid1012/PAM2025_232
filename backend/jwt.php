<?php
/**
 * Simple JWT Implementation for RunRace API
 */

class JWT {
    
    /**
     * Generate JWT Token
     */
    public static function encode($payload, $secret) {
        $header = self::base64UrlEncode(json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]));
        
        $payload['iat'] = time();
        $payload['exp'] = time() + JWT_EXPIRY;
        
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));
        
        $signature = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$payloadEncoded", $secret, true)
        );
        
        return "$header.$payloadEncoded.$signature";
    }
    
    /**
     * Decode and Verify JWT Token
     */
    public static function decode($token, $secret) {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return null;
        }
        
        list($header, $payload, $signature) = $parts;
        
        // Verify signature
        $expectedSignature = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", $secret, true)
        );
        
        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }
        
        // Decode payload
        $payloadData = json_decode(self::base64UrlDecode($payload), true);
        
        // Check expiration
        if (isset($payloadData['exp']) && $payloadData['exp'] < time()) {
            return null;
        }
        
        return $payloadData;
    }
    
    /**
     * Base64 URL Encode
     */
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Base64 URL Decode
     */
    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}

/**
 * Get Authenticated User from Token
 */
function getAuthenticatedUser() {
    $token = getBearerToken();
    
    if (!$token) {
        return null;
    }
    
    $payload = JWT::decode($token, JWT_SECRET);
    
    if (!$payload || !isset($payload['user_id'])) {
        return null;
    }
    
    // Get user from database
    $db = getDB();
    $stmt = $db->prepare("SELECT id, name, email, role, photo_url FROM users WHERE id = ?");
    $stmt->execute([$payload['user_id']]);
    
    return $stmt->fetch();
}

/**
 * Require Authentication Middleware
 */
function requireAuth() {
    $user = getAuthenticatedUser();
    
    if (!$user) {
        sendResponse(false, 'Unauthorized. Please login.', null, 401);
    }
    
    return $user;
}

/**
 * Require Admin Role Middleware
 */
function requireAdmin() {
    $user = requireAuth();
    
    if ($user['role'] !== 'admin') {
        sendResponse(false, 'Forbidden. Admin access required.', null, 403);
    }
    
    return $user;
}
