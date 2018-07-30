<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["username"] == null || $_SESSION["username"] == '') {
    header('Location: index.php');
}

$dir = '..';
if ($_REQUEST["path"] != null && $_REQUEST["path"] != '') {
    $dir = $_REQUEST["path"];
} else {
    $dir = '..';
}

if (strpos($dir, '/..') !== false) {
    $dir = '..';
}
foreach (explode('/', $dir) as $path) {
    if ($path == 'admin') {
        $dir = '..';
    }
}

include "header.php";

$newPath   = '';
$pathArray = explode("/", $dir);
echo '</br><div>';
foreach ($pathArray as $path) {
    if ($newPath == '') {
        $newPath = $newPath . $path;
    } else {
        $newPath = $newPath . '/' . $path;
    }
    echo '<a href="home.php?path=' . $newPath . '">' . $path . '</a>&nbsp;/&nbsp;';
}
echo '</div></br>';

if ($_POST["Submit"] == "Create Folder") {
    $newFolderPath = $newPath . '/' . $_POST["folderName"];
    if (!file_exists($newFolderPath)) {
        mkdir($newFolderPath, 0755, true);
        echo '<div style="color: green">Folder ' . $newFolderPath . ' created</div>';
    } else {
        echo '<div style="color: red">' . $newFolderPath . " is already exists.</div>";
    }
}

if ($_POST["Submit"] == "Create File") {
    $newFilePath = $newPath . '/' . $_POST["fileName"];
    if (!file_exists($newFilePath)) {
        touch($newFilePath);
        echo '<div style="color: green">File ' . $newFilePath . ' created</div>';
    } else {
        echo '<div style="color: red">' . $newFilePath . " is already exists.</div>";
    }
}

if ($_POST["Submit"] == "Upload File") {
    $uploadedFile = $newPath . '/' . $_POST["uploadedFile"];
    if (isset($_FILES["uploadedFile"]) && $_FILES["uploadedFile"]["error"] == 0) {
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];
        
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize)
            die("Error: File size is larger than the allowed limit.");
        
        // Check whether file exists before uploading it
        if (file_exists("upload/" . $_FILES["photo"]["name"])) {
            echo '<div style="color: red">' . '/' . $_FILES["photo"]["name"] . " is already exists.</div>";
        } else {
            move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $newPath . '/' . $_FILES["uploadedFile"]["name"]);
            echo '<div style="color: green">File ' . $newPath . '/' . $_FILES["uploadedFile"]["name"] . ' uploaded</div>';
        }
    }
}
?>
<div class="container">

	<div class="row">
		<div class="col-sm-6 col-xs-12">
			<h3>PHP Admin Panel</h3>
		</div>
		<div class="col-sm-6 col-xs-12" style="float: right">
			<h3>
				<span>
				<?php
					echo $_SESSION["username"];
				?>
				</span>
				&nbsp;&nbsp;
				<span><a href="logout.php"> Logout </a></span>
			</h3>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-4 col-sm-12">
				
			<form id="createNewFolder" method="post">
				<input type="hidden" name="path" id="path" value="..">
				<div class="panel panel-default">
					<div class="panel-heading">
						Create New Folder
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label for="folderName">Folder Name*:</label>
							<input type="text" name="folderName" id="folderName" maxlength="50">
						</div>
					</div>
					<div class="panel-footer">
						<button type="submit" name="Submit" value="Create Folder" class="btn btn-default">Submit</button>
					</div>
				</div>
			</form>

		</div>    
		<div class="col-lg-4 col-sm-12">
				
			<form id="createNewFolder" method="post">
				<input type="hidden" name="path" id="path" value="..">
				<div class="panel panel-default">
					<div class="panel-heading">
						Create New Folder
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label for="fileName">File Name*:</label>
							<input type="text" name="fileName" id="fileName" maxlength="50">
						</div>
					</div>
					<div class="panel-footer">
						<button type="submit" name="Submit" value="Create File" class="btn btn-default">Submit</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-lg-4 col-sm-12">
				
			<form id="uploadFile" method="post" enctype="multipart/form-data">
				<input type="hidden" name="path" id="path" value="..">
				<div class="panel panel-default">
					<div class="panel-heading">
						Create New Folder
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label for="uploadedFile">File Name*:</label>
							<input type="file" name="uploadedFile" id="uploadedFile" maxlength="50">
						</div>
					</div>
					<div class="panel-footer">
						<button type="submit" name="Submit" value="Upload File" class="btn btn-default">Submit</button>
					</div>
				</div>
			</form>
		</div>        
	</div>

	
	<div class="row">
		<div class="col-xs-12">
			<table width="100%" bolder="1" class="table table-striped table-hover table-bordered dataTable no-footer">
			<tr><th>Folder / File Name</th><th>Action</th></tr>
			
			<?php
			$directoryList = scandir($dir);
			foreach ($directoryList as $file) {
				echo '<tr><td>';
				
				if (!is_dir($dir . '/' . $file)) {
					echo '<a href="home.php?path=' . $dir . '/' . $file . '"><i class="fa fa-file-text-o"></i>&nbsp;' . $file . '</a></td><td>';
				} else {
					echo '<a href="home.php?path=' . $dir . '/' . $file . '"><i class="fa fa-folder"></i>&nbsp;' . $file . '</a></td><td>';
				}
				if (!is_dir($dir . '/' . $file)) {
					echo '<a href="edit.php?path=' . $dir . '/' . $file . '"><i class="fa fa-pencil-square-o"></i>&nbsp;</a>';
					echo '<a href="delete.php?path=' . $dir . '/' . $file . '"><i class="fa fa-trash-o"></i>&nbsp;</a>';
				} else {
					echo '<a href="home.php?path=' . $dir . '/' . $file . '"><i class="fa fa-eye"></i>&nbsp;</a>';
				}
				echo '</td></tr>';
			}
			?>
			</table>
		</div>        
	</div>
</div>
