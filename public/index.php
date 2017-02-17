<?php
	require_once('testAPI.php');
	
	$request = new testAPI\getFileListRequest();
	$request->directory = 'folder';
	$responce = testAPI\getFileList($request);
	if ($responce->status != 200)
	{
		echo('error ' . $responce->status);
	}
	else
	{
		if (!empty($responce->fileNames))
		{
			print_r($responce->fileNames);
		}
		else 
		{
			echo 'directory is empty';
		}
	}
	echo '<br />';
	
	$request = new testAPI\getFileMetadataRequest();
	$request->fileName = 'folder/screen.PNG';
	$responce = testAPI\getFileMetadata($request);
	if ($responce->status != 200)
	{
		echo('error ' . $responce->status);
	}
	else
	{
		if (!empty($responce->fileMetadata))
		{
			print_r($responce->fileMetadata);
		}
		else 
		{
			echo 'no metadata';
		}
	}
	echo '<br />';
	
	//$request = new testAPI\getFileRequest();
	//$request->fileName = 'folder/screen.PNG';
	//$responce = testAPI\getFile($request);
	//if ($responce->status != 200)
	//{
	//	echo('error ' . $responce->status);
	//}
	//echo '<br />';
	
	$request = new testAPI\uploadFileRequest();
	$request->fileName = 'folder/SanPiN1.doc';
	$exampleName = 'folder/SanPiN.doc';
	$fStream = fopen($exampleName, 'r');
	$request->rawData = fread($fStream, filesize($exampleName));
	$responce = testAPI\uploadFile($request);
	if ($responce->status != 200)
	{
		echo('error ' . $responce->status);
	}
	else
	{
		echo('success');
	}
	echo '<br />';
	
	$request = new testAPI\uploadFileRequest();
	$request->fileName = 'readonly folder/screen5.PNG';
	
	$exampleName = 'folder/screen.PNG';
	$fStream = fopen($exampleName, 'r');
	$request->rawData = fread($fStream, filesize($exampleName));
	$responce = testAPI\uploadFile($request);
	if ($responce->status != 200)
	{
		echo('error ' . $responce->status);
	}
	else
	{
		echo('success');
	}
	echo '<br />';
?>