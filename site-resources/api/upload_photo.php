<?php
if ($_FILES["fileToUpload"]["name"]) {
    $target_x = $_POST["cropx"];
    $target_y = $_POST["cropy"];
    $target_height = $_POST["cropheight"];
    $target_width = $_POST["cropwidth"];
    
    $stmt = $mysqli->prepare("SELECT uid, icon_ext FROM user WHERE uid = ?");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $stmt->bind_result($uid, $oldExt);
    if ($stmt->fetch()) {
      $stmt->close();
      
      $dir = $_SERVER["DOCUMENT_ROOT"]."/profile/";
      $imageFileType = pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
      $path = $dir . $uid . ".png";
      
      $restriction = array("jpg", "png", "jpeg");
      $sizeMax = 1 * 1024 * 1024; // 1 MB
            
      $checkFlag = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      $imgFlag = $imageFileType == $restriction[0] || $imageFileType == $restriction[1] || $imageFileType == $restriction[2];
      $sizeFlag = $_FILES["fileToUpload"]["size"] < $sizeMax;
              
      if ($checkFlag && $imgFlag && $sizeFlag) {
        $temp_image = imagecreatefromstring(file_get_contents($_FILES["fileToUpload"]["tmp_name"]));
        $ini_size = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        $ini_x_size = $ini_size[0];
        $ini_y_size = $ini_size[1];
        
        $width = $target_width * $ini_x_size;
        $height = $target_height * $ini_y_size;
        $x = $target_x * $ini_x_size;
        $y = $target_y * $ini_y_size;
        
        $crop_image_array = array('x' => $x, 'y' => $y, 'width' => $width, 'height' => $height);
        
        $thumb_im = imagecreatetruecolor($crop_image_array['width'], $crop_image_array['height']);
        
        imagealphablending( $thumb_im, false );
        imagesavealpha( $thumb_im, true );        
        
        imagecopy(
            $thumb_im,
            $temp_image,
            0,
            0,
            $crop_image_array['x'],
            $crop_image_array['y'],
            $crop_image_array['width'],
            $crop_image_array['height']
        );

        //$thumb_im = imagecrop($temp_image,$crop_image_array);
        
        imagepng($thumb_im,$path);
       
        $stmt = $mysqli-> prepare("UPDATE user SET icon_ext = ? WHERE uid = ?");
        $stmt->bind_param("ss", $imageFileType, $uid);
        $stmt->execute();
        $stmt->close();
        
        if ($imageFileType != $oldExt) {
          //unlink($dir . $uid . "." . $oldExt);
        }
      } else {
        if (!$imgFlag) {
          $failure[] = "extensionError";
        } 
        if (!$sizeFlag) {
          $failure[] = "sizeError";
        }
      } 
?>