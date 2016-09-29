<?php
namespace Aquarium\Resources\State;


class ResourceSetTest extends \PHPUnit_Framework_TestCase
{
	public function test_count_None_Return0()
	{
		$this->assertSame(0, (new ResourceSet())->count());
	}
	
	public function test_count_Exists_ReturnCount()
	{
		$set = new ResourceSet();
		
		$set->Files[] = new ResourceFile();
		$set->Files[] = new ResourceFile();
		$set->Files[] = new ResourceFile();
		
		$this->assertSame(3, $set->count());
	}
	
	
	public function test_getFileNames_None_ReturnEmptyArray()
	{
		$this->assertSame([], (new ResourceSet())->getFileNames());
	}
	
	public function test_getFileNames_Exists_ReturnNames()
	{
		$set = new ResourceSet();
		
		$file1 = new ResourceFile();
		$file2 = new ResourceFile();
		$file3 = new ResourceFile();
		
		$file1->Path = 'file/1';
		$file2->Path = 'file/2';
		$file3->Path = 'file/3';
		
		$set->Files = [$file1, $file2, $file3];
		
		$this->assertSame(['file/1', 'file/2', 'file/3'], $set->getFileNames());
	}
	
	
	public function test_getResourcesByFullPath_None_ReturnEmptyArray()
	{
		$this->assertSame([], (new ResourceSet())->getResourcesByFullPath());
	}
	
	public function test_getResourcesByFullPath_Exists_ReturnNames()
	{
		$set = new ResourceSet();
		
		$file1 = new ResourceFile();
		$file2 = new ResourceFile();
		$file3 = new ResourceFile();
		
		$file1->Path = 'file/1';
		$file2->Path = 'file/2';
		$file3->Path = 'file/3';
		
		$set->Files = [$file1, $file2, $file3];
		
		$this->assertSame(
			['file/1' => $file1, 'file/2' => $file2, 'file/3' => $file3], 
			$set->getResourcesByFullPath());
	}
}