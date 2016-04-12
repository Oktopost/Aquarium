<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Skeleton\Type;
use Aquarium\Resources\Config;


/** @var \Skeleton\Skeleton $skeleton */
$skeleton = Config::skeleton();


// Utils
$skeleton->set(Process\ITimestampHelper::class,		Utils\TimestampHelper::class,	Type::Singleton);
$skeleton->set(Process\IPreCompileHelper::class,	Utils\PreCompileHelper::class,	Type::Singleton);