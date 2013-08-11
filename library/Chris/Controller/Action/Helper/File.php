<?php

class Chris_Controller_Action_Helper_File extends Zend_Controller_Action_Helper_Abstract
{

    /*
     * retrieve informations
     */
    public function getFileData($file)
	{

        if (!$this->fileExists($file)) {

            return false;

        }

        $splFileInfo = new SplFileInfo($file);

        $name = $splFileInfo->getFilename();
        $path = $splFileInfo->getPath();
        $pathName = $splFileInfo->getPathname();
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

        $infos = array(
                'name' => $name,
                'path' => $path,
                'pathName' => $pathName,
                'size' => $humanReadableSize,
                'owner' => $owner,
                'lastAccessTime' => $lastAccessTime,
                'lastModificationTime' => $lastModificationTime,
                'creationTime' => $creationTime,
                'getPerms' => $getPerms,
                'getGroup' => $getGroup,
                'isWritable' => $isWritable,
                'isReadable' => $isReadable,
                'isExecutable' => $isExecutable
            );

        return $infos;

    }
	
	function bytesToSize($bytes, $precision = 2)
	{
	
		// human readable format - powers of 1024
		$unit = array('B','KB','MB','GB','TB','PB','EB');

		return @round(
			$bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision
		).' '.$unit[$i];
		
	}
    
    /**
     * does file exist 
     */
    public function fileExists($file)
	{
        
        if (is_null($file) || !is_file($file)) {

            return false;

        } else {
            
            return true;
            
        }
        
    }

    /*
     * copy file
     */
    public function copyFile($source, $target)
	{

        if (file_exists($source)) {

            copy($source, $target);

            return true;

        } else {

            return false;

        }

    }

    /*
     * move file
     */
    public function moveFile($source, $target)
	{

        if (file_exists($source)) {

            rename($source, $target);

            return true;

        } else {

            return false;

        }

    }

    /*
     * rename file
     */
    public function renameFile($source, $newName)
	{

        if (file_exists($source)) {

            rename($source, $newName);

            return true;

        } else {

            return false;

        }

    }

    /*
     * delete file
     */
    public function deleteFile($path)
	{

        if (file_exists($source)) {

            unlink($path);
            
            return true;

        } else {

            return false;

        }

    }

    /*
     * change permissions
     */
    public function chmodFile($path, $mode)
	{

        if (file_exists($source)) {

            chmod($path, $mode);

            return true;

        } else {

            return false;

        }

    }

    /*
     * filename cleaner
     */
    public function cleanFilename($fileName)
	{

        $fileName = str_replace('../', '', $fileName);
        $fileName = str_replace('./', '', $fileName);
        $fileName = str_replace('.', '_', $fileName);
        $fileName = str_replace(' ', '_', $fileName);

        if (function_exists('iconv')) {

            $fileName = @iconv('UTF-8', 'ASCII//TRANSLIT', $fileName);

        } else {

            $cleaner = array();
            $cleaner[] = array('expression'=>"/[àáäãâª]/",'replace'=>"a");
            $cleaner[] = array('expression'=>"/[èéêë]/",'replace'=>"e");
            $cleaner[] = array('expression'=>"/[ìíîï]/",'replace'=>"i");
            $cleaner[] = array('expression'=>"/[òóõôö]/",'replace'=>"o");
            $cleaner[] = array('expression'=>"/[ùúûü]/",'replace'=>"u");
            $cleaner[] = array('expression'=>"/[ñ]/",'replace'=>"n");
            $cleaner[] = array('expression'=>"/[ç]/",'replace'=>"c");

            foreach($cleaner as $cv) {

                $fileName = preg_replace($cv["expression"], $cv["replace"], $fileName);

            }

        }

        $fileName = preg_replace("/[^a-zA-Z0-9. -]/", "", $fileName);
        $fileName = strtolower($fileName);

        return $fileName;

    }

    public function getExtensionByName($uploadedFileName)
	{

        $dotPosition = strrpos($uploadedFileName, '.');
        $length = strlen($uploadedFileName) - $dotPosition;
        $extension = strtolower(substr($uploadedFileName, $dotPosition, $length));

        return $extension;

    }
    
    public function getExtensionByPath($uploadedFilePath)
	{

        $extension = pathinfo($uploadedFilePath, PATHINFO_EXTENSION);

        return $extension;

    }

}