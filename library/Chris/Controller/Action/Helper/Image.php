<?php

class Chris_Controller_Action_Helper_Image extends Zend_Controller_Action_Helper_Abstract
{

    /*
     * create thumbnail
     *
     * $Imagonator = $this->_helper->Imagonator;
     * $image = APPLICATION_PATH.'/../public/images/test.jpg';
     * $result = $Imagonator->thumbs($image);
     * Zend_Debug::dump($result);
     *
     */
    public function thumbs($image, $newWidth = '', $newHeight = '', $destFile = null, $jpgQuality = 95)
	{

        if (is_null($image) || !is_file($image)) {

            return false;

        }
		
		if (is_null($destFile)) $destFile = $image;

        // FIND IMAGE TYPE
        $imageData = $this->getimagedata($image);
        $type = strtolower($imageData['type']);

        switch ($type) {

            case "gif":     $srcImage = imagecreatefromgif($image);
                break;
            case "jpg":     $srcImage = imagecreatefromjpeg($image);
                break;
            case "jpeg":    $srcImage = imagecreatefromjpeg($image);
                break;
            case "png":     $srcImage = imagecreatefrompng($image);
                break;

        }
		
        if (is_null($newWidth) || empty($newWidth)) $newWidth = imagesx($srcImage);
        if (is_null($newHeight) || empty($newHeight)) $newHeight = imagesy($srcImage);

        // HEIGHT/WIDTH
        $srcWidth = imagesx($srcImage);
        $srcHeight = imagesy($srcImage);
        $ratioWidth = $srcWidth/$newWidth;
        $ratioHeight = $srcHeight/$newHeight;

        // IS BIGGER THEN MAXSIZE
        if (($ratioWidth > 1) || ($ratioHeight > 1)) {

            if ($ratioWidth < $ratioHeight) {

                $destWidth = $srcWidth/$ratioHeight;
                $destHeight = $newHeight;

            } else {

                $destWidth = $newWidth;
                $destHeight = $srcHeight/$ratioWidth;

            }

        } else {

            $destWidth = $srcWidth;
            $destHeight = $srcHeight;

        }

        // RESIZE
        $destImage = imagecreatetruecolor($destWidth, $destHeight);

        imagecopyresized($destImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);

        switch ($type) {

            case "gif":     imagegif($destImage, $destFile);
                break;
            case "jpg":     imagejpeg($destImage, $destFile, $jpgQuality);
                break;
            case "jpeg":    imagejpeg($destImage, $destFile, $jpgQuality);
                break;
            case "png":     imagepng($destImage, $destFile);
                break;

        }

        // FREE MEMORY
        imagedestroy($srcImage);
        imagedestroy($destImage);

        return true;

    }

    /*
     * resize file
     */
    public function resizeimage($image, $newWidth, $newHeight, $jpgQuality = 95)
	{

        $this->thumbs($image, $newWidth, $newHeight, $image);

    }

    /*
     * $Imagonator = $this->_helper->Imagonator;
     * $image = APPLICATION_PATH.'/../public/images/test-corrupt.jpg';
     * $result = $Imagonator->testimage($image);
     * Zend_Debug::dump($result);
     *
     */
    public function testimage($image)
	{

        $fileType = '';

        // is it JPEG
        $result = @imagecreatefromjpeg($image);

        if (!empty($result)) {

            $fileType = 'jpeg';

        }

        // is it GIF
        $result = @imagecreatefromgif($image);

        if (!empty($result)) {

            $fileType = 'gif';

        }

        // is it PNG
        $result = @imagecreatefrompng($image);

        if (!empty($result)) {

            $fileType = 'png';

        }

        if (!empty($fileType)) {

            return $fileType;

        } else {

            return false;

        }

    }

    /*
     * $Imagonator = $this->_helper->Imagonator;
     * $image = APPLICATION_PATH.'/../public/images/rss.gif';
     * $result = $Imagonator->getimagedata($image, true, true);
     * Zend_Debug::dump($result);
     *
     */
    public function getimagedata($image, $readDiscInfo = false, $readExifInfo = false)
	{

        if (is_null($image) || !is_file($image)) {

            return false;

        }

        // defines the keys
        $redefine_keys = array(
            'width',
            'height',
            'type',
            'html',
            'bits',
            'channels',
            'mime',
        );

        // assign usefull values for the third index
        $types = array(
            1 => 'GIF',
            2 => 'JPG',
            3 => 'PNG',
            4 => 'SWF',
            5 => 'PSD',
            6 => 'BMP',
            7 => 'TIFF(intel byte order)',
            8 => 'TIFF(motorola byte order)',
            9 => 'JPC',
            10 => 'JP2',
            11 => 'JPX',
            12 => 'JB2',
            13 => 'SWC',
            14 => 'IFF',
            15 => 'WBMP',
            16 => 'XBM'
        );

        $temp = array();
        $data = array();

        // Get the image info using getimagesize()
        // If $temp fails to populate, warn the user and return false
        if (!$temp = getimagesize($image)) {

            return false;

        }

        // Get the values returned by getimagesize()
        $temp = array_values($temp);

        // Make an array using values from $redefine_keys as keys and values from $temp as values
        foreach ($temp AS $k => $v) {

            $data[$redefine_keys[$k]] = $v;

        }

        // create css data, same as html attributes, but can be used in dynamic css file
        $data['css'] = 'width: '.$data['width'].'px;';
        $data['css'] .= "\r\n";
        $data['css'] .= 'height: '.$data['height'].'px;';
        $data['css'] .= "\r\n";

        // read exif data, if its a jpg or tiff file
        if ($readExifInfo) {

            $exifData = false;

            if ($data['type'] == 'JPG') {

                $exifData = @exif_read_data($image);

            }

            if ($exifData !== false) {

                $data['exif'] = $exifData;

            }
            
        }

        // get image on disc data
        if ($readDiscInfo) {

            $splFileInfo = new SplFileInfo($image);

            $size = $splFileInfo->getSize();
            $humanReadableSize = $this->bytesToSize($size);
            $owner = $splFileInfo->getOwner();
            $lastAccessTime = $splFileInfo->getATime();
            $lastModificationTime = $splFileInfo->getMTime();
            $creationTime = $splFileInfo->getCTime();
            $getPerms = $splFileInfo->getPerms();
            $getGroup = $splFileInfo->getGroup();
            $isWritable = $splFileInfo->isWritable();
            $isReadable = $splFileInfo->isReadable();
            $isExecutable = $splFileInfo->isExecutable();

            $data['size'] = $humanReadableSize;
            $data['owner'] = $owner;
            $data['lastAccessTime'] = $lastAccessTime;
            $data['lastModificationTime'] = $lastModificationTime;
            $data['creationTime'] = $creationTime;

            // Convert datetime
            if (Zend_Registry::isRegistered('Language')) {

                $language = Zend_Registry::get('Language');
                $zfdate = new Zend_Date();

                $zfdate->set($lastAccessTime, Zend_Date::TIMESTAMP);
                $data['lastAccessTimePlain'] = $zfdate->get(Zend_Date::DATE_FULL, $language);

                $zfdate->set($lastModificationTime, Zend_Date::TIMESTAMP);
                $data['lastModificationTimePlain'] = $zfdate->get(Zend_Date::DATE_FULL, $language);

                $zfdate->set($creationTime, Zend_Date::TIMESTAMP);
                $data['creationTimePlain'] = $zfdate->get(Zend_Date::DATE_FULL, $language);

            }

            $data['getPerms'] = $getPerms;
            $data['getGroup'] = $getGroup;
            $data['isWritable'] = $isWritable;
            $data['isReadable'] = $isReadable;
            $data['isExecutable'] = $isExecutable;

        }

        // Make 'type' usefull.
        $data['type'] = $types[$data['type']];

        return $data;

    }
	
	function bytesToSize($bytes, $precision = 2)
	{
	
		// human readable format - powers of 1024
		$unit = array('B','KB','MB','GB','TB','PB','EB');

		return @round(
			$bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision
		).' '.$unit[$i];
		
	}

}