<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Image Compositor</title>
  <style>
    form {
      font-family: helvetica, arial, sans-serif;
    }
  </style>
</head>
<body>
 
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <h2>Select an image to upload:</h2>
    <br><input type="file" name="fileToUpload" id="fileToUpload">
    <br><input type="submit" value="Upload Image" name="submit">
  </form>
  
  <?php

    // Download the image files if we don't have them
    function get_file($file, $from) {
        if (!file_exists(__DIR__ . "/" . $file)) { file_put_contents(__DIR__ . "/" . $file, file_get_contents($from)); }
    }
    get_file("background-layer-1.png", "http://i.imgur.com/6pgf3WK.png");
    get_file("icon-layer-2.png", "http://i.imgur.com/0sJt52z.png");
    get_file("stars-layer-3.png", "http://i.imgur.com/1Tvlokk.png");

    $bgFile = __DIR__ . "/background-layer-1.png"; // 93 x 93
    $imageFile = __DIR__ . "/icon-layer-2.png"; // 76 x 76
    $watermarkFile = __DIR__ . "/stars-layer-3.png"; // 133 x 133

    // We want our final image to be 76x76 size
    $x = $y = 76;

    // dimensions of the final image
    $final_img = imagecreatetruecolor($x, $y);

    // Create our image resources from the files
    $image_1 = imagecreatefrompng($bgFile);
    $image_2 = imagecreatefrompng($imageFile);
    $image_3 = imagecreatefrompng($watermarkFile);

    // Enable blend mode and save full alpha channel
    imagealphablending($final_img, true);
    imagesavealpha($final_img, true);

    // Copy our image onto our $final_img
    imagecopy($final_img, $image_1, 0, 0, 0, 0, $x, $y);
    imagecopy($final_img, $image_2, 0, 0, 0, 0, $x, $y);
    imagecopy($final_img, $image_3, 0, 0, 0, 0, $x, $y);

    ob_start();
    imagepng($final_img);
    $watermarkedImg = ob_get_contents(); // Capture the output
    ob_end_clean(); // Clear the output buffer

    header('Content-Type: image/png');
    echo $watermarkedImg; // outputs: `http://i.imgur.com/f7UWKA8.png`
  ?>
  
</body>
</html>