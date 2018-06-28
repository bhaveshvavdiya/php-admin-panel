<?php
    session_start();

    if(isset($_SESSION["username"]) && $_SESSION["username"]!=null && $_SESSION["username"]!=''){
      header('Location: home.php'); 
    }
    
    $error_msg = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);
      try{
        if($username == 'username' && $password == 'password' )
        {
            
            $_SESSION["username"] = $username;
            header("location:./home.php");
            exit();
        }else{
          $error_msg = "Invalid username / password";
        }
      }catch(Exception $e){
          $error_msg = "Invalid username / password";
      }
    }
?>
<div style="color: red;">
<?php echo $error_msg;?>
</div>
<form id='login' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Login</legend>
<input type='hidden' name='submitted' id='submitted' value='1'/>

<label for='username' >UserName*:</label>
<input type='text' name='username' id='username'  maxlength="50" />
<br>
<label for='password' >Password*:</label>
<input type='password' name='password' id='password' maxlength="50" />
<br>
<input type="submit" name="Submit" value="Submit">
<br><br>
</form>


