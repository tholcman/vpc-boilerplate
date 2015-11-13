#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$configurator = new Nette\Configurator();
$configurator->setDebugMode(true);
$configurator->enableDebugger('/tmp/');
$configurator->setTempDirectory('/tmp/');
$configurator->addConfig(__DIR__ . '/src/config.neon');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/src/')
	->register();

$container = $configurator->createContainer();

$application = $container->getByType(Symfony\Component\Console\Application::class);
$commands = $container->findByType(Symfony\Component\Console\Command\Command::class);


foreach ($commands as $commandName) {
    $application->add($container->getService($commandName));
}

$application->run();
