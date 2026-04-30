<?php

require_once dirname(__FILE__) . '/../constant.php';

class FileUploadUtil
{
    public function getExtension($file) {
        return strtolower(pathinfo(basename($file['name']), PATHINFO_EXTENSION));
    }

    public function uploadImage($file, $target_file, $extension)
	{
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if($check === false) {
            return 'Only jpg, jpeg, png image is supported!';
        }

        // Check file size
        $maxAllowedSize = 1 * 1024 * 1024;
        $file_size = $_FILES["file"]["size"];
        if ($file_size > $maxAllowedSize) {
            $curr_size = round($file_size / 1024 / 1024, 2);
            return "Sorry, select image below 1MB. Current Size " . $curr_size . "MB";
        }

        // Allow certain file formats
        if(!in_array($extension, ALLOWED_EXTENTION) ) {
            return "Sorry, only supported type: " . join(", ", ALLOWED_EXTENTION);
        }

        //upload
        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            return 'Sorry, there was an error uploading the image.';
        }

        return '';
	}

    public function uploadPdf($file, $target_file, $extension)
	{
        // Check file size
        $maxAllowedSize = 2 * 1024 * 1024;
        $file_size = $_FILES["file"]["size"];
        if ($file_size > $maxAllowedSize) {
            $curr_size = round($file_size / 1024 / 1024, 2);
            return "Sorry, select file below 2MB. Current Size " . $curr_size . "MB";
        }

        // Allow certain file formats
        if($extension !== 'pdf') {
            return "Sorry, only pdf is supported." ;
        }

        //upload
        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            return 'Sorry, there was an error uploading the image.';
        }

        return '';
	}
}