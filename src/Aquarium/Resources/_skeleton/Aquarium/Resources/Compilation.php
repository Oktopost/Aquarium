<?php
namespace Aquarium\Resources\Compilation;


use Skeleton\Type;
use Aquarium\Resources\Config;


/** @var \Skeleton\Skeleton $skeleton */
$skeleton = Config::skeleton();


$skeleton->set(IPhpBuilder::class,	DefaultPhpBuilder::class,	Type::Singleton);