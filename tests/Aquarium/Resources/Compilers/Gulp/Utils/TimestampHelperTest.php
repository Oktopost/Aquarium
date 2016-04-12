<?php
namespace Aquarium\Resources\Compilers\Gulp\Utils;


class TimestampHelperTest extends \PHPUnit_Framework_TestCase
{
	private $time 			= 1460457563;
	private $encodedTime 	= 'o5io9n';
	
	private $timestampFilesPath = __DIR__ . '/TimestampHelperFiles'; 
	
	
	public function test_generateTimestamp()
	{
		$this->assertEquals(
			TimestampHelper::TIMESTAMP_PREFIX . $this->encodedTime, 
			(new TimestampHelper())->generateTimestamp($this->time));
		
		$this->assertEquals(
			strlen(TimestampHelper::TIMESTAMP_PREFIX) + 6, 
			strlen((new TimestampHelper())->generateTimestamp()));
	}
	
	
	public function test_getTimestampFromFileName_FileHasNoFileType()
	{
		$file = '/asdasd.' . TimestampHelper::TIMESTAMP_PREFIX . $this->encodedTime;
		
		$this->assertEquals(
			$this->time,
			(new TimestampHelper())->getTimestampFromFileName($file)
		);
	}
	
	public function test_getTimestampFromFileName_FileHasFileType()
	{
		$file = '/asdasd.' . TimestampHelper::TIMESTAMP_PREFIX . $this->encodedTime . '.js';
		
		$this->assertEquals(
			$this->time,
			(new TimestampHelper())->getTimestampFromFileName($file)
		);
	}
	
	public function test_getTimestampFromFileName_FileInMoreThanOneDirectory()
	{
		$file = '/asdasd/asda.' . TimestampHelper::TIMESTAMP_PREFIX . $this->encodedTime;
		
		$this->assertEquals(
			$this->time,
			(new TimestampHelper())->getTimestampFromFileName($file)
		);
	}
	
	
	public function test_generateTimestampForFile_FileHasNoFileType() 
	{
		$file = '/asdasd';
		
		$this->assertEquals(
			'/asdasd.' . TimestampHelper::TIMESTAMP_PREFIX . $this->encodedTime,
			(new TimestampHelper())->generateTimestampForFile($file, $this->time)
		);
	}
	
	public function test_generateTimestampForFile_FileHasFileType() 
	{
		$file = '/asdasd.css';
		
		$this->assertEquals(
			'/asdasd.' . TimestampHelper::TIMESTAMP_PREFIX . $this->encodedTime . '.css',
			(new TimestampHelper())->generateTimestampForFile($file, $this->time)
		);
	}
	
	public function test_generateTimestampForFile_FileInMoreThanOneDirectory()
	{
		$file1 = '/asdasd/b/asda';
		$file2 = '/asdasd/b/asda.css';
		
		$this->assertEquals(
			'/asdasd/b/asda.' . TimestampHelper::TIMESTAMP_PREFIX . $this->encodedTime,
			(new TimestampHelper())->generateTimestampForFile($file1, $this->time)
		);
		
		$this->assertEquals(
			'/asdasd/b/asda.' . TimestampHelper::TIMESTAMP_PREFIX . $this->encodedTime . '.css',
			(new TimestampHelper())->generateTimestampForFile($file2, $this->time)
		);
	}
	
	
	public function test_findFileWithTimestamp_NoFileWithTimestamp()
	{
		$file = $this->timestampFilesPath . '/NoFileWithTimestamp/a.js';
		$this->assertFalse((new TimestampHelper())->findFileWithTimestamp($file));
	}
	
	public function test_findFileWithTimestamp_SingleFileWithTimestamp()
	{
		$file = $this->timestampFilesPath . '/SingleFileWithTimestamp/a.js';
		$prefix = TimestampHelper::TIMESTAMP_PREFIX;
		
		$this->assertEquals(
			$this->timestampFilesPath . "/SingleFileWithTimestamp/a.{$prefix}aaaaaa.js", 
			(new TimestampHelper())->findFileWithTimestamp($file)
		);
	}
	
	public function test_findFileWithTimestamp_NumberOfFilesExist_NewestSelected()
	{
		$file = $this->timestampFilesPath . '/NumberOfFilesExist/a.js';
		$prefix = TimestampHelper::TIMESTAMP_PREFIX;
		
		$this->assertEquals(
			$this->timestampFilesPath . "/NumberOfFilesExist/a.{$prefix}caaaaa.js", 
			(new TimestampHelper())->findFileWithTimestamp($file)
		);
	}
	
	public function test_findFileWithTimestamp_FileWithoutFileType()
	{
		$file = $this->timestampFilesPath . '/FileWithoutFileType/a';
		$prefix = TimestampHelper::TIMESTAMP_PREFIX;
		
		$this->assertEquals(
			$this->timestampFilesPath . "/FileWithoutFileType/a.{$prefix}aaaaaa", 
			(new TimestampHelper())->findFileWithTimestamp($file)
		);
	}
}