<?php
namespace Aquarium\Resources\Base;


use Skeleton\Type;
use Aquarium\Resources\Config;


/** @var \Skeleton\Skeleton $skeleton */
$skeleton = Config::skeleton();


use Aquarium\Resources\Base\Config\ILogConfig;

$skeleton->set(ILogConfig::class, Config\LogConfig::class, Type::Singleton);


// State
use Aquarium\Resources\State\StateValidator;
use Aquarium\Resources\State\StateJsonFileDAO;

$skeleton->set(State\IStateDAO::class,			StateJsonFileDAO::class);
$skeleton->set(State\IStateValidator::class,	StateValidator::class);