<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/21 16:32:03
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode;

use EasySwoole\Command\AbstractInterface\CommandHelpInterface;
use EasySwoole\Command\Color;
use EasySwoole\Command\CommandManager;
use EasySwoole\Command\Result;
use EasySwoole\Component\Di;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Command\CommandInterface;
use EasySwoole\Utility\ArrayToTextTable;
use Swoole\Coroutine\Scheduler;

class GenerateCodeCommand implements CommandInterface
{
    public function commandName(): string
    {
        return "generateCode";
    }

    public function desc(): string
    {
        return 'Code auto generation tool';
    }

    public function help(CommandHelpInterface $commandHelp): CommandHelpInterface
    {
        $commandHelp->addAction('all', 'create controller,model,modelConst,logic,validate,errorCodeConst');
        $commandHelp->addAction('controller', 'create controller class');
        $commandHelp->addAction('route', 'create route class');
        $commandHelp->addAction('model', 'create model class');
        $commandHelp->addAction('const', 'create modelConst class');
        $commandHelp->addAction('logic', 'create logic class');
        $commandHelp->addAction('validate', 'create validate class');
        $commandHelp->addAction('errorCodeConst', 'create errorCodeConst class');
        $commandHelp->addAction('entity', 'create entity class');
        $commandHelp->addActionOpt('-tableName', 'specify table name');
        $commandHelp->addActionOpt('-controllerName', 'specify controller name');
        $commandHelp->addActionOpt('-enableRoute', 'enable automatic route generation');
        $commandHelp->addActionOpt('-routeName', 'specify route name');
        $commandHelp->addActionOpt('-modelName', 'specify model name');
        $commandHelp->addActionOpt('-constName', 'specify const name');
        $commandHelp->addActionOpt('-logicName', 'specify logic name');
        $commandHelp->addActionOpt('-validateName', 'specify validate name');
        $commandHelp->addActionOpt('-errorCodeConstName', 'specify errorCodeConst name');
        $commandHelp->addActionOpt('-entityName', 'specify entity name');
        $commandHelp->addActionOpt('-connectName', 'specify db-pool connect name');
        $commandHelp->addActionOpt('-commonName', 'specify common command class name');
        return $commandHelp;
    }

    /**
     * @return string|null
     * Author: hlh XueSi
     * Email: 1592328848@qq.com
     * Date: 2022/5/23 16:53:14
     * // -controllerName="\\User\\V1\\User"
     * // ?????? ?????????
     * // -modelName="\\User\\V1\\UserModel"
     * // ??????
     * // -modelConstName="\\User\\UserConst"
     * // ???????????????
     * // -logicName="\\User\\UserLogic"
     * // Logic
     * // -validateName="\\User\\UserValidate"
     * // ?????????
     * // -errorCodeConstName="\\User\\UserErrorCode"
     * // ???????????????
     */
    public function exec(): ?string
    {
        $action = CommandManager::getInstance()->getArg(0);
        $result = new Result();
        $run = new Scheduler();
        $run->add(function () use (&$result, $action) {
            switch ($action) {
                case 'all':
                    $result = $this->all();
                    break;
                case 'controller':
                    // ?????? ?????????
                    $result = $this->generateController();
                    break;
                case 'route':
                    // ????????????
                    $result = $this->generateRoute();
                    break;
                case 'model':
                    // ????????????
                    $result = $this->generateModel();
                    break;
                case 'const':
                    // ????????????
                    $result = $this->generateConst();
                    break;
                case 'logic':
                    // ??????Logic
                    $result = $this->generateLogic();
                    break;
                case 'validate':
                    // ???????????????
                    $result = $this->generateValidate();
                    break;
                case 'errorCodeConst':
                    // ?????????????????????
                    $result = $this->generateErrorCodeConst();
                    break;
                case 'lang':
                    // ???????????????
                    $result = $this->generateLang();
                    break;
                case 'entity':
                    // ???????????????
                    $result = $this->generateEntity();
                    break;
                default:
                    $result = CommandManager::getInstance()->displayCommandHelp($this->commandName());
                    break;
            }
            Timer::getInstance()->clearAll();
        });
        $run->start();
        return $result . PHP_EOL;
    }

    protected function generateController()
    {
        $commandManager = CommandManager::getInstance();
        $controllerPath = $commandManager->getOpt('controllerName');
        // ????????????????????????
        $enableGenerateRoute = $commandManager->getOpt('enableRoute');

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        if ($controllerPath) {
            // ????????????????????????
            $filePath = $codeGeneration->generationController($controllerPath);
            $table[] = ['fileType' => 'Controller', "filePath" => $filePath];

            if ($enableGenerateRoute === 'true') {
                // ?????????????????????
                $table[] = $this->generateRoute(true);
            }
        } else {
            return Color::error('controllerName must be specified');
        }

        return new ArrayToTextTable($table);
    }

    protected function generateEntity()
    {
        $commandManager = CommandManager::getInstance();
        $entityPath = $commandManager->getOpt('entityName');
        $tableName = $commandManager->getOpt('tableName');
        $connectName = $commandManager->getOpt('connectName', 'default');

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        if ($entityPath) {
            // ?????????????????????
            $filePath = $codeGeneration->generationEntity($entityPath, $connectName, $tableName);
            $table[] = ['fileType' => 'Entity', "filePath" => $filePath];
        } else {
            return Color::error('entityName must be specified');
        }

        return new ArrayToTextTable($table);
    }

    protected function generateRoute(bool $needTableInfo = false)
    {
        $commandManager = CommandManager::getInstance();
        $controllerPath = $commandManager->getOpt('controllerName');

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        if ($controllerPath) {
            // ?????????????????????
            $filePath = $codeGeneration->generationRoute($controllerPath);
            $tableInfo = ['fileType' => 'Route', "filePath" => $filePath];
        } else {
            return Color::error('controllerName must be specified');
        }

        if ($needTableInfo) {
            return $tableInfo;
        }

        return new ArrayToTextTable([$tableInfo]);
    }

    protected function generateModel()
    {
        $commandManager = CommandManager::getInstance();

        $tableName = $commandManager->getOpt('tableName');
        if (empty($tableName)) {
            return Color::error('tableName not empty');
        }

        $modelPath = $commandManager->getOpt('modelName');
        if (empty($modelPath)) {
            return Color::error('modelName must be specified');
        }

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        // ?????????????????????
        $filePath = $codeGeneration->generationModel($modelPath, $tableName);
        $table[] = ['fileType' => 'Model', "filePath" => $filePath];


        return new ArrayToTextTable($table);
    }

    protected function generateConst()
    {
        $commandManager = CommandManager::getInstance();
        $constPath = $commandManager->getOpt('constName');

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        if ($constPath) {
            // ???????????????????????????
            $filePath = $codeGeneration->generationConst($constPath);
            $table[] = ['fileType' => 'Const', "filePath" => $filePath];
        } else {
            return Color::error('constName must be specified');
        }

        return new ArrayToTextTable($table);
    }

    protected function generateLogic()
    {
        $commandManager = CommandManager::getInstance();
        $logicPath = $commandManager->getOpt('logicName');

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        if ($logicPath) {
            // ???????????????????????????
            $filePath = $codeGeneration->generationLogic($logicPath);
            $table[] = ['fileType' => 'Logic', "filePath" => $filePath];
        } else {
            return Color::error('logicName must be specified');
        }

        return new ArrayToTextTable($table);
    }

    protected function generateValidate()
    {
        $commandManager = CommandManager::getInstance();
        $validatePath = $commandManager->getOpt('validateName');

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        if ($validatePath) {
            // ???????????????????????????
            $filePath = $codeGeneration->generationValidate($validatePath);
            $table[] = ['fileType' => 'Validate', "filePath" => $filePath];
        } else {
            return Color::error('validateName must be specified');
        }

        return new ArrayToTextTable($table);
    }

    protected function generateErrorCodeConst()
    {
        $commandManager = CommandManager::getInstance();
        $errorCodeConstPath = $commandManager->getOpt('errorCodeConstName');

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        if ($errorCodeConstPath) {
            // ???????????????????????????
            $filePath = $codeGeneration->generationErrorCodeConst($errorCodeConstPath);
            $table[] = ['fileType' => 'ErrorCodeConst', "filePath" => $filePath];
        } else {
            return Color::error('errorCodeConstName must be specified');
        }

        return new ArrayToTextTable($table);
    }

    protected function generateLang()
    {
        // todo::
        return new ArrayToTextTable([]);
    }

    protected function all()
    {
        $commandManager = CommandManager::getInstance();

        $tableName = $commandManager->getOpt('tableName');
        $controllerPath = $commandManager->getOpt('controllerName');
        $enableGenerateRoute = $commandManager->getOpt('enableRoute');
        $routePath = $commandManager->getOpt('routeName');
        $modelPath = $commandManager->getOpt('modelName');
        $constPath = $commandManager->getOpt('constName');
        $logicPath = $commandManager->getOpt('logicName');
        $validatePath = $commandManager->getOpt('validateName');
        $errorCodeConstPath = $commandManager->getOpt('errorCodeConstName');
        $commonName = $commandManager->getOpt('commonName');

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        $table = [];
        // ??????????????????
        if ($controllerPath) {
            $filePath = $codeGeneration->generationController($controllerPath);
            $table[] = ['className' => 'Controller', "filePath" => $filePath];
        }

        // ?????????????????????
        if ($routePath) {
            $filePath = $codeGeneration->generationRoute($routePath);
            $table[] = ['className' => 'Route', "filePath" => $filePath];
        } else {
            if ($enableGenerateRoute) {
                $filePath = $codeGeneration->generationRoute($controllerPath);
                $table[] = ['className' => 'Route', "filePath" => $filePath];
            }
        }

        if ($commonName) {
            $modelPath = $constPath = $logicPath = $validatePath = $errorCodeConstPath = $commonName;
        }

        // ???????????????
        if ($modelPath) {
            if (empty($tableName)) {
                return Color::error('table not empty');
            }
            $filePath = $codeGeneration->generationModel($modelPath, $tableName);
            $table[] = ['className' => 'Model', "filePath" => $filePath];
        }

        // ???????????????
        if ($constPath) {
            $filePath = $codeGeneration->generationConst($constPath);
            $table[] = ['className' => 'Const', "filePath" => $filePath];
        }

        // ??????Logic???
        if ($logicPath) {
            $filePath = $codeGeneration->generationLogic($logicPath);
            $table[] = ['className' => 'Logic', "filePath" => $filePath];
        }

        // ??????????????????
        if ($validatePath) {
            $filePath = $codeGeneration->generationValidate($validatePath);
            $table[] = ['className' => 'Validate', "filePath" => $filePath];
        }

        // ????????????????????????
        if ($errorCodeConstPath) {
            $filePath = $codeGeneration->generationErrorCodeConst($errorCodeConstPath);
            $table[] = ['className' => 'ErrorCodeConst', "filePath" => $filePath];
        }

        return new ArrayToTextTable($table);
    }

    protected function trySetDiGenerationPath(CodeGeneration $codeGeneration)
    {
        /** @var string $controllerBaseNameSpace */
        $controllerBaseNameSpace = Di::getInstance()->get('CodeGeneration.controllerBaseNameSpace');
        if ($controllerBaseNameSpace) {
            $codeGeneration->setControllerBaseNameSpace($controllerBaseNameSpace);
        }

        /** @var string $routeBaseNameSpace */
        $routeBaseNameSpace = Di::getInstance()->get('CodeGeneration.routeBaseNameSpace');
        if ($routeBaseNameSpace) {
            $codeGeneration->setRouteBaseNameSpace($routeBaseNameSpace);
        }

        /** @var string $modelBaseNameSpace */
        $modelBaseNameSpace = Di::getInstance()->get('CodeGeneration.modelBaseNameSpace');
        if ($modelBaseNameSpace) {
            $codeGeneration->setModelBaseNameSpace($modelBaseNameSpace);
        }

        /** @var string $constBaseNameSpace */
        $constBaseNameSpace = Di::getInstance()->get('CodeGeneration.constBaseNameSpace');
        if ($constBaseNameSpace) {
            $codeGeneration->setConstBaseNameSpace($constBaseNameSpace);
        }

        /** @var string $logicBaseNameSpace */
        $logicBaseNameSpace = Di::getInstance()->get('CodeGeneration.logicBaseNameSpace');
        if ($logicBaseNameSpace) {
            $codeGeneration->setLogicBaseNameSpace($logicBaseNameSpace);
        }

        /** @var string $validateBaseNameSpace */
        $validateBaseNameSpace = Di::getInstance()->get('CodeGeneration.validateBaseNameSpace');
        if ($validateBaseNameSpace) {
            $codeGeneration->setValidateBaseNameSpace($validateBaseNameSpace);
        }

        /** @var string $errorCodeConstBaseNameSpace */
        $errorCodeConstBaseNameSpace = Di::getInstance()->get('CodeGeneration.errorCodeConstBaseNameSpace');
        if ($errorCodeConstBaseNameSpace) {
            $codeGeneration->setErrorCodeConstBaseNameSpace($errorCodeConstBaseNameSpace);
        }

        /** @var string $rootPath */
        $rootPath = Di::getInstance()->get('CodeGeneration.rootPath');
        if ($rootPath) {
            $codeGeneration->setRootPath($rootPath);
        }
    }
}