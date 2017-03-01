<?php
namespace GetFile
{
    // Can be used for getting a single file and 
    // entire list of all files in selected directory - depends of path
    require_once 'Base.php';
    
    class getFileRequest extends \Base\request
    {
        public $path;
    }

    class getFileResponce extends \Base\responce
    {
        // Could be empty
        public $fileNames = array();
    }

    function getFile(getFileRequest $request)
    {
        $responce = new getFileResponce();
        if (is_string($request->path))
        {
            // When 
            if(is_dir($request->path))
            {
                $tryScan = scandir($request->path);
                $responce->fileNames = array_diff($tryScan, array('..', '.'));
                if (!$tryScan)
                {
                    $responce->status = InternalError;
                }
                else
                {
                    $responce->status = \Base\OK;
                }
            }
            else if(is_readable($request->path))
            {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($request->fileName).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($request->path));
                readfile($request->path);
                $responce->status = \Base\OK;
            }
            else
            {
                $responce->status = \Base\NotFound;
            }
        }
        else
        {
            $responce->status = \Base\BadRequest;
        }

        return $responce;
    }
}
?>
