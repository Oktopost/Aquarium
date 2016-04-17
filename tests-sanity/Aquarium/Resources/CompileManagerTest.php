<?php
namespace Aquarium\Resources;


use Aquarium\Resources\Logger\VoidLogger;
use Aquarium\Resources\Utils\Builder;
use Aquarium\Resources\Package\IBuilder;
use Aquarium\Resources\Compilers\GulpPackageManager;
use Aquarium\Resources\ConsumerStrategy\TestConsumer;
use Aquarium\Resources\DefinitionStrategy\ClassMethods;


class CompilerManagerTest extends \PHPUnit_Framework_TestCase 
{
	const SANITY_DIR = __DIR__ . '/Sanity';
	
	
	private function clean()
	{
		foreach (glob(self::SANITY_DIR . '/php/*.php') as $file) { unlink($file); }
		foreach (glob(self::SANITY_DIR . '/target/*') as $file) 
		{
			if ($file == '.keep') continue;
			
			foreach (glob($file . '/*') as $innerFile) 
			{
				unlink($innerFile);
			}
			
			rmdir($file);
		}
	}
	
	private function setupScriptTest(GulpPackageManager $gulp)
	{
		Config::instance()->Compiler			= $gulp;
		Config::instance()->DefinitionManager	= new ClassMethods(SanityTestHelper_Script::class);
		Config::instance()->Provider			= new Manager();
		Config::instance()->Consumer			= new TestConsumer();
	}
	
	private function setupStyleTest(GulpPackageManager $gulp)
	{
		Config::instance()->Compiler			= $gulp;
		Config::instance()->DefinitionManager	= new ClassMethods(SanityTestHelper_Style::class);
		Config::instance()->Provider			= new Manager();
		Config::instance()->Consumer			= new TestConsumer();
	}
	
	private function setupWithPackageLoader(GulpPackageManager $gulp, $class)
	{
		Config::instance()->Provider			= new Manager();
		Config::instance()->Compiler			= $gulp;
		
		Config::instance()->DefinitionManager	= new ClassMethods($class);
		Config::instance()->Consumer			= new TestConsumer();
	}
	
	private function setupDirectories()
	{
		Config::instance()->Directories->addSourceDir(self::SANITY_DIR . '/source');
		Config::instance()->Directories->PhpTargetDir		= self::SANITY_DIR . '/php';
		Config::instance()->Directories->ResourcesTargetDir	= self::SANITY_DIR . '/target';
	}
	
	
	public function setUp()
	{
		$this->clean();
		Builder::setTestMode(false);
		Config::instance()->Log = new VoidLogger();
	}
	
	public function tearDown()
	{
		$this->clean();
	}
	
	
	public function test_script_minify()
	{
		$gulpCompiler = new GulpPackageManager();
		$gulpCompiler->setup()
			->script()
			->jsmin();
		
		$this->setupScriptTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->Provider->package('js/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/js_a/a.js');
		$this->assertFileExists(self::SANITY_DIR . '/target/js_a/b.js');
		
		$this->assertFileNotEquals(self::SANITY_DIR . '/source/a.js', self::SANITY_DIR . '/target/js_a/a.js');
		$this->assertFileNotEquals(self::SANITY_DIR . '/source/b.js', self::SANITY_DIR . '/target/js_a/b.js');
	}
	
	public function test_script_concatenate()
	{
		$gulpCompiler = new GulpPackageManager();
		$gulpCompiler->setup()
			->script()
			->concatenate();
		
		$this->setupScriptTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->Provider->package('js/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/js_a/a.js');
		$this->assertEquals(
			file_get_contents(self::SANITY_DIR . '/source/a.js') . "\n" . file_get_contents(self::SANITY_DIR . '/source/b.js'),
			file_get_contents(self::SANITY_DIR . '/target/js_a/a.js'));
	}
	
	public function test_script_concatenate_and_minify()
	{
		$gulpCompiler = new GulpPackageManager();
		$gulpCompiler->setup()
			->script()
			->concatenate()
			->jsmin();
		
		$this->setupScriptTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->Provider->package('js/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/js_a/a.js');
		$this->assertNotEquals(
			file_get_contents(self::SANITY_DIR . '/source/a.js') . "\n" . file_get_contents(self::SANITY_DIR . '/source/b.js'),
			file_get_contents(self::SANITY_DIR . '/target/js_a/a.js'));
	}
	
	
	public function test_style_minify()
	{
		$gulpCompiler = new GulpPackageManager();
		$gulpCompiler->setup()
			->style()
			->cssmin();
		
		$this->setupStyleTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->Provider->package('css/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/css_a/a.css');
		$this->assertFileExists(self::SANITY_DIR . '/target/css_a/b.css');
		
		$this->assertFileNotEquals(self::SANITY_DIR . '/source/a.css', self::SANITY_DIR . '/target/css_a/a.css');
		$this->assertFileNotEquals(self::SANITY_DIR . '/source/b.css', self::SANITY_DIR . '/target/css_a/b.css');
	}
	
	public function test_style_concatenate()
	{
		$gulpCompiler = new GulpPackageManager();
		$gulpCompiler->setup()
			->style()
			->concatenate();
		
		$this->setupStyleTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->Provider->package('css/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/css_a/a.css');
		$this->assertEquals(
			file_get_contents(self::SANITY_DIR . '/source/a.css') . "\n" . file_get_contents(self::SANITY_DIR . '/source/b.css'),
			file_get_contents(self::SANITY_DIR . '/target/css_a/a.css'));
	}
	
