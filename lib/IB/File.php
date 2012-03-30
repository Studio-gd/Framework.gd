<?php
Class IB_File
{
    static $s = false;
    static function getInstance(){if(!self::$s){self::$s=new IB_File();}return self::$s;}
    
    function upload($file, $filename, $path)
    {
        if(!empty($file))
        {
            $fullpath = $path.$filename;
            
            if(move_uploaded_file($file['tmp_name'],$fullpath))
            {
                chmod($fullpath,0644);
                
                return true;
            }
        }
        return false;
    }
    
    /**
     * Read File
     *
     * Opens the file specfied in the path and returns it as a string.
     *
     * @access    public
     * @param    string    path to file
     * @return    string
     */
    function readFile($file)
    {
        if(!file_exists($file)) return false;
    
        if(function_exists('file_get_contents'))
        {
            return file_get_contents($file);
        }

        if(!$fp = fopen($file, FOPEN_READ)) return false;
        
        flock($fp, LOCK_SH);
    
        $data = '';
        if(filesize($file) > 0)
        {
            $data = fread($fp, filesize($file));
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return $data;
    }
    
    /**
     * Write File
     *
     * Writes data to the file specified in the path.
     * Creates a new file if non-existent.
     *
     * @access    public
     * @param    string    path to file
     * @param    string    file data
     * @return    bool
     */
    function writeFile($path, $data, $mode = 'wb')
    {
        if(!$fp = fopen($path, $mode)) return false;
        
        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }
    
    function appendToFile($path, $data)
    {
        file_put_contents($path, $data, FILE_APPEND);
    }
    
    function prependToFile($path, $data)
    {
        $this->writeFile($path, $data.$this->readFile($path));
    }
    
    /**
     * Delete Files
     *
     * Deletes all files contained in the supplied directory path.
     * Files must be writable or owned by the system in order to be deleted.
     * If the second parameter is set to true, any directories contained
     * within the supplied base directory will be nuked as well.
     *
     * @access    public
     * @param    string    path to file
     * @param    bool    whether to delete any directories found in the path
     * @return    bool
     */
    function deleteFiles($path, $del_dir = false, $level = 0)
    {    
        $path = rtrim($path, '/');
            
        if(!$current_dir = @opendir($path)) return;
    
        while($filename = readdir($current_dir))
        {
            if($filename != "." && $filename != "..")
            {
                if(is_dir($path.'/'.$filename))
                {
                    // Ignore empty folders
                    if(substr($filename, 0, 1) != '.')
                    {
                        $this->deleteFiles($path.'/'.$filename, $del_dir, $level + 1);
                    }
                }
                else
                {
                    unlink($path.'/'.$filename);
                }
            }
        }
        closedir($current_dir);
    
        if($del_dir && $level > 0)
        {
            rmdir($path);
        }
    }
    
    /**
     * Get Filenames
     *
     * Reads the specified directory and builds an array containing the filenames.  
     * Any sub-folders contained within the specified path are read as well.
     *
     * @access    public
     * @param    string    path to source
     * @param    bool    whether to include the path as part of the filename
     * @param    bool    internal variable to determine recursion status - do not use in calls
     * @return    array
     */
    function getFilenames($source_dir, $include_path = false, $_recursion = false)
    {
        static $_filedata = array();
                
        if($fp = @opendir($source_dir))
        {
            // reset the array and make sure $source_dir has a trailing slash on the initial call
            if (!$_recursion)
            {
                $_filedata = array();
                $source_dir = rtrim(realpath($source_dir), '/').'/';
            }
            
            while($file = readdir($fp))
            {
                if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0)
                {
                     $this->getFilenames($source_dir.$file.'/', $include_path, true);
                }
                elseif (strncmp($file, '.', 1) !== 0)
                {
                    $_filedata[] = $include_path == true ? $source_dir.$file : $file;
                }
            }
            return $_filedata;
        }
        return false;
    }
    
    
    function forceDownload($filename = false, $data = false)
    {
        if(!$filename || !$data) return false;

        // Try to determine if the filename includes a file extension.
        // We need it in order to set the MIME type
        if(!strpos($filename, '.')) return false;
    
        $extension = getExtension($filename);
        
        if(!$mime = $this->getMime($extension))
        {
            $mime = 'application/octet-stream';
        }
        
        // Generate the server headers
        header('Content-Type: "'.$mime.'"');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header("Content-Transfer-Encoding: binary");
        
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
        {
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        else
        {
            header('Pragma: no-cache');
        }
        header("Content-Length: ".strlen($data));
        
        exit($data);
    }
    
    
    function getMime($extension)
    {
        $mimes = array( 'hqx'   =>    'application/mac-binhex40',
                        'cpt'   =>    'application/mac-compactpro',
                        'csv'   =>    array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
                        'bin'   =>    'application/macbinary',
                        'dms'   =>    'application/octet-stream',
                        'lha'   =>    'application/octet-stream',
                        'lzh'   =>    'application/octet-stream',
                        'exe'   =>    'application/octet-stream',
                        'class' =>    'application/octet-stream',
                        'psd'   =>    'application/x-photoshop',
                        'so'    =>    'application/octet-stream',
                        'sea'   =>    'application/octet-stream',
                        'dll'   =>    'application/octet-stream',
                        'oda'   =>    'application/oda',
                        'pdf'   =>    array('application/pdf', 'application/x-download'),
                        'ai'    =>    'application/postscript',
                        'eps'   =>    'application/postscript',
                        'ps'    =>    'application/postscript',
                        'smi'   =>    'application/smil',
                        'smil'  =>    'application/smil',
                        'mif'   =>    'application/vnd.mif',
                        'xls'   =>    array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
                        'ppt'   =>    array('application/powerpoint', 'application/vnd.ms-powerpoint'),
                        'wbxml' =>    'application/wbxml',
                        'wmlc'  =>    'application/wmlc',
                        'dcr'   =>    'application/x-director',
                        'dir'   =>    'application/x-director',
                        'dxr'   =>    'application/x-director',
                        'dvi'   =>    'application/x-dvi',
                        'gtar'  =>    'application/x-gtar',
                        'gz'    =>    'application/x-gzip',
                        'php'   =>    'application/x-httpd-php',
                        'php4'  =>    'application/x-httpd-php',
                        'php3'  =>    'application/x-httpd-php',
                        'phtml' =>    'application/x-httpd-php',
                        'phps'  =>    'application/x-httpd-php-source',
                        'js'    =>    'application/x-javascript',
                        'swf'   =>    'application/x-shockwave-flash',
                        'sit'   =>    'application/x-stuffit',
                        'tar'   =>    'application/x-tar',
                        'tgz'   =>    'application/x-tar',
                        'xhtml' =>    'application/xhtml+xml',
                        'xht'   =>    'application/xhtml+xml',
                        'zip'   =>    array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
                        'mid'   =>    'audio/midi',
                        'midi'  =>    'audio/midi',
                        'mpga'  =>    'audio/mpeg',
                        'mp2'   =>    'audio/mpeg',
                        'mp3'   =>    array('audio/mpeg', 'audio/mpg'),
                        'aif'   =>    'audio/x-aiff',
                        'aiff'  =>    'audio/x-aiff',
                        'aifc'  =>    'audio/x-aiff',
                        'ram'   =>    'audio/x-pn-realaudio',
                        'rm'    =>    'audio/x-pn-realaudio',
                        'rpm'   =>    'audio/x-pn-realaudio-plugin',
                        'ra'    =>    'audio/x-realaudio',
                        'rv'    =>    'video/vnd.rn-realvideo',
                        'wav'   =>    'audio/x-wav',
                        'bmp'   =>    'image/bmp',
                        'gif'   =>    'image/gif',
                        'jpeg'  =>    array('image/jpeg', 'image/pjpeg'),
                        'jpg'   =>    array('image/jpeg', 'image/pjpeg'),
                        'jpe'   =>    array('image/jpeg', 'image/pjpeg'),
                        'png'   =>    array('image/png',  'image/x-png'),
                        'tiff'  =>    'image/tiff',
                        'tif'   =>    'image/tiff',
                        'css'   =>    'text/css',
                        'html'  =>    'text/html',
                        'htm'   =>    'text/html',
                        'shtml' =>    'text/html',
                        'txt'   =>    'text/plain',
                        'text'  =>    'text/plain',
                        'log'   =>    array('text/plain', 'text/x-log'),
                        'rtx'   =>    'text/richtext',
                        'rtf'   =>    'text/rtf',
                        'xml'   =>    'text/xml',
                        'xsl'   =>    'text/xml',
                        'mpeg'  =>    'video/mpeg',
                        'mpg'   =>    'video/mpeg',
                        'mpe'   =>    'video/mpeg',
                        'qt'    =>    'video/quicktime',
                        'mov'   =>    'video/quicktime',
                        'avi'   =>    'video/x-msvideo',
                        'movie' =>    'video/x-sgi-movie',
                        'doc'   =>    'application/msword',
                        'docx'  =>    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'xlsx'  =>    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'word'  =>    array('application/msword', 'application/octet-stream'),
                        'xl'    =>    'application/excel',
                        'eml'   =>    'message/rfc822'

                    );
                    
        return is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
    }
    
}