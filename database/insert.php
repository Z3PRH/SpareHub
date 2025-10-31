<?php
// DB config - update user/password if needed
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'ullivada');
define('DB_NAME', 'sparehub');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo "DB connection failed: " . $mysqli->connect_error;
    exit;
}

$user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
$name    = isset($_POST['name']) ? trim($_POST['name']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone   = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$password= isset($_POST['password']) ? $_POST['password'] : '';
$confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

if ($user_id === '' || $name === '' || $email === '' || $phone === '' || $password === '' || $confirm === '') {
    // redirect back with info to show message and prefill fields (no passwords)
    $qs = http_build_query(['error'=>'required','user_id'=>$user_id,'name'=>$name,'email'=>$email,'phone'=>$phone]);
    header('Location: ../pages/signup.html?'.$qs, true, 303);
    exit;
}

if ($password !== $confirm) {
    $qs = http_build_query(['error'=>'pass_mismatch','user_id'=>$user_id,'name'=>$name,'email'=>$email,'phone'=>$phone]);
    header('Location: ../pages/signup.html?'.$qs, true, 303);
    exit;
}

if (!ctype_digit($user_id)) {
    $qs = http_build_query(['error'=>'invalid_userid','name'=>$name,'email'=>$email,'phone'=>$phone]);
    header('Location: ../pages/signup.html?'.$qs, true, 303);
    exit;
}
$user_id = (int)$user_id;

if (!preg_match('/^\d{7,15}$/', $phone)) {
    $qs = http_build_query(['error'=>'invalid_phone','user_id'=>$user_id,'name'=>$name,'email'=>$email]);
    header('Location: ../pages/signup.html?'.$qs, true, 303);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $qs = http_build_query(['error'=>'invalid_email','user_id'=>$user_id,'name'=>$name,'phone'=>$phone]);
    header('Location: ../pages/signup.html?'.$qs, true, 303);
    exit;
}

// per request: store password as plain text (NOT recommended in production)
$password_plain = $password;

// role is always buyer
$role = 'buyer';
$created_at = date('Y-m-d H:i:s');

// SQL uses column names present in your DB (update if your column is `user-id`)
$sql = "INSERT INTO `users` (`user_id`, `name`, `password`, `role`, `email`, `phone`, `created_at`)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo "Prepare failed: " . $mysqli->error;
    exit;
}

$stmt->bind_param('issssss', $user_id, $name, $password_plain, $role, $email, $phone, $created_at);

if ($stmt->execute()) {
    $stmt->close();
    $mysqli->close();
    // confirmation page and redirect to login after 3 seconds
    echo '<!doctype html><html lang="en"><head>
      <meta charset="utf-8">
      <meta http-equiv="refresh" content="3;url=../pages/Login.html">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <title>Account Created</title>
      <link rel="stylesheet" href="../styles/login.css">
      <style>
        .msg-wrap{min-height:60vh;display:flex;align-items:center;justify-content:center;padding:30px}
        .msg-card{background:#fff;padding:28px;border-radius:10px;box-shadow:0 6px 20px rgba(0,0,0,0.08);text-align:center}
        .msg-card h2{color:#333;margin-bottom:8px}
        .msg-card p{color:#666;margin-bottom:12px}
        .msg-card a{color:#ff7878;font-weight:700}
      </style>
    </head><body>
      <div class="msg-wrap"><div class="msg-card">
        <h2>Account created</h2>
        <p>Your account was created successfully. Redirecting to the login page...</p>
        <p><a href="../pages/Login.html">Go now</a> — or wait 3 seconds.</p>
      </div></div>
      <script>setTimeout(function(){ window.location.href = "../pages/Login.html"; }, 3000);</script>
    </body></html>';
    exit;
} else {
    if ($mysqli->errno === 1062) {
        // duplicate entry (user_id or other unique). Ask user to change user_id.
        $qs = http_build_query(['error'=>'duplicate','user_id'=>$user_id,'name'=>$name,'email'=>$email,'phone'=>$phone]);
        header('Location: ../pages/signup.html?'.$qs, true, 303);
        exit;
    } else {
        http_response_code(500);
        echo "Insert failed: " . $stmt->error;
    }
    $stmt->close();
    $mysqli->close();
    exit;
}
?>