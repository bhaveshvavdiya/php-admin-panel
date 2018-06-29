<?php
  session_start();
  if(!isset($_SESSION["username"]) || $_SESSION["username"]==null || $_SESSION["username"]==''){
    header('Location: index.php'); 
  }
  
  $dir    = '..';
  if($_REQUEST["path"]!=null && $_REQUEST["path"]!=''){
    $dir    = $_REQUEST["path"];  
  } else {
    $dir    = '..';
  }
  
  if (strpos($dir, '/..') !== false) {
    $dir    = '..';
  } 
  foreach(explode('/',$dir) as $path){
    if($path == 'admin'){
      $dir    = '..';
    }
  }

  $newPath = '';
  $pathArray = explode("/",$dir);
  echo '</br><div>';
  foreach($pathArray as $path){
    if($newPath==''){
      $newPath = $newPath.$path;
    }else{
      $newPath = $newPath.'/'.$path;
    }
    echo '<a href="home.php?path='.$newPath.'">' . $path . '</a>&nbsp;/&nbsp;';
  }
  echo '</div></br>';
  
  if($_POST["Submit"] == "Create Folder"){
    $newFolderPath = $newPath.'/'.$_POST["folderName"];
    if (!file_exists($newFolderPath)) {
        mkdir($newFolderPath, 0644, true);
        echo '<div style="color: green">Folder '.$newFolderPath.' created</div>';
    }else{
      echo '<div style="color: red">'.$newFolderPath . " is already exists.</div>";
     }
  }
  
  if($_POST["Submit"] == "Create File"){
    $newFilePath = $newPath.'/'.$_POST["fileName"];
    if (!file_exists($newFilePath)) {
        touch($newFilePath);
        echo '<div style="color: green">File '.$newFilePath.' created</div>';
    } else{
      echo '<div style="color: red">'.$newFilePath . " is already exists.</div>";
     }
  }
  
  if($_POST["Submit"] == "Upload File"){
    $uploadedFile = $newPath.'/'.$_POST["uploadedFile"];
     if(isset($_FILES["uploadedFile"]) && $_FILES["uploadedFile"]["error"] == 0){
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];
    
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        // Check whether file exists before uploading it
        if(file_exists("upload/" . $_FILES["photo"]["name"])){
            echo '<div style="color: red">'.'/'.$_FILES["photo"]["name"] . " is already exists.</div>";
        } else{
            move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $newPath .'/' . $_FILES["uploadedFile"]["name"]);
            echo '<div style="color: green">File '.$newPath .'/' . $_FILES["uploadedFile"]["name"].' uploaded</div>';
        } 
     }
  }
?>
<div>
  <h3>PHP Admin Panel</h3>
  <div style="float: right; display: inline-block;">
    <span><?php echo $_SESSION["username"];?></span>&nbsp;&nbsp;
    <span><a href="lohout.php"> Logout </a></span>
  </div>
</div>
<form id="createNewFolder" method="post">
<fieldset>
<legend>Create New Folder</legend>
<input type="hidden" name="path" id="path" value="<?php echo $newPath;?>">
<label for="folderName">Folder Name*:</label>
<input type="text" name="folderName" id="folderName" maxlength="50">
<input type="submit" name="Submit" value="Create Folder">
</fieldset>
</form>

<form id="createNewFile" method="post">
<fieldset>
<legend>Create New File</legend>
<input type="hidden" name="path" id="path" value="<?php echo $newPath;?>">
<label for="fileName">File Name*:</label>
<input type="text" name="fileName" id="fileName" maxlength="50">
<input type="submit" name="Submit" value="Create File">
</fieldset>
</form>

<form id="uploadFile" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Create New File</legend>
<input type="hidden" name="path" id="path" value="<?php echo $newPath;?>">
<label for="uploadedFile">File Name*:</label>
<input type="file" name="uploadedFile" id="uploadedFile" maxlength="50">
<input type="submit" name="Submit" value="Upload File">
</fieldset>
</form>

<?php
  $directoryList = scandir($dir);
  echo '<table width="100%" bolder="1">';
  echo '<tr><th>Folder / File Name</th><th>Edit</th><th>Delete</th></tr>';
  foreach($directoryList as $file){
      echo '<tr><td><a href="home.php?path='.$dir.'/'.$file.'">' . $file . '</a></td>';
      echo '<td>';
      if(!is_dir($dir.'/'.$file)){
        echo '<a href="edit.php?path='.$dir.'/'.$file.'">Edit</a> </td><td>';
        echo '<a href="delete.php?path='.$dir.'/'.$file.'">Delete</a>';
      } else {
          echo '<a href="home.php?path='.$dir.'/'.$file.'">View</a></td><td>';        
      }
    echo '</td></tr>'';
  }
  echo '</table>';
?>
