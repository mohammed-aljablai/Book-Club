<?php
  try {
    include_once "models/model_user.php";
    $check_db = new UserDB("book_club", "root");
    if (
      (isset($_COOKIE['remember_me']) && 
      isset($_COOKIE['user_fullname']) && 
      isset($_COOKIE['user_email']) &&
      $check_db->checkUser($_COOKIE['remember_me'], $_COOKIE['user_email']))
    ) {
      setcookie("user_fullname", $_COOKIE['user_fullname'], ['expires' => time() + (30 * 24 * 60 * 60)]);
      setcookie("user_email", $_COOKIE['user_email'], ['expires' => time() + (30 * 24 * 60 * 60)]);
      $token = bin2hex(random_bytes(32));
      $expire = date('Y-m-d H:i:s', strtotime('+30 days'));
      setcookie('remember_me', $check_db->updateToken($token, $expire, $_COOKIE["user_email"]), [
        'expires' => time() + (30 * 24 * 60 * 60), // Valid for 30 days only
        'path' => '/',
        'secure' => true, // HTTPS only
        'httponly' => true, // JavaScript not allowed
        'samesite' => 'Strict', // stop CSRF
      ]);
      header("location: " . "home.php");
    }
  } catch (\Throwable $th) {
    //throw $th;
  }