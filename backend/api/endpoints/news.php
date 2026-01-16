<?php
/**
 * News Endpoints
 * GET /news - Get all news
 * GET /news/featured - Get featured news
 * GET /news/{id} - Get news by ID
 * POST /news - Create news (Admin)
 * PUT /news/{id} - Update news (Admin)
 * DELETE /news/{id} - Delete news (Admin)
 */

function handleNews($method, $id) {
    switch ($method) {
        case 'GET':
            if ($id === 'featured') {
                getFeaturedNews();
            } elseif ($id && is_numeric($id)) {
                getNewsById($id);
            } elseif (!$id || $id === '') {
                getAllNews();
            } else {
                sendResponse(false, 'Endpoint not found', null, 404);
            }
            break;
            
        case 'POST':
            createNews();
            break;
            
        case 'PUT':
            if ($id && is_numeric($id)) {
                updateNews($id);
            } else {
                sendResponse(false, 'News ID required', null, 400);
            }
            break;
            
        case 'DELETE':
            if ($id && is_numeric($id)) {
                deleteNews($id);
            } else {
                sendResponse(false, 'News ID required', null, 400);
            }
            break;
            
        default:
            sendResponse(false, 'Method not allowed', null, 405);
    }
}

/**
 * Get All News
 */
function getAllNews() {
    $db = getDB();
    
    $stmt = $db->query("SELECT * FROM news ORDER BY created_at DESC");
    $news = $stmt->fetchAll();
    
    $formattedNews = array_map(function($n) {
        return [
            'id' => (int) $n['id'],
            'title' => $n['title'],
            'content' => $n['content'],
            'image_url' => $n['image_url'],
            'created_at' => $n['created_at']
        ];
    }, $news);
    
    sendResponse(true, 'News retrieved', $formattedNews);
}

/**
 * Get Featured News (latest 3)
 */
function getFeaturedNews() {
    $db = getDB();
    
    $stmt = $db->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 3");
    $news = $stmt->fetchAll();
    
    $formattedNews = array_map(function($n) {
        return [
            'id' => (int) $n['id'],
            'title' => $n['title'],
            'content' => $n['content'],
            'image_url' => $n['image_url'],
            'created_at' => $n['created_at']
        ];
    }, $news);
    
    sendResponse(true, 'Featured news retrieved', $formattedNews);
}

/**
 * Get News by ID
 */
function getNewsById($id) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch();
    
    if (!$news) {
        sendResponse(false, 'News not found', null, 404);
    }
    
    $formattedNews = [
        'id' => (int) $news['id'],
        'title' => $news['title'],
        'content' => $news['content'],
        'image_url' => $news['image_url'],
        'created_at' => $news['created_at']
    ];
    
    sendResponse(true, 'News found', $formattedNews);
}

/**
 * Create News (Admin only)
 */
function createNews() {
    requireAdmin();
    
    $data = getRequestBody();
    
    if (empty($data['title']) || empty($data['content'])) {
        sendResponse(false, 'Judul dan konten harus diisi', null, 400);
    }
    
    $db = getDB();
    
    $stmt = $db->prepare("INSERT INTO news (title, content, image_url) VALUES (?, ?, ?)");
    $stmt->execute([
        $data['title'],
        $data['content'],
        $data['image_url'] ?? null
    ]);
    
    $newsId = $db->lastInsertId();
    
    // Get created news
    $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$newsId]);
    $news = $stmt->fetch();
    
    $formattedNews = [
        'id' => (int) $news['id'],
        'title' => $news['title'],
        'content' => $news['content'],
        'image_url' => $news['image_url'],
        'created_at' => $news['created_at']
    ];
    
    sendResponse(true, 'Berita berhasil dibuat', $formattedNews, 201);
}

/**
 * Update News (Admin only)
 */
function updateNews($id) {
    requireAdmin();
    
    $data = getRequestBody();
    
    $db = getDB();
    
    // Check if news exists
    $stmt = $db->prepare("SELECT id FROM news WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        sendResponse(false, 'Berita tidak ditemukan', null, 404);
    }
    
    if (empty($data['title']) || empty($data['content'])) {
        sendResponse(false, 'Judul dan konten harus diisi', null, 400);
    }
    
    $stmt = $db->prepare("UPDATE news SET title = ?, content = ?, image_url = ? WHERE id = ?");
    $stmt->execute([
        $data['title'],
        $data['content'],
        $data['image_url'] ?? null,
        $id
    ]);
    
    // Get updated news
    $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch();
    
    $formattedNews = [
        'id' => (int) $news['id'],
        'title' => $news['title'],
        'content' => $news['content'],
        'image_url' => $news['image_url'],
        'created_at' => $news['created_at']
    ];
    
    sendResponse(true, 'Berita berhasil diperbarui', $formattedNews);
}

/**
 * Delete News (Admin only)
 */
function deleteNews($id) {
    requireAdmin();
    
    $db = getDB();
    
    // Check if news exists
    $stmt = $db->prepare("SELECT id FROM news WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        sendResponse(false, 'Berita tidak ditemukan', null, 404);
    }
    
    $stmt = $db->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);
    
    sendResponse(true, 'Berita berhasil dihapus');
}
