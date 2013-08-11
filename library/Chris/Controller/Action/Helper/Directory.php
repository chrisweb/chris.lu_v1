<?php

class Chris_Controller_Action_Helper_Directory extends Zend_Controller_Action_Helper_Abstract
{

    /*
     * read directory
     *
     *  * usage:
     * $directory = APPLICATION_PATH;
     * $recursive = 0;
     *
     * $directorynator = $this->_helper->Directorynator;
     * $direcotryData = $directorynator->readDirectory($directory, $recursive);
    */
    function readDirectory($directory = '', $recursive = 0)
	{

        $directories = '';
        $files = '';
        $links = '';
        $dots = '';
        
        //Zend_Debug::dump($directory, '$directory');
        //Zend_Debug::dump($recursive, '$recursive');
        //exit;

        if ($this->directoryexists($directory)) {

            set_time_limit(0);

            if ($recursive) {

                $directoryIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

            } else {

                $directoryIterator = new DirectoryIterator($directory);

            }

            $directories = array();
            $files = array();
            $links = array();
            $dots = array();

            foreach ($directoryIterator as $file) {
			
				//Zend_Debug::dump($file);

                $name = $file->getFilename();
                $path = $file->getPath();
                $pathName = $file->getPathname();
                $size = $file->getSize();
                $humanReadableSize = $this->bytesToSize($size);
                $owner = $file->getOwner();
                $lastAccessTime = $file->getATime();
                $lastModificationTime = $file->getMTime();
                $creationTime = $file->getCTime();
                $getPerms = $file->getPerms();
                $getGroup = $file->getGroup();
                $isWritable = $file->isWritable();
                $isReadable = $file->isReadable();
                $isExecutable = $file->isExecutable();
				
				//Zend_Debug::dump($name);

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

                if ($file->isFile()) {

                    if ($file->isLink()) {

                        $links[] = $infos;

                    } else {

                        $files[] = $infos;

                    }

                    //Zend_Debug::dump($files);

                } elseif ($file->isDir()) {
				
					//Zend_Debug::dump($file);

                    if (!$recursive && $file->isDot()) {

                        $dots[] = $infos;

                    } else {

                        $directories[] = $infos;

                    }

                }

            }
            
            $data = array('files' => $files, 'directories' => $directories, 'links' => $links, 'dots' => $dots);

            return $data;

        } else {
            
            return false;
            
        }

    }
	
	function bytesToSize($bytes, $precision = 2)
	{
	
		// human readable format - powers of 1024
		$unit = array('B','KB','MB','GB','TB','PB','EB');

		return @round(
			$bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision
		).' '.$unit[$i];
		
	}

    /*
     * create new directory
    */
    public function createdirectory($path, $permissions = 0755)
	{

        return mkdir($path, $permissions);

    }

    /*
     * renames directory
    */
    public function renamedirectory($directoryName, $newDirectoryName)
	{

        if (is_dir($source)) {

            rename($source, $newName);

        }

    }


    /*
     * copy directory recursively
     */
    public function copydirectoryrecursive($sourceDirectory, $destinationDirectory)
	{

        // check if source exists
        if (file_exists($destinationDirectory) && is_dir($sourceDirectory)) {

            // Make destination directory
            if (!file_exists($destinationDirectory) || !is_dir($destinationDirectory)) {

                mkdir($destinationDirectory);

            }

            // retrieve directory instance
            $directoryInstance = dir($sourceDirectory);

            // loop through the source folder
            while (($entry = $directoryInstance->read()) !== false) {

                // skip dots
                if ($entry == '.' || $entry == '..') {

                    continue;

                }

                // subfolder path
                $filePath = $sourceDirectory.'/'.$entry;

                // check if it is a subfolder
                if (is_dir($filePath)) {

                    // is found a subdir call copy directory method again for subfolder
                    $this->copydirectoryrecursive($filePath, $destinationDirectory.'/'.$entry );

                    continue;

                }

                copy($filePath, $destinationDirectory.'/'.$entry);

            }

            // close direcoty instance
            $directoryInstance->close();

            return true;

        }else {

            return false;

        }

    }

    /**
     * delete directory recursively
     */
    public function deletedirectoryrecursive($directory)
	{

        if (is_readable($directory)) {

            // retrieve directory instance
            $handle = opendir($directory);

            // loop through the directory
            while(($entry = readdir($handle)) !== false) {

                // skip dots
                if ($entry == '.' || $entry == '..') {

                    continue;

                }

                // subfolder path
                $filePath = $directory.'/'.$entry;

                // if the new path is a directory
                if (is_dir($filePath)) {

                    // we call this function with the new path
                    $this->deletedirectoryrecursive($filePath);

                    continue;

                }

                // we remove the file
                unlink($filePath);

            }

            closedir($handle);

            return true;

        } else {

            return false;

        }

    }

    /*
     * count files in directory
     * 
     * $directory = APPLICATION_PATH;
     * $recursive = 0;
     * $directorynator = $this->_helper->Directorynator;
     * $recursive = true;
     * $counter = 0;
     * $includethumbs = false;
     * $direcotryData = $directorynator->numfiles($directory, $recursive, $counter, $includethumbs);
     * Zend_Debug::dump($directory);
     * Zend_Debug::dump($direcotryData);
     */
    public function numfiles($directory, $recursive = false, $counter = 0, $includethumbs = false)
	{

        if (is_dir($directory)) {

            $handle = opendir($directory);

            if ($handle) {

                while (($file = readdir($handle)) !== false) {

                    if ($file != "." && $file != ".." && $file != "desktop.ini") {

                        $filePath = $directory."/".$file;

                        if (!is_dir($filePath)) {

                            if ($includethumbs) {

                                $counter++;

                            } else {

                                if (preg_match('/thumb/', $file)) {

                                    continue;

                                } else {

                                    $counter++;

                                }

                            }

                        } else {

                            if ($recursive) {

                                $counter = $this->numfiles($filePath, $recursive, $counter, $includethumbs);

                            } else {

                                continue;

                            }

                        }

                    }

                }

                closedir($handle);

                return $counter;

            }

        } else {

            return false;

        }

    }

    /*
     * check if directory is empty
     */
    public function isemptyfolder($directory)
	{

        $counter = 0;

        $files = opendir($directory);

        while ($file = readdir($files)) {

            $counter++;

            if ($counter > 2) {

                return true; // dir contains something

            }

        }

        return false; // empty dir

    }
    
    public function directoryexists($directory)
	{
        
        if (is_null($directory) || !is_dir($directory)) {

            return false;

        } else {
            
            return true;
            
        }
        
    }

}