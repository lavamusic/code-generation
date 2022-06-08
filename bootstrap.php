<?php
//全局bootstrap事件
date_default_timezone_set('Asia/Shanghai');

//$mysqlConfig = new \EasySwoole\ORM\Db\Config(Config::getInstance()->getConf('MYSQL')['default']);
//$connection = new \EasySwoole\ORM\Db\Connection($mysqlConfig);
\EasySwoole\Component\Di::getInstance()->set('CodeGeneration.controllerBaseNameSpace', "App\\Api");
\EasySwoole\Component\Di::getInstance()->set('CodeGeneration.routeBaseNameSpace', "App\\Route");
\EasySwoole\Component\Di::getInstance()->set('CodeGeneration.modelBaseNameSpace', "App\\Model");
\EasySwoole\Component\Di::getInstance()->set('CodeGeneration.constBaseNameSpace', "App\\Consts");
\EasySwoole\Component\Di::getInstance()->set('CodeGeneration.logicBaseNameSpace', "App\\Logic");
\EasySwoole\Component\Di::getInstance()->set('CodeGeneration.validateBaseNameSpace', "App\\Validate");
\EasySwoole\Component\Di::getInstance()->set('CodeGeneration.errorCodeConstBaseNameSpace', "App\\Consts\\ErrorCode");
//\EasySwoole\Component\Di::getInstance()->set('CodeGeneration.connection', $connection);
\EasySwoole\Component\Di::getInstance()->set('CodeGeneration.rootPath', getcwd());

\EasySwoole\Command\CommandManager::getInstance()->addCommand(new \LavaMusic\GenerateCode\GenerateCodeCommand());

\EasySwoole\EasySwoole\Config::getInstance()->loadDir(__DIR__ . '/Config');