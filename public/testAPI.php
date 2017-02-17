<?php
namespace testAPI
{
	class request
	{}
	
	class responce
	{
		public $status;
	}
	
	class getFileListRequest extends request
	{
		public $directory;
	}
	
	class getFileListResponce extends responce
	{
		public $fileNames = array();
	}
	
	const OK = 200;
	const BadRequest = 400;
	const Forbidden = 403;
	const NotFound = 404;
	const Conflict = 409;
	const InternalError = 500;
	
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
					$responce->status = OK;
				}
			}
			else
			{
				$responce->status = NotFound;
			}
		}
		else
		{
			$responce->status = BadRequest;
		}
		
		return $responce;
	}
	
	class getFileMetadataRequest extends request
	{
		public $fileName;
	}
	
	class getFileMetadataResponce extends responce
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
					$responce->status = InternalError;
				}
				else
				{
					$responce->status = OK;
				}
			}
			else
			{
				$responce->status = NotFound;
			}
		}
		else
		{
			$responce->status = BadRequest;
		}
		
		return $responce;
	}
	
	class getFileRequest extends request
	{
		public $fileName;
	}
	
	// Stub
	class getFileResponce extends responce
	{
	}
	
	function getFile(getFileRequest $request)
	{
		$responce = new getFileResponce();
		if (is_string($request->fileName))
		{
			if(is_readable($request->fileName))
			{
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($request->fileName).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($request->fileName));
				readfile($request->fileName);
				$responce->status = OK;
			}
			else
			{
				$responce->status = NotFound;
			}
		}
		else
		{
			$responce->status = BadRequest;
		}
		
		return $responce;
	}
	
	class uploadFileRequest extends request
	{
		public $fileName;
		public $rawData;
	}
	
	// Stub
	class uploadFileResponce extends responce
	{
	}
	
	function uploadFile(uploadFileRequest $request)
	{
		$responce = new uploadFileResponce();
		if (is_string($request->fileName))
		{
			if(!file_exists($request->fileName) && !file_exists($request->fileName.'.gz'))
			{
				$needEncoding = 0;
				if (substr($request->fileName, -3) != '.gz')
				{
					$request->fileName = $request->fileName.'.gz';
					$needEncoding = 1;
				}
					
				if ($fStream = @fopen($request->fileName, "wb"))
				{
					fwrite($fStream, $needEncoding ? gzencode($request->rawData) : $request->rawData);
					$responce->status = OK;
					fclose($fStream);
				}
				else
				{
					$responce->status = Forbidden;
				}		
			}
			else
			{
				$responce->status = Conflict;
			}
		}
		else
		{
			$responce->status = BadRequest;
		}
		
		return $responce;
	}
	
	function reloadFile(uploadFileRequest $request)
	{
		$responce = new uploadFileResponce();
		if (is_string($request->fileName))
		{
			if(file_exists($request->fileName) || file_exists($request->fileName.'.gz'))
			{
				$needEncoding = 0;
				if (substr($request->fileName, -3) != '.gz')
				{
					$request->fileName = $request->fileName.'.gz';
					$needEncoding = 1;
				}
				
				if ($fStream = @fopen($request->fileName, "wb"))
				{
					fwrite($fStream, $needEncoding ? gzencode($request->rawData) : $request->rawData);
					$responce->status = OK;
					fclose($fStream);
				}
				else
				{
					$responce->status = Forbidden;
				}		
			}
			else
			{
				// Probably we could just create new file and return OK here 
				$responce->status = NotFound;
			}
		}
		else
		{
			$responce->status = BadRequest;
		}
		
		return $responce;
	}
}
?>