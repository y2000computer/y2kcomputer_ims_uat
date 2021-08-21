<?php
function resizeImg($file,$name,$folder,$width,$clean=false){

		$img = new upload($file);
		
		// Only proceed if the file has been uploaded
		if($img->uploaded) {
			$img->file_safe_name 		= false;
		    // Set the new filename of the uploaded image
		    $img->file_new_name_body   	= $name;
		    // Make sure the image is resized
		    $img->image_resize         	= true;
		    $img->file_overwrite		= true;
		    $img->image_convert			='jpeg';

			 $img->image_x              = $width;
			 $img->image_ratio_y		= true;
			 
		    // Process the image resize and save the uploaded file to the directory
		    $img->process($folder);
		    // Proceed if image processing completed sucessfully
		    if ($clean)$img->clean();
		    if($img->processed) {
		    	return true;
		    }else{
		        // Write the error to the screen
		        echo 'error : ' . $img->error;
		    }
		}
	}



?>