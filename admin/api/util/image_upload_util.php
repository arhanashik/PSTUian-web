<?php
require_once dirname(__FILE__) . '/../constant.php';

class ImageUploadUtil
{
    public function getExtension($image) {
        return strtolower(pathinfo(basename($image['name']), PATHINFO_EXTENSION));
    }

    public function uploadSlider($image, $target_file_name, $extension)
	{
		$fileName = $image['name'];
        $target_file = ROOT_DIR . '/' . SLIDER_PATH . $target_file_name;

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            return 'Only jpg, jpeg, png image is supported!';
        }

        // Check file size
        $maxAllowedSize = 1 * 1024 * 1024;
        if ($_FILES["image"]["size"] > $maxAllowedSize) {
            return "Sorry, select image below 1MB.";
        }

        // Allow certain file formats
        if(!in_array($extension, ALLOWED_EXTENTION) ) {
            return "Sorry, only supported type: " . join(", ", ALLOWED_EXTENTION);
        }

        //upload
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            return 'Sorry, there was an error uploading the image.';
        }

        return '';
	}

    public function uploadFacultyIcon($image, $target_file_name, $extension)
	{
		$fileName = $image['name'];
        $target_file = ROOT_DIR . '/' . FACULTY_ICON_PATH . $target_file_name;

        // Check if image file is a actual image or fake image
        $check = getimagesize($image["tmp_name"]);
        if($check === false) {
            return 'Only jpg, jpeg, png image is supported!';
        }

        // Check file size
        $maxAllowedSize = 0.5 * 1024 * 1024;
        if ($image["size"] > $maxAllowedSize) {
            return "Sorry, select image below 500KB.";
        }

        // Allow certain file formats
        if(!in_array($extension, ALLOWED_EXTENTION) ) {
            return "Sorry, only supported type: " . join(", ", ALLOWED_EXTENTION);
        }

        //upload
        if (!move_uploaded_file($image["tmp_name"], $target_file)) {
            return 'Sorry, there was an error uploading the image.';
        }

        return '';
	}
}