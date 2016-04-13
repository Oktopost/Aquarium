<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Aquarium\Resources\Compilers\Gulp\Cmd\GulpCommand;
use Aquarium\Resources\Compilers\Gulp\Cmd\Shell;
use Skeleton\Type;
use Aquarium\Resources\Config;


/** @var \Skeleton\Skeleton $skeleton */
$skeleton = Config::skeleton();

// Shell
$skeleton->set(IShell::class, 			Shell::class,		Type::Singleton);
$skeleton->set(IGulpCommand::class, 	GulpCommand::class);

// Actions
$skeleton->set(IGulpActionFactory::class, Actions\ActionFactory::class,	Type::Singleton);

// Utils
$skeleton->set(Process\ITimestampHelper::class,		Utils\TimestampHelper::class,	Type::Singleton);
$skeleton->set(Process\IPreCompileHelper::class,	Utils\PreCompileHelper::class,	Type::Singleton);
