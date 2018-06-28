<?php
  $path = $_REQUEST["path"];

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

  if($isValidPath){
    if (file_exists($path)) {
        if(unlink($path)){
          echo '<div style="color: green">'.$newFilePath . " deleted successfully.</div>";
        } else {
          echo '<div style="color: red">Not abale to delete '.$path . ".</div>";
        }      
    } else {
      echo '<div style="color: red">'.$path . " is not exists.</div>";
    }
  } else {
     echo '<div style="color: red">Error reading path '.$path.' </div>';
  }
?>
