<?php
  session_start();

  if (isset($_SESSION['username'])) {
    # database connection file
    include 'app/db.conn.php';

    include 'app/helpers/user.php';
    include 'app/helpers/chat.php';
    include 'app/helpers/opened.php';
    include 'app/helpers/conversations.php';
    include 'app/helpers/timeAgo.php';
    include 'app/helpers/last_chat.php';

    if (!isset($_GET['user'])) {
      header("Location: home.php");
      exit;
    }

    # Getting User data data
    $chatWith = getUser($_GET['user'], $conn);
    $user = getUser($_SESSION['username'], $conn);
    $conversations = getConversation($user['user_id'], $conn);

    if (empty($chatWith)) {
      header("Location: home.php");
      exit;
    }

    $chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);

    opened($chatWith['user_id'], $conn, $chats);
    
    // Check that the sound files exist
    $newMsgSound = file_exists('images/newmsg.mp3') ? 'images/newmsg.mp3' : '';
    $msgSendSound = file_exists('images/msgsend.mp3') ? 'images/msgsend.mp3' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZOV - Chat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="icon" href="images/logo.png">
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #2c3e50;
      --light-bg: #f8f9fc;
      --border-color: #e0e0e0;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      height: 100vh;
      margin: 0;
      overflow: hidden;
    }
    
    .app-container {
      display: flex;
      height: 100vh;
    }
    
    /* Sidebar styles (25%) */
    .sidebar {
      width: 25%;
      border-right: 1px solid var(--border-color);
      display: flex;
      flex-direction: column;
      background-color: white;
    }
    
    .sidebar-header {
      padding: 15px;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }
    
    .conversations-list {
      flex-grow: 1;
      overflow-y: auto;
    }
    
    .conversation-item {
      padding: 10px 15px;
      border-bottom: 1px solid var(--border-color);
      cursor: pointer;
      transition: background-color 0.2s;
    }
    
    .conversation-item:hover {
      background-color: var(--light-bg);
    }
    
    .conversation-active {
      background-color: #e9f0ff;
    }
    
    .online-status {
      width: 8px;
      height: 8px;
      background: #28a745;
      border-radius: 50%;
      display: inline-block;
      margin-left: 5px;
    }
    
    /* Chat area styles (75%) */
    .chat-area {
      width: 75%;
      display: flex;
      flex-direction: column;
      background-color: var(--light-bg);
    }
    
    .chat-header {
      padding: 12px 15px;
      border-bottom: 1px solid var(--border-color);
      background-color: white;
      display: flex;
      align-items: center;
    }
    
    .chat-header-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 12px;
    }
    
    .chat-messages {
      flex-grow: 1;
      padding: 15px 20px;
      overflow-y: auto;
      background-color: #f5f7fb;
      display: flex;
      flex-direction: column;
    }
    
    /* Compact message bubbles */
    .message {
      max-width: 65%;
      padding: 8px 12px;
      border-radius: 12px;
      margin-bottom: 8px;
      position: relative;
      word-wrap: break-word;
      font-size: 0.9rem;
      line-height: 1.4;
    }
    
    .message-sent {
      background-color: var(--primary-color);
      color: white;
      margin-left: auto;
      border-bottom-right-radius: 4px;
    }
    
    .message-received {
      background-color: white;
      margin-right: auto;
      border-bottom-left-radius: 4px;
      box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    }
    
    .message-time {
      font-size: 0.7rem;
      opacity: 0.8;
      margin-top: 3px;
      display: block;
      text-align: right;
    }
    
    .message-input-container {
      padding: 12px 15px;
      background-color: white;
      border-top: 1px solid var(--border-color);
      display: flex;
      align-items: center;
    }
    
    .message-input {
      flex-grow: 1;
      border-radius: 18px;
      padding: 8px 15px;
      border: 1px solid var(--border-color);
      resize: none;
      outline: none;
      font-size: 0.9rem;
      max-height: 120px;
    }
    
    .send-button {
      margin-left: 10px;
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background-color: var(--primary-color);
      color: white;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      flex-shrink: 0;
    }
    
    .no-messages {
      text-align: center;
      color: #6c757d;
      padding: 30px;
      font-size: 0.9rem;
      margin: auto;
    }
    
    .search-container {
      padding: 12px 15px;
      border-bottom: 1px solid var(--border-color);
    }
    
    /* Scrollbar styling */
    ::-webkit-scrollbar {
      width: 5px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 3px;
    }
    
    .last-message {
      font-size: 0.8rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 180px;
    }
  </style>
