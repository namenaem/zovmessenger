<?php

function getChats($from_id, $to_id, $conn) {
    // Fetch messages in chronological order (oldest first)
    $sql = "SELECT * FROM chats 
            WHERE (from_id = ? AND to_id = ?) 
               OR (from_id = ? AND to_id = ?)
            ORDER BY created_at ASC";  // ASC = oldest first
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$from_id, $to_id, $to_id, $from_id]);

    return $stmt->fetchAll();
}

function getNewChats($from_id, $to_id, $conn, $last_id = 0) {
    // Fetch only new messages after the given ID
    $sql = "SELECT * FROM chats 
            WHERE ((from_id = ? AND to_id = ?) 
                OR (from_id = ? AND to_id = ?))
                AND chat_id > ?
            ORDER BY created_at ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$from_id, $to_id, $to_id, $from_id, $last_id]);

    return $stmt->fetchAll();
}

function getLastMessage($user1_id, $user2_id, $conn) {
    // Get the latest message in the conversation
    $sql = "SELECT message FROM chats
            WHERE (from_id = ? AND to_id = ?) 
               OR (from_id = ? AND to_id = ?)
            ORDER BY created_at DESC 
            LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user1_id, $user2_id, $user2_id, $user1_id]);
    
    return $stmt->fetchColumn();
}

// Helper function that counts unread messages
function getUnreadCount($from_id, $to_id, $conn) {
    $sql = "SELECT COUNT(*) FROM chats
            WHERE to_id = ? AND from_id = ? AND opened = 0";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$to_id, $from_id]);
    
    return $stmt->fetchColumn();
}