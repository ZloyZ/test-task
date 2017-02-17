<?php
use PHPUnit\Framework\TestCase;

final class getFilesTest extends TestCase
{
	public function testGetFilesFromEmptyDir()
	{
		$request = new testAPI\getFileListRequest();
		$request->directory = 'empty folder';
		$responce = testAPI\getFileList($request);
        $this->assertEquals(testAPI\OK, $responce->status);
        $this->assertEmpty($responce->fileNames); 
	}
	
	public function testGetFilesFromNotEmptyDir()
	{
		$request = new testAPI\getFileListRequest();
		$request->directory = 'folder';
		$responce = testAPI\getFileList($request);
        $this->assertEquals(testAPI\OK, $responce->status);
	}
	
	public function testGetFilesFromNotExistingDir()
	{
		$request = new testAPI\getFileListRequest();
		$request->directory = 'some imaginary folder';
		$responce = testAPI\getFileList($request);                         
        $this->assertEquals(testAPI\NotFound, $responce->status);
	}
	
	public function testGetFilesFromBadPath()
	{
		$request = new testAPI\getFileListRequest();
		$request->directory = 132.5;
		$responce = testAPI\getFileList($request);
        $this->assertEquals(testAPI\BadRequest, $responce->status);
	}
	
	public function testGetFileMetadata()
	{
		$request = new testAPI\getFileMetadataRequest();
		$request->fileName = 'folder/screen.PNG';
		$responce = testAPI\getFileMetadata($request);
        $this->assertEquals(testAPI\OK, $responce->status);
	}
	
	public function testGetNotExistingFileMetadata()
	{
		$request = new testAPI\getFileMetadataRequest();
		$request->fileName = 'some imaginary folder/screen.PNG';
		$responce = testAPI\getFileMetadata($request);                         
        $this->assertEquals(testAPI\NotFound, $responce->status);
	}
	
	public function testGetFileMetadataFromBadPath()
	{
		$request = new testAPI\getFileMetadataRequest();
		$request->fileName = 132.5;
		$responce = testAPI\getFileMetadata($request);
        $this->assertEquals(testAPI\BadRequest, $responce->status);
	}
	
	/* public function testGetFile()
	{
		$request = new testAPI\getFileRequest();
		$request->fileName = 'folder/screen.PNG';
		$responce = testAPI\getFile($request);
        $this->assertEquals(testAPI\OK, $responce->status);
	} */
	
	public function testGetNotExistingFile()
	{
		$request = new testAPI\getFileRequest();
		$request->fileName = 'some imaginary folder/screen.PNG';
		$responce = testAPI\getFile($request);                         
        $this->assertEquals(testAPI\NotFound, $responce->status);
	}
	
	public function testGetFileFromBadPath()
	{
		$request = new testAPI\getFileRequest();
		$request->fileName = 132.5;
		$responce = testAPI\getFile($request);
        $this->assertEquals(testAPI\BadRequest, $responce->status);
	}
	
	public function testUploadFile()
	{
		$request = new testAPI\uploadFileRequest();
		$request->fileName = 'folder/screen5.PNG';	
		$exampleName = "folder/screen.PNG";
		$fStream = fopen($exampleName, "r");
		$request->rawData = fread($fStream, filesize($exampleName));
		$responce = testAPI\uploadFile($request);
        $this->assertEquals(testAPI\OK, $responce->status);
	}
	
	public function testUploadFileAgain()
	{
		$request = new testAPI\uploadFileRequest();
		$request->fileName = 'folder/screen5.PNG';	
		$exampleName = "folder/screen.PNG";
		$fStream = fopen($exampleName, "r");
		$request->rawData = fread($fStream, filesize($exampleName));
		$responce = testAPI\uploadFile($request);
        $this->assertEquals(testAPI\Conflict, $responce->status);
	}
	
	public function testUploadFileToNotExistingPath()
	{
		$request = new testAPI\uploadFileRequest();                            
		$request->fileName = 'some imaginary folder/screen5.PNG';	
		$exampleName = "folder/screen.PNG";
		$fStream = fopen($exampleName, "r");
		$request->rawData = fread($fStream, filesize($exampleName));
		$responce = testAPI\uploadFile($request);
        $this->assertEquals(testAPI\Forbidden, $responce->status);
	}
	
	public function testUploadFileToReadOnlyFolder()
	{
		$request = new testAPI\uploadFileRequest();
		$request->fileName = 'readonly folder/screen5.PNG';	
		$exampleName = "folder/screen.PNG";
		$fStream = fopen($exampleName, "r");
		$request->rawData = fread($fStream, filesize($exampleName));
		$responce = testAPI\uploadFile($request);
        $this->assertEquals(testAPI\Forbidden, $responce->status);
	}
	
	public function testUploadFileToBadPath()
	{
		$request = new testAPI\uploadFileRequest();
		$request->fileName = 132.5;	
		$exampleName = "folder/screen.PNG";
		$fStream = fopen($exampleName, "r");
		$request->rawData = fread($fStream, filesize($exampleName));
		$responce = testAPI\uploadFile($request);
        $this->assertEquals(testAPI\BadRequest, $responce->status);
	}
	
	public function testReloadFile()
	{
		$request = new testAPI\uploadFileRequest();
		$request->fileName = 'folder/screen5.PNG';	
		$exampleName = "folder/screen.PNG";
		$fStream = fopen($exampleName, "r");
		$request->rawData = fread($fStream, filesize($exampleName));
		$responce = testAPI\reloadFile($request);
        $this->assertEquals(testAPI\OK, $responce->status);
        unlink($request->fileName);
	}
	
	public function testReloadFileToNotExistingPath()
	{                                                                    
		$request = new testAPI\uploadFileRequest();                            
		$request->fileName = 'some imaginary folder/screen5.PNG';	
		$exampleName = "folder/screen.PNG";
		$fStream = fopen($exampleName, "r");
		$request->rawData = fread($fStream, filesize($exampleName));
		$responce = testAPI\reloadFile($request);
        $this->assertEquals(testAPI\NotFound, $responce->status);
	}
	
	public function testReloadFileToReadOnlyFolder()
	{
		$request = new testAPI\uploadFileRequest();
		$request->fileName = 'readonly folder/screen.PNG';	
		$exampleName = "folder/screen.PNG";
		$fStream = fopen($exampleName, "r");
		$request->rawData = fread($fStream, filesize($exampleName));
		$responce = testAPI\reloadFile($request);
        $this->assertEquals(testAPI\Forbidden, $responce->status);
	}
	
	public function testReloadFileToBadPath()
	{
		$request = new testAPI\uploadFileRequest();
		$request->fileName = 132.5;	
		$exampleName = "folder/screen.PNG";
		$fStream = fopen($exampleName, "r");
		$request->rawData = fread($fStream, filesize($exampleName));
		$responce = testAPI\reloadFile($request);
        $this->assertEquals(testAPI\BadRequest, $responce->status);
	}
}

?>