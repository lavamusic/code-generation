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
     * // 生成 控制器
     * // -modelName="\\User\\V1\\UserModel"
     * // 模型
     * // -modelConstName="\\User\\UserConst"
     * // 模型常量类
     * // -logicName="\\User\\UserLogic"
     * // Logic
     * // -validateName="\\User\\UserValidate"
     * // 验证器
     * // -errorCodeConstName="\\User\\UserErrorCode"
     * // 错误码常量
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
                    // 生成 控制器
                    $result = $this->generateController();
                    break;
                case 'route':
                    // 生成路由
                    $result = $this->generateRoute();
                    break;
                case 'model':
                    // 生成模型
                    $result = $this->generateModel();
                    break;
                case 'const':
                    // 生成常量
                    $result = $this->generateConst();
                    break;
                case 'logic':
                    // 生成Logic
                    $result = $this->generateLogic();
                    break;
                case 'validate':
                    // 生成验证器
                    $result = $this->generateValidate();
                    break;
                case 'errorCodeConst':
                    // 生成错误码常量
                    $result = $this->generateErrorCodeConst();
                    break;
                case 'lang':
                    // 生成多语言
                    $result = $this->generateLang();
                    break;
                case 'entity':
                    // 生成多语言
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
        // 开启自动生成路由
        $enableGenerateRoute = $commandManager->getOpt('enableRoute');

        $codeGeneration = new CodeGeneration();
        $this->trySetDiGenerationPath($codeGeneration);

        if ($controllerPath) {
            // 生成控制器类文件
            $filePath = $codeGeneration->generationController($controllerPath);
            $table[] = ['fileType' => 'Controller', "filePath" => $filePath];

            if ($enableGenerateRoute === 'true') {
                // 生成路由类文件
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
            // 生成实体类文件
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
            // 生成路由类文件
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

        // 生成模型类文件
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
            // 生成模型常量类文件
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
            // 生成模型常量类文件
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
            // 生成模型常量类文件
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
            // 生成模型常量类文件
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
        // 生成控制器类
        if ($controllerPath) {
            $filePath = $codeGeneration->generationController($controllerPath);
            $table[] = ['className' => 'Controller', "filePath" => $filePath];
        }

        // 生成路由配置类
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

        // 生成模型类
        if ($modelPath) {
            if (empty($tableName)) {
                return Color::error('table not empty');
            }
            $filePath = $codeGeneration->generationModel($modelPath, $tableName);
            $table[] = ['className' => 'Model', "filePath" => $filePath];
        }

        // 生成常量类
        if ($constPath) {
            $filePath = $codeGeneration->generationConst($constPath);
            $table[] = ['className' => 'Const', "filePath" => $filePath];
        }

        // 生成Logic类
        if ($logicPath) {
            $filePath = $codeGeneration->generationLogic($logicPath);
            $table[] = ['className' => 'Logic', "filePath" => $filePath];
        }

        // 生成验证器类
        if ($validatePath) {
            $filePath = $codeGeneration->generationValidate($validatePath);
            $table[] = ['className' => 'Validate', "filePath" => $filePath];
        }

        // 生成错误码常量类
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