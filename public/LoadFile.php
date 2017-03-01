<?php
namespace LoadFile
{
    require_once 'Base.php';
    
    class loadFileRequest extends \Base\request
    {
        public $fileName;
        public $base64Data;
    }

    // Stub
    class loadFileResponce extends \Base\responce
    {
    }
    
    function writeFile(loadFileRequest $request) 
    {
        $needEncoding = 0;
        if (substr($request->fileName, -3) != '.gz')
        {
            $request->fileName = $request->fileName.'.gz';
            $needEncoding = 1;
        }

        $data = base64_decode($request->base64Data);
        if (!$data)
        {
            return \Base\InternalError;
        }
        
        if ($fStream = @fopen($request->fileName, "wb"))
        {
            fwrite($fStream, $needEncoding ? gzencode($data) : $data);
            fclose($fStream);
            return \Base\OK;
        }
        else
        {
            return \Base\Forbidden;
        }
    }

    function uploadFile(loadFileRequest $request)
    {
        $responce = new loadFileResponce();
        if (is_string($request->fileName))
        {
            if(!file_exists($request->fileName) && !file_exists($request->fileName.'.gz'))
            {
                $responce->status = writeFile($request);
            }
            else
            {
                $responce->status = \Base\Conflict;
            }
        }
        else
        {
            $responce->status = \Base\BadRequest;
        }

        return $responce;
    }
    
    function updateFile(loadFileRequest $request)
    {
        $responce = new loadFileResponce();
        if (is_string($request->fileName))
        {
            if(file_exists($request->fileName) || file_exists($request->fileName.'.gz'))
            {
                $responce->status = writeFile($request);
            }
            else
            {
                // Probably we could just create new file and return OK here 
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
