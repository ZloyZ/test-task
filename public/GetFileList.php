<?php
namespace GetFileList
{
    require_once 'Base.php';
    
    class getFileListRequest extends \Base\request
    {
        public $path;
    }

    class getFileListResponce extends \Base\responce
    {
        public $fileNames = array();
    }	

    function getFileList(getFileListRequest $request)
    {
        $responce = new getFileListResponce();
        if (is_string($request->directory))
        {
            if(is_dir($request->directory))
            {
                $tryScan = scandir($request->directory);
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