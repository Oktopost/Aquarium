<?php
namespace Aquarium\Resources;


/** @var \Skeleton\Skeleton $skeleton */
$skeleton = Config::skeleton();


use Aquarium\Resources\Modules\Compilers\Gulp\GulpCommand;

$skeleton->set(Compilers\Gulp\IGulpCommand::class, GulpCommand::class);