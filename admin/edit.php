<?php

  session_start();
    if(!isset($_SESSION["username"]) || $_SESSION["username"]==null || $_SESSION["username"]==''){
    header('Location: index.php'); 
  }


  
  $path =  $_REQUEST["path"];
  $file_content = '';
  

  $isValidPath = true;
  if (strpos($path, '/..') == true) {
    $isValidPath = false;
  } else {
    foreach(explode('/',$path) as $path_part){
      if($path_part == 'admin'){
        $isValidPath = false;
        break;
      }
    }
  }
  if(!$isValidPath){
    header('Location: home.php'); 
  }

  if (!file_exists($path)) {
      echo '<div style="color: red">'.$path . " is not exists.</div>";      
  } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $file_content  = file_get_contents($path);
     // The request is using the POST method
  } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     // The request is using the POST method
     $file_content = $_POST['file_content'];
      file_put_contents($path, $file_content);
     echo '<div style="color: green">'.$path . " updated.</div>";
  } else {
    echo 'Not found '. $_SERVER['REQUEST_METHOD'];
  }
?>

<form id='editFile' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Edit File</legend>
<input type='hidden' name='path' id='path' value='<?php echo $path;?>'/>
<br>
<strong>File Name : <?php echo $path;?></strong>
<br>
<label for='file_content' >File Content :</label>
</br>
<textarea name='file_content' id='file_content' width="100%" height="500px">
<?php
print htmlspecialchars($file_content );
?>
</textarea>
<br>
<input type="submit" name="Submit" value="Submit">
<br>
</form>