</head>
<body>
  <!-- Notification sounds -->
  <?php if ($newMsgSound): ?>
  <audio id="newMessageSound" preload="auto">
    <source src="<?= $newMsgSound ?>" type="audio/mpeg">
  </audio>
  <?php endif; ?>
  
  <?php if ($msgSendSound): ?>
  <audio id="sendMessageSound" preload="auto">
    <source src="<?= $msgSendSound ?>" type="audio/mpeg">
  </audio>
  <?php endif; ?>

  <div class="app-container">
    <!-- Sidebar (25%) -->
    <div class="sidebar">
      <div class="sidebar-header">
        <div class="d-flex align-items-center">
          <img src="uploads/<?=$user['p_p']?>" class="user-avatar">
          <div>
            <h6 class="mb-0" style="font-size: 0.95rem;"><?=$user['name']?></h6>
            <small class="text-muted" style="font-size: 0.75rem;">Online</small>
          </div>
        </div>
        <a href="logout.php" class="btn btn-outline-danger btn-sm" style="padding: 0.25rem 0.5rem;">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </div>
      
      <!-- Search -->
      <div class="search-container">
        <div class="input-group" style="height: 38px;">
          <input type="text" class="form-control" placeholder="Search..." id="searchText" style="font-size: 0.85rem; height: 38px;">
          <button class="btn btn-primary" id="searchBtn" style="height: 38px; width: 38px;">
            <i class="fas fa-search" style="font-size: 0.9rem;"></i>
          </button>
        </div>
      </div>
      
      <!-- Chat list -->
      <div class="conversations-list" id="conversationsList">
        <?php if (!empty($conversations)): ?>
          <?php foreach ($conversations as $conversation): ?>
            <a href="chat.php?user=<?=$conversation['username']?>" 
               class="d-block text-decoration-none text-dark conversation-item <?=($conversation['user_id'] == $chatWith['user_id']) ? 'conversation-active' : ''?>">
              <div class="d-flex align-items-center">
                <img src="uploads/<?=$conversation['p_p']?>" class="user-avatar">
                <div style="flex: 1; min-width: 0;">
                  <div class="d-flex justify-content-between align-items-center">
                    <strong style="font-size: 0.9rem;"><?=$conversation['name']?></strong>
                    <?php if (last_seen($conversation['last_seen']) == "Online"): ?>
                      <span class="online-status" title="Online"></span>
                    <?php endif; ?>
                  </div>
                  <p class="mb-0 text-muted last-message">
                    <?=lastChat($_SESSION['user_id'], $conversation['user_id'], $conn)?>
                  </p>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="p-3 text-center text-muted" style="font-size: 0.85rem;">
            <i class="far fa-comments fa-lg mb-2"></i>
            <p class="mb-1">No chats yet</p>
            <small>Start a conversation with someone</small>
          </div>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Chat area (75%) -->
    <div class="chat-area">
      <div class="chat-header">
        <img src="uploads/<?=$chatWith['p_p']?>" class="chat-header-avatar">
        <div>
          <h5 class="mb-0" style="font-size: 1rem;"><?=$chatWith['name']?></h5>
          <small class="text-muted" style="font-size: 0.75rem;">
            <?php if (last_seen($chatWith['last_seen']) == "Online"): ?>
              <span class="text-success">Online</span>
            <?php else: ?>
              Last seen <?=last_seen($chatWith['last_seen'])?>
            <?php endif; ?>
          </small>
        </div>
      </div>
      
      <div class="chat-messages" id="chatBox">
        <?php if (!empty($chats)): ?>
          <?php foreach($chats as $chat): ?>
            <div class="message <?=($chat['from_id'] == $_SESSION['user_id']) ? 'message-sent' : 'message-received'?>">
              <?=$chat['message']?>
              <span class="message-time"><?=$chat['created_at']?></span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-messages">
            <i class="far fa-comments fa-2x mb-2"></i>
            <p>No messages yet, start the conversation</p>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="message-input-container">
        <textarea class="message-input" id="message" placeholder="Type a message..." rows="1"></textarea>
        <button class="send-button" id="sendBtn">
          <i class="fas fa-paper-plane"></i>
        </button>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    // Notification sounds
    const newMessageSound = document.getElementById('newMessageSound');
    const sendMessageSound = document.getElementById('sendMessageSound');
    
    // Auto-scroll to bottom of chat
    var scrollDown = function(){
      let chatBox = document.getElementById('chatBox');
      chatBox.scrollTop = chatBox.scrollHeight;
    }
    
    scrollDown();
    
    // Play a sound with error handling
    function playSound(sound) {
      if (sound) {
        try {
          sound.currentTime = 0;
          sound.play().catch(e => console.log("Sound play failed:", e));
        } catch(e) {
          console.log("Sound play error:", e);
        }
      }
    }
    
    $(document).ready(function(){
      // Send message
      $("#sendBtn").on('click', function(){
        let message = $("#message").val();
        if (message.trim() == "") return;
        
        // Play the send sound
        playSound(sendMessageSound);
        
        $.post("app/ajax/insert.php", {
          message: message,
          to_id: <?=$chatWith['user_id']?>
        }, function(data, status){
          $("#message").val("");
          // Reset the textarea height
          $("#message").css('height', 'auto');
          
          // Append the new message
          $("#chatBox").append(`
            <div class="message message-sent">
              ${message}
              <span class="message-time">Just now</span>
            </div>
          `);
          
          // Remove the "no messages" block if present
          $(".no-messages").remove();
          
          scrollDown();
          
          // Refresh the chat list after sending a message
          updateConversationsList();
        });
      });
      
      // Send message on Enter key (but allow Shift+Enter for new line)
      $("#message").on('keypress', function(e){
        if(e.which == 13 && !e.shiftKey) {
          e.preventDefault();
          $("#sendBtn").click();
        }
      });
      
      // Auto-resize textarea
      function adjustTextareaHeight() {
        const textarea = document.getElementById('message');
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
      }
      
      document.getElementById('message').addEventListener('input', adjustTextareaHeight);
      
      // Search functionality
      $("#searchText").on("input", function(){
        var searchText = $(this).val();
        if(searchText == "") {
          updateConversationsList();
          return;
        }
        $.post('app/ajax/search.php',
          { key: searchText },
          function(data, status){
            $("#conversationsList").html(data);
          });
      });

      $("#searchBtn").on("click", function(){
        var searchText = $("#searchText").val();
        if(searchText == "") {
          updateConversationsList();
          return;
        }
        $.post('app/ajax/search.php',
          { key: searchText },
          function(data, status){
            $("#conversationsList").html(data);
          });
      });

      // Auto-update last seen
      let lastSeenUpdate = function(){
        $.get("app/ajax/update_last_seen.php");
      }
      lastSeenUpdate();
      setInterval(lastSeenUpdate, 10000);
      
      // Auto-refresh messages
      let fetchData = function(){
        $.post("app/ajax/getMessage.php", 
          {
            id_2: <?=$chatWith['user_id']?>
          },
          function(data, status){
            if (data.trim() != "") {
              // Check for new messages
              if ($("#chatBox").html().indexOf(data) === -1) {
                // Append the new message
                $("#chatBox").append(data);
                
                // Remove the "no messages" block if present
                $(".no-messages").remove();
                
                scrollDown();
                
                // Play the new message sound
                playSound(newMessageSound);
              }
            }
          });
      }
      
      fetchData();
      setInterval(fetchData, 1000);
      
      // Auto-update conversations list
      function updateConversationsList() {
        $.post("app/helpers/update_conversations.php",
          function(data, status) {
            if (data) {
              $("#conversationsList").html(data);
            }
          }).fail(function() {
            console.log("Conversations update failed");
          });
      }
      
      // Refresh the chat list every 5 seconds
      updateConversationsList();
      setInterval(updateConversationsList, 5000);
    });
  </script>
</body>
</html>
<?php
  } else {
    header("Location: index.php");
    exit;
  }
?>