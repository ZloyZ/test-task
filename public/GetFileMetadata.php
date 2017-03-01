<?php
namespace GetFileMetadata
{
    require_once 'Base.php';
    
    class getFileMetadataRequest extends \Base\request
    {
        public $fileName;
    }

    class getFileMetadataResponce extends \Base\responce
    {
        public $fileMetadata = array();
    }

    function getFileMetadata(getFileMetadataRequest $request)
    {
        $responce = new getFileMetadataResponce();
        if (is_string($request->fileName))
        {
            if(is_readable($request->fileName))
            {
                $responce->fileMetadata = stat($request->fileName);
                if (!$responce->fileMetadata)
                {
                    $responce->status = \Base\InternalError;
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
