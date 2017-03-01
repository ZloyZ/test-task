<?php
namespace testAPI
{
    require_once('Base.php');
    require_once('GetFileList.php');
    require_once('GetFileMetadata.php');
    require_once('GetFile.php');
    require_once('LoadFile.php');	
	
    $method = $_SERVER['REQUEST_METHOD'];
    $request = explode('/', substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),1));
    $input = $_REQUEST;
    
    // Our assumptions:
    // $request[0] - here be "testapi" due to .htaccess properties
    // $request[1] possible variants: "files", "metadata"
    // $request[2] and further: path to file/folder; path for creating/updating file
    if (count($request) < 2)
    {
        $responce = new \Base\responce();
        $responce->status = \Base\NotFound;
    }  
    else if ($request[1] != "files" && $request[1] != "metadata")
    {
        $responce = new \Base\responce();
        $responce->status = \Base\NotFound;
    }
    else
    {
        $path = urldecode(implode('/', array_slice($request, 2)));
        switch ($method) {
            case 'GET':
                if ($request[1] == "metadata")
                {
                    $request = new \GetFileMetadata\getFileMetadataRequest();
                    $request->fileName = $path;
                    $responce = \GetFileMetadata\getFileMetadata($request);
                }
                else {
                    $request = new \GetFile\getFileRequest();
                    $request->path = $path;
                    $responce = \GetFile\getFile($request);
                }
                break;
            case 'PUT':
                //Update file
                $request = new \LoadFile\LoadFileRequest();
                $request->fileName = $path;
                $request->base64Data = file_get_contents('php://input');
                $responce = \LoadFile\updateFile($request);
                break;
            case 'POST':
                //Create file                
                $request = new \LoadFile\LoadFileRequest();
                $request->fileName = $path;
                $request->base64Data = file_get_contents('php://input');
                $responce = \LoadFile\uploadFile($request);
                break;
            case 'DELETE':
                //Not implemented
        }
    }
    
    http_response_code($responce->status);
    echo json_encode(get_object_vars($responce));
}
?>