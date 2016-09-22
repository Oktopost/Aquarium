<?php
namespace Aquarium\Resources\Compilation;


use Skeleton\Type;
use Aquarium\Resources\Config;


/** @var \Skeleton\Skeleton $skeleton */
$skeleton = Config::skeleton();


use Aquarium\Resources\Base\Config\ILogConfig;


$skeleton->set(ILogConfig::class,	Config\LogConfig::class,	Type::Singleton);