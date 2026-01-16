<?php
/**
 * Registration Endpoints
 * GET /registrations/my - Get user's registrations
 */

function handleRegistrations($method, $action) {
    switch ($action) {
        case 'my':
            if ($method === 'GET') {
                getMyRegistrations();
            } else {
                sendResponse(false, 'Method not allowed', null, 405);
            }
            break;
            
        default:
            sendResponse(false, 'Endpoint not found', null, 404);
    }
}

/**
 * Get User's Registrations
 */
function getMyRegistrations() {
    $user = requireAuth();
    
    $db = getDB();
    
    $stmt = $db->prepare("
        SELECT r.*, e.nama_event, e.lokasi, e.kategori, e.tanggal, e.status, e.banner_url
        FROM registrations r
        JOIN events e ON r.event_id = e.id
        WHERE r.user_id = ?
        ORDER BY r.registered_at DESC
    ");
    $stmt->execute([$user['id']]);
    $registrations = $stmt->fetchAll();
    
    $formattedRegistrations = array_map(function($r) {
        return [
            'id' => (int) $r['id'],
            'user_id' => (int) $r['user_id'],
            'event_id' => (int) $r['event_id'],
            'registered_at' => $r['registered_at'],
            'event' => [
                'id' => (int) $r['event_id'],
                'nama_event' => $r['nama_event'],
                'lokasi' => $r['lokasi'],
                'kategori' => $r['kategori'],
                'tanggal' => $r['tanggal'],
                'status' => $r['status'],
                'banner_url' => $r['banner_url']
            ]
        ];
    }, $registrations);
    
    sendResponse(true, 'Registrations retrieved', $formattedRegistrations);
}
