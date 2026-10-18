<?php
  session_start();

  if (isset($_SESSION['username'])) {
    # database connection file
    include 'app/db.conn.php';

    include 'app/helpers/user.php';
    include 'app/helpers/conversations.php';
    include 'app/helpers/timeAgo.php';
    include 'app/helpers/last_chat.php';

    # Getting User data data
    $user = getUser($_SESSION['username'], $conn);

    # Getting User conversations
    $conversations = getConversation($user['user_id'], $conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZOV - Chats</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="./images/logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body {
      height: 100vh;
      overflow: hidden;
      margin: 0;
    }
    .app-container {
      display: flex;
      height: 100vh;
    }
    .chats-sidebar {
      width: 25%;
      border-right: 1px solid #dee2e6;
      display: flex;
      flex-direction: column;
    }
    .chat-area {
      width: 75%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f8f9fa;
    }
    .header {
      padding: 15px;
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
    }
    .chat-list {
      flex-grow: 1;
      overflow-y: auto;
    }
    .user-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
    }
    .online-status {
      width: 12px;
      height: 12px;
      background: #28a745;
      border-radius: 50%;
    }
    .chat-item:hover {
      background-color: #f8f9fa;
    }
    .no-chats {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .select-chat-message {
      text-align: center;
      padding: 20px;
    }
    .select-chat-message img {
      width: 150px;
      opacity: 0.5;
      margin-bottom: 20px;
    }
    .select-chat-text {
      color: #6c757d;
      font-size: 1.2rem;
    }
    .search-container {
      padding: 15px;
      border-bottom: 1px solid #dee2e6;
    }
    .search-results {
      display: none;
    }
  </style>
</head>
<body>
  <div class="app-container">
    <!-- Chats sidebar (25%) -->
    <div class="chats-sidebar">
      <!-- Header -->
      <div class="header">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <img src="uploads/<?=$user['p_p']?>" class="user-avatar me-3">
            <div>
              <h5 class="mb-0"><?=$user['name']?></h5>
              <small class="text-muted">Online</small>
            </div>
          </div>
          <a href="logout.php" class="btn btn-outline-danger btn-sm">Log out</a>
        </div>
      </div>

      <!-- Search -->
      <div class="search-container">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search..." id="searchText">
          <button class="btn btn-primary" id="serachBtn">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>

      <!-- Chat list -->
      <div class="chat-list">
        <?php if (!empty($conversations)) { ?>
          <ul class="list-group list-group-flush" id="chatList">
            <?php foreach ($conversations as $conversation) { ?>
              <li class="list-group-item chat-item">
                <a href="chat.php?user=<?=$conversation['username']?>" class="text-decoration-none text-dark">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                      <img src="uploads/<?=$conversation['p_p']?>" class="user-avatar me-3">
                      <div>
                        <h6 class="mb-0"><?=$conversation['name']?></h6>
                        <small class="text-muted">
                          <?php echo lastChat($_SESSION['user_id'], $conversation['user_id'], $conn); ?>
                        </small>
                      </div>
                    </div>
                    <?php if (last_seen($conversation['last_seen']) == "Online") { ?>
                      <div title="online" class="online-status"></div>
                    <?php } ?>
                  </div>
                </a>
              </li>
            <?php } ?>
          </ul>
        <?php } else { ?>
          <div class="no-chats" id="noChatsMessage">
            <img src="./images/logo.png" alt="ZOV" height="50" class="mb-3">
            <p class="text-muted mb-0">No chats yet</p>
            <small class="text-muted">Start a conversation with someone</small>
          </div>
        <?php } ?>
        
        <!-- Search results block -->
        <div id="searchResults" class="search-results"></div>
      </div>
    </div>

    <!-- Main area (75%) -->
    <div class="chat-area">
      <div class="select-chat-message">
        <img src="./images/logo.png" alt="ZOV">
        <h4 class="select-chat-text">Select a chat to start messaging</h4>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      // Track whether this is the first search
      let isFirstSearch = true;
      
      // Search
      $("#searchText").on("input", function(){
        var searchText = $(this).val();
        if(searchText == "") {
          // If the search field is empty, show the regular chat list
          $("#searchResults").hide().empty();
          if ($("#chatList").length) {
            $("#chatList").show();
          }
          $("#noChatsMessage").show();
          isFirstSearch = true; // Reset the flag when the search is cleared
          return;
        }
        
        // Hide the default elements on the first search
        if (isFirstSearch) {
          if ($("#chatList").length) {
            $("#chatList").hide();
          }
          $("#noChatsMessage").hide();
          isFirstSearch = false;
        }
        
        // Show the search results block
        $("#searchResults").show();
        
        // Run the search
        $.post('app/ajax/search.php',
               { key: searchText },
               function(data, status){
                 if (data.trim() === "") {
                   $("#searchResults").html('<div class="no-chats"><p class="text-muted">Nothing found</p></div>');
                 } else {
                   $("#searchResults").html('<ul class="list-group list-group-flush">' + data + '</ul>');
                 }
               });
      });
      
      // Search using the button
      $("#serachBtn").on("click", function(){
        $("#searchText").trigger("input");
      });

      // Auto update last seen
      let lastSeenUpdate = function(){
        $.get("app/ajax/update_last_seen.php");
      }
      lastSeenUpdate();
      setInterval(lastSeenUpdate, 10000);
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