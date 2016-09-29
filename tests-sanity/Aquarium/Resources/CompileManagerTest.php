<?php
namespace Aquarium\Resources;


use Aquarium\Resources\Log\LogStream\ConsoleLogStream;
use Aquarium\Resources\Log\LogStream\VoidStream;
use Aquarium\Resources\Utils\PackageBuilder;
use Aquarium\Resources\Package\IPackageBuilder;
use Aquarium\Resources\Compilers\GulpPackageCompiler;
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
		
		if (file_exists(self::SANITY_DIR . '/state/state.json'))
			unlink(self::SANITY_DIR . '/state/state.json');
	}
	
	private function basicSetup(GulpPackageCompiler $gulp)
	{
		Config::instance()->setCompiler($gulp);
		Config::instance()->setProvider(new Manager());
		Config::instance()->setConsumer(new TestConsumer());
	}
	
	
	private function setupScriptTest(GulpPackageCompiler $gulp)
	{
		$this->basicSetup($gulp);
		Config::instance()->setPackageDefinitionManager(new ClassMethods(SanityTestHelper_Script::class));
	}
	
	private function setupStyleTest(GulpPackageCompiler $gulp)
	{
		$this->basicSetup($gulp);
		Config::instance()->setPackageDefinitionManager(new ClassMethods(SanityTestHelper_Style::class));
	}
	
	private function setupWithPackageLoader(GulpPackageCompiler $gulp, $class)
	{
		$this->basicSetup($gulp);
		Config::instance()->setPackageDefinitionManager(new ClassMethods($class));
	}
	
	private function setupDirectories()
	{
		Config::instance()->directories()->addSourceDir(self::SANITY_DIR . '/source');
		Config::instance()->directories()->PhpTargetDir			= self::SANITY_DIR . '/php';
		Config::instance()->directories()->CompiledResourcesDir	= self::SANITY_DIR . '/target';
		Config::instance()->directories()->RootWWWDirectory		= self::SANITY_DIR;
		Config::instance()->directories()->StateFile		 	= self::SANITY_DIR . '/state/state.json';
	}
	
	
	public function setUp()
	{
		$this->clean();
		PackageBuilder::setTestMode(false);
		Config::instance()->logConfig()->setLogStream(new VoidStream());
	}
	
	public function tearDown()
	{
		$this->clean();
		
		if (file_exists(self::SANITY_DIR . '/state/state.json'))
			unlink(self::SANITY_DIR . '/state/state.json');
	}
	
	
	public function test_script_minify()
	{
		$gulpCompiler = new GulpPackageCompiler();
		$gulpCompiler->setup()
			->script()
			->jsmin();
		
		$this->setupScriptTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->provider()->package('js/n');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/js_n/a.js');
		$this->assertFileExists(self::SANITY_DIR . '/target/js_n/b.js');
		
		$this->assertFileNotEquals(self::SANITY_DIR . '/source/a.js', self::SANITY_DIR . '/target/js_n/a.js');
		$this->assertFileNotEquals(self::SANITY_DIR . '/source/b.js', self::SANITY_DIR . '/target/js_n/b.js');
	}
	
	public function test_script_concatenate()
	{
		$gulpCompiler = new GulpPackageCompiler();
		$gulpCompiler->setup()
			->script()
			->concatenate();
		
		$this->setupScriptTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->provider()->package('js/n');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/js_n/js_n.js');
		$this->assertEquals(
			file_get_contents(self::SANITY_DIR . '/source/a.js') . "\n" . file_get_contents(self::SANITY_DIR . '/source/b.js'),
			file_get_contents(self::SANITY_DIR . '/target/js_n/js_n.js'));
	}
	
	public function test_script_concatenate_and_minify()
	{
		$gulpCompiler = new GulpPackageCompiler();
		$gulpCompiler->setup()
			->script()
			->concatenate()
			->jsmin();
		
		$this->setupScriptTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->provider()->package('js/n');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/js_n/js_n.js');
		$this->assertNotEquals(
			file_get_contents(self::SANITY_DIR . '/source/a.js') . "\n" . file_get_contents(self::SANITY_DIR . '/source/b.js'),
			file_get_contents(self::SANITY_DIR . '/target/js_n/js_n.js'));
	}
	
	
	public function test_style_minify()
	{
		$gulpCompiler = new GulpPackageCompiler();
		$gulpCompiler->setup()
			->style()
			->cssmin();
		
		$this->setupStyleTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->provider()->package('css/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/css_a/a.css');
		$this->assertFileExists(self::SANITY_DIR . '/target/css_a/b.css');
		
		$this->assertFileNotEquals(self::SANITY_DIR . '/source/a.css', self::SANITY_DIR . '/target/css_a/a.css');
		$this->assertFileNotEquals(self::SANITY_DIR . '/source/b.css', self::SANITY_DIR . '/target/css_a/b.css');
	}
	
	public function test_style_concatenate()
	{
		$gulpCompiler = new GulpPackageCompiler();
		$gulpCompiler->setup()
			->style()
			->concatenate();
		
		$this->setupStyleTest($gulpCompiler);
		$this->setupDirectories();
		
		Config::instance()->provider()->package('css/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/css_a/css_a.css');
		$this->assertEquals(
			file_get_contents(self::SANITY_DIR . '/source/a.css') . "\n" . file_get_contents(self::SANITY_DIR . '/source/b.css'),
			file_get_contents(self::SANITY_DIR . '/target/css_a/css_a.css'));
	}
	
	public function test_style_sass()
	{
		$gulpCompiler = new GulpPackageCompiler();
		$gulpCompiler->setup()
			->style()
			->sass();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Scss::class);
		$this->setupDirectories();
		
		Config::instance()->provider()->package('scss/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/a.css');
	}
	
	public function test_style_sass_and_css()
	{
		$gulpCompiler = new GulpPackageCompiler();
		$gulpCompiler->setup()
			->style()
			->sass()
			->cssmin();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Scss_And_Css::class);
		$this->setupDirectories();
		
		Config::instance()->provider()->package('scss/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/a.css');
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/b.css');
	}
	
	
	public function test_fullStuck_NoPhpBuild()
	{
		$gulpCompiler = new GulpPackageCompiler();
		$gulpCompiler->setup()
			->style()
			->sass()
			->cssmin();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Scss_And_Css::class);
		$this->setupDirectories();
		
		Config::instance()->provider()->package('scss/a');
		
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/a.css');
		$this->assertFileExists(self::SANITY_DIR . '/target/scss_a/b.css');
	}
	
	
	public function test_compiler_full_test()
	{
		$gulpCompiler = new GulpPackageCompiler();
		
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
		$gulpCompiler = new GulpPackageCompiler();
		
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
		
		$this->assertFileNotExists(self::SANITY_DIR . '/target/A/A.js');
		$this->assertFileNotExists(self::SANITY_DIR . '/target/A/A.css');
		
		$this->assertCount(1, glob(self::SANITY_DIR . '/target/A/A.*.js'));
		$this->assertCount(1, glob(self::SANITY_DIR . '/target/A/A.*.css'));
	}
	
	public function test_compiler_filesWithOlderTimestampExists()
	{
		$gulpCompiler = new GulpPackageCompiler();
		$gulpCompiler->setup()->addTimestamp();
		$gulpCompiler->setup()->style()->concatenate();
		$gulpCompiler->setup()->script()->concatenate();
		
		$this->setupWithPackageLoader($gulpCompiler, SanityTestHelper_Compiler_UsingTimestamp::class);
		$this->setupDirectories();
		
		CompileManager::compile();
		
		$js = glob(self::SANITY_DIR . '/target/A/A.*.js');
		$css = glob(self::SANITY_DIR . '/target/A/A.*.css');
		$js = end($js);
		$css = end($css);
		rename($js, self::SANITY_DIR . '/target/A/A.t000001.js');
		rename($css, self::SANITY_DIR . '/target/A/A.t000001.css');
		
		CompileManager::compile();
		
		$this->assertFileExists(self::SANITY_DIR . '/target/A/A.t000001.js');
		$this->assertFileExists(self::SANITY_DIR . '/target/A/A.t000001.css');
		
		$this->assertCount(1, glob(self::SANITY_DIR . '/target/A/A.*.js')); 
		$this->assertCount(1, glob(self::SANITY_DIR . '/target/A/A.*.css'));
	}
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


class SanityTestHelper_Script
{
	public function Package_js_n(IPackageBuilder $builder)
	{
		$builder
			->script('a.js')
			->script('b.js');
	}
}

class SanityTestHelper_Style
{
	public function Package_css_a(IPackageBuilder $builder)
	{
		$builder
			->style('a.css')
			->style('b.css');
	}
}

class SanityTestHelper_Scss
{
	public function Package_scss_a(IPackageBuilder $builder)
	{
		$builder->style('a.scss');
	}
}

class SanityTestHelper_Scss_And_Css
{
	public function Package_scss_a(IPackageBuilder $builder)
	{
		$builder->style('b.css');
		$builder->style('a.scss');
	}
}

class SanityTestHelper_FullStuck
{
	public function Package_full_stuck(IPackageBuilder $builder)
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
	public function Package_A(IPackageBuilder $builder)
	{
		$builder
			->style('a.css')
			->style('b.css');
		
		$builder
			->script('a.js');
	}
	
	public function Package_A_B(IPackageBuilder $builder)
	{
		$builder
			->style('a.css')
			->style('b.css')
			->style('a.scss');
		
		$builder
			->script('a.js')
			->script('b.js');
	}
	
	public function Package_A_DEP_B(IPackageBuilder $builder)
	{
		$builder
			->package('A/B');
		
		$builder
			->script('dir/in-dir.js');
	}
}

class SanityTestHelper_Compiler_UsingTimestamp
{
	public function Package_A(IPackageBuilder $builder)
	{
		$builder->style('a.css');
		$builder->script('a.js');
	}
}