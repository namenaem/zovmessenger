<?php

function getConversation($user_id, $conn) {
    // Get all unique conversations of the user
    $sql = "SELECT 
                c.conversation_id,
                CASE 
                    WHEN c.user_1 = :user_id THEN c.user_2 
                    ELSE c.user_1 
                END AS other_user_id
            FROM conversations c
            WHERE c.user_1 = :user_id OR c.user_2 = :user_id
            GROUP BY other_user_id
            ORDER BY c.conversation_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);

    $conversations = $stmt->fetchAll();
    $user_data = [];

    if (!empty($conversations)) {
        // Collect the IDs of all chat partners
        $other_user_ids = array_column($conversations, 'other_user_id');
        $placeholders = implode(',', array_fill(0, count($other_user_ids), '?'));
        $params = $other_user_ids;

        // Fetch chat partner info with a single query
        $sql_users = "SELECT user_id, username, name, p_p, last_seen 
                      FROM users 
                      WHERE user_id IN ($placeholders)";
        $stmt_users = $conn->prepare($sql_users);
        $stmt_users->execute($params);
        $users_result = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

        // Build an associative array keyed by user_id
        $users_map = [];
        foreach ($users_result as $user) {
            $users_map[$user['user_id']] = $user;
        }

        // Build the final result
        foreach ($conversations as $conv) {
            if (isset($users_map[$conv['other_user_id']])) {
                $user_data[] = $users_map[$conv['other_user_id']];
            }
        }
    }

    return $user_data;
}