<?php
  include 'uploadToGoogle.php';
  $drive = new GoogleDrive();
  $file = $drive->upload('files/', 'photo.gif');
  if (!empty($file->id)) {
    printf("File Uploaded Successfully! [ID: %s\n", $file->id.']');
  }
?>