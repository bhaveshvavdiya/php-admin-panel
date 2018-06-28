<?php
  $path = $_REQUEST["path"];
  if (file_exists($path)) {
      if(unlink($path)){
        echo '<div style="color: green">'.$newFilePath . " deleted successfully.</div>";
      } else {
        echo '<div style="color: red">Not abale to delete '.$path . ".</div>";
      }      
  } else {
    echo '<div style="color: red">'.$path . " is not exists.</div>";
  }
?>