	public function test_style_sass()
	{
		$gulpCompiler = new GulpPackageManager();
		$gulpCompiler->setup()
			->style()
			->sass();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Scss::class);
		$this->setupDirectories();
		
		Config::instance()->Provider->package('scss/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/a.css');
	}
	
	public function test_style_sass_and_css()
	{
		$gulpCompiler = new GulpPackageManager();
		$gulpCompiler->setup()
			->style()
			->sass()
			->cssmin();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Scss_And_Css::class);
		$this->setupDirectories();
		
		Config::instance()->Provider->package('scss/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/a.css');
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/b.css');
	}
	
	
	public function test_fullStuck_NoPhpBuild()
	{
		$gulpCompiler = new GulpPackageManager();
		$gulpCompiler->setup()
			->style()
			->sass()
			->cssmin();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Scss_And_Css::class);
		$this->setupDirectories();
		
		Config::instance()->Provider->package('scss/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/a.css');
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/b.css');
	}
	
	
	public function test_compiler_full_test()
	{
		$gulpCompiler = new GulpPackageManager();
		
		$gulpCompiler->setup()
			->style()
			->sass()
			->concatenate()
			->cssmin();
		
		$gulpCompiler->setup()
			->script()
			->concatenate()
			->jsmin();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Compiler_FullStuck::class);
		$this->setupDirectories();
		
		CompileManager::compile();
		
		$this->assertFileExists(self::SANITY_DIR . '/php/CompiledPackage_A.php');
		$this->assertFileExists(self::SANITY_DIR . '/php/CompiledPackage_A_B.php');
		$this->assertFileExists(self::SANITY_DIR . '/php/CompiledPackage_A_DEP_B.php');
	}
	
	public function test_compiler_using_timestamp()
	{
		$gulpCompiler = new GulpPackageManager();
		
		$gulpCompiler->setup()->addTimestamp();
		
		$gulpCompiler->setup()
			->style()
			->concatenate();
		
		$gulpCompiler->setup()
			->script()
			->concatenate();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Compiler_UsingTimestamp::class);
		$this->setupDirectories();
		
		CompileManager::compile();
		
		$this->assertFileExists(self::SANITY_DIR . '/php/CompiledPackage_A.php');
		
		$this->assertFileNotExists(self::SANITY_DIR . '/target/A/a.js');
		$this->assertFileNotExists(self::SANITY_DIR . '/target/A/a.css');
		
		$this->assertCount(1, glob(self::SANITY_DIR . '/target/A/a.*.js')); 
		$this->assertCount(1, glob(self::SANITY_DIR . '/target/A/a.*.css'));
	}
	
	public function test_compiler_filesWithOlderTimestampExists()
	{
		$gulpCompiler = new GulpPackageManager();
		$gulpCompiler->setup()->addTimestamp();
		$gulpCompiler->setup()->style()->concatenate();
		$gulpCompiler->setup()->script()->concatenate();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Compiler_UsingTimestamp::class);
		$this->setupDirectories();
		
		CompileManager::compile();
		
		$js = glob(self::SANITY_DIR . '/target/A/a.*.js');
		$css = glob(self::SANITY_DIR . '/target/A/a.*.css');
		$js = end($js);
		$css = end($css);
		rename($js, self::SANITY_DIR . '/target/A/a.t000001.js');
		rename($css, self::SANITY_DIR . '/target/A/a.t000001.css');
		
		CompileManager::compile();
		
		$this->assertFileExists(self::SANITY_DIR . '/target/A/a.t000001.js');
		$this->assertFileExists(self::SANITY_DIR . '/target/A/a.t000001.css');
		
		$this->assertCount(1, glob(self::SANITY_DIR . '/target/A/a.*.js')); 
		$this->assertCount(1, glob(self::SANITY_DIR . '/target/A/a.*.css'));
	}
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


class SanityTestHelper_Script
{
	public function Package_js_a(IBuilder $builder)
	{
		$builder
			->script('a.js')
			->script('b.js');
	}
}

class SanityTestHelper_Style
{
	public function Package_css_a(IBuilder $builder)
	{
		$builder
			->style('a.css')
			->style('b.css');
	}
}

class SanityTestHelper_Scss
{
	public function Package_scss_a(IBuilder $builder)
	{
		$builder->style('a.scss');
	}
}

class SanityTestHelper_Scss_And_Css
{
	public function Package_scss_a(IBuilder $builder)
	{
		$builder->style('b.css');
		$builder->style('a.scss');
	}
}

class SanityTestHelper_FullStuck
{
	public function Package_full_stuck(IBuilder $builder)
	{
		$builder
			->style('a.css')
			->style('b.css')
			->style('a.scss');
		
		$builder
			->style('a.js')
			->style('b.js');
	}
}

class SanityTestHelper_Compiler_FullStuck
{
	public function Package_A(IBuilder $builder)
	{
		$builder
			->style('a.css')
			->style('b.css');
		
		$builder
			->script('a.js');
	}
	
	public function Package_A_B(IBuilder $builder)
	{
		$builder
			->style('a.css')
			->style('b.css')
			->style('a.scss');
		
		$builder
			->script('a.js')
			->script('b.js');
	}
	
	public function Package_A_DEP_B(IBuilder $builder)
	{
		$builder
			->package('A/B');
		
		$builder
			->script('dir/in-dir.js');
	}
}

class SanityTestHelper_Compiler_UsingTimestamp
{
	public function Package_A(IBuilder $builder)
	{
		$builder->style('a.css');
		$builder->script('a.js');
	}
}