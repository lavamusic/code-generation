<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 9:19:48
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode;

use App\Api\ApiBase;
use App\Common\BaseLogic;
use LavaMusic\GenerateCode\ConstGeneration\ConstConfig;
use LavaMusic\GenerateCode\ConstGeneration\ConstGeneration;
use LavaMusic\GenerateCode\ControllerGeneration\ControllerConfig;
use LavaMusic\GenerateCode\ControllerGeneration\ControllerGeneration;
use LavaMusic\GenerateCode\ErrorCodeConstGeneration\ErrorCodeConstConfig;
use LavaMusic\GenerateCode\ErrorCodeConstGeneration\ErrorCodeConstGeneration;
use LavaMusic\GenerateCode\LogicGeneration\LogicConfig;
use LavaMusic\GenerateCode\LogicGeneration\LogicGeneration;
use LavaMusic\GenerateCode\ModelGeneration\ModelConfig;
use LavaMusic\GenerateCode\ModelGeneration\ModelGeneration;
use LavaMusic\GenerateCode\RouteGeneration\RouteConfig;
use LavaMusic\GenerateCode\RouteGeneration\RouteGeneration;
use LavaMusic\GenerateCode\ValidateGeneration\ValidateConfig;
use LavaMusic\GenerateCode\ValidateGeneration\ValidateGeneration;
use App\Common\Lib\Validate\Validate;
use App\Model\LavaBaseModel;
use App\Route\RouteInterface;
use EasySwoole\ORM\Db\Connection;

class CodeGeneration
{
    protected $controllerGeneration;
    protected $routeGeneration;
    protected $modelGeneration;
    protected $constGeneration;
    protected $logicGeneration;
    protected $validateGeneration;
    protected $controllerBaseNameSpace = "App\\Api";
    protected $routeBaseNameSpace = "App\\Route";
    protected $modelBaseNameSpace = "App\\Model";
    protected $constBaseNameSpace = "App\\Consts";
    protected $logicBaseNameSpace = "App\\Logic";
    protected $validateBaseNameSpace = "App\\Validate";
    protected $errorCodeConstBaseNameSpace = "App\\Consts\\ErrorCode";
    protected $rootPath;

    public function __construct(string $tableName = null, Connection $connection = null)
    {
        if ($tableName && $connection) {
            $tableObjectGeneration = new \EasySwoole\ORM\Utility\TableObjectGeneration($connection, $tableName);
            $schemaInfo = $tableObjectGeneration->generationTable();
            $this->schemaInfo = $schemaInfo;
        }
    }

    private function getControllerGeneration(string $path, string $extendClass = ApiBase::class): ControllerGeneration
    {
        $fullPath = "{$this->controllerBaseNameSpace}{$path}";
        $namespaceArray = explode("\\", $fullPath);
        $className = array_pop($namespaceArray);
        $namespace = join("\\", $namespaceArray);
        $controllerConfig = new ControllerConfig($className, $namespace, $extendClass);
        $controllerConfig->setRootPath($this->getRootPath());
        $controllerGeneration = new ControllerGeneration($controllerConfig);
        $this->controllerGeneration = $controllerGeneration;
        return $controllerGeneration;
    }

    public function generationController(string $path, string $extendClass = ApiBase::class)
    {
        return $this->getControllerGeneration($path, $extendClass)->generate();
    }

    private function getRouteGeneration(string $path, string $implementsClass = RouteInterface::class): RouteGeneration
    {
        // User/V1/User
        // User/V1/Account
        $pathArray = explode("\\", trim($path, '\\'));
        $moduleName = $pathArray[0]; // 模块名
        $version = $pathArray[1];
        $businessName = array_pop($pathArray); // 业务模块名
        $routeConfig = new RouteConfig($moduleName, $this->routeBaseNameSpace, null, $implementsClass);
        $routeConfig->setModuleName($moduleName);
        $routeConfig->setBusinessName($businessName);
        $routeConfig->setVersion($version);
        $routeConfig->setRootPath($this->getRootPath());
        $routeGeneration = new RouteGeneration($routeConfig);
        $this->routeGeneration = $routeGeneration;
        return $routeGeneration;
    }

    public function generationRoute(string $path, string $implementsClass = RouteInterface::class)
    {
        return $this->getRouteGeneration($path, $implementsClass)->generate();
    }

    private function getModelGeneration(string $path, string $tableName, string $extendClass = LavaBaseModel::class): ModelGeneration
    {
        $fullPath = "{$this->modelBaseNameSpace}{$path}";
        $namespaceArray = explode("\\", $fullPath);
        $className = array_pop($namespaceArray);
        $namespace = join("\\", $namespaceArray);
        $modelConfig = new ModelConfig($className, $tableName, $namespace, $extendClass);
        $modelConfig->setBusinessName($className);
        $modelConfig->setRootPath($this->getRootPath());
        $modelGeneration = new ModelGeneration($modelConfig);
        $this->modelGeneration = $modelGeneration;
        return $modelGeneration;
    }

    public function generationModel(string $path, string $tableName, string $extendClass = LavaBaseModel::class)
    {
        return $this->getModelGeneration($path, $tableName, $extendClass)->generate();
    }

    public function generationConst(string $path, string $extendClass = null)
    {
        $fullPath = "{$this->constBaseNameSpace}{$path}";
        $namespaceArray = explode("\\", $fullPath);
        $className = array_pop($namespaceArray);
        $namespace = join("\\", $namespaceArray);
        $constConfig = new ConstConfig($className, $namespace, $extendClass);
        $constConfig->setRootPath($this->getRootPath());
        $constGeneration = new ConstGeneration($constConfig);
        return $constGeneration->generate();
    }

    public function generationLogic(string $path, string $extendClass = BaseLogic::class)
    {
        $fullPath = "{$this->logicBaseNameSpace}{$path}";
        $namespaceArray = explode("\\", $fullPath);
        $className = array_pop($namespaceArray);
        $namespace = join("\\", $namespaceArray);
        $logicConfig = new LogicConfig($className, $namespace, $extendClass);
        $logicConfig->setRootPath($this->getRootPath());
        $logicGeneration = new LogicGeneration($logicConfig);
        $this->logicGeneration = $logicGeneration;
        return $logicGeneration->generate();
    }

    public function generationValidate(string $path, string $extendClass = Validate::class)
    {
        $fullPath = "{$this->validateBaseNameSpace}{$path}";
        $namespaceArray = explode("\\", $fullPath);
        $className = array_pop($namespaceArray);
        $namespace = join("\\", $namespaceArray);
        $validateConfig = new ValidateConfig($className, $namespace, $extendClass);
        $validateConfig->setRootPath($this->getRootPath());
        $validateGeneration = new ValidateGeneration($validateConfig);
        $this->validateGeneration = $validateGeneration;
        return $validateGeneration->generate();
    }

    public function generationErrorCodeConst(string $path, string $extendClass = null)
    {
        $fullPath = "{$this->errorCodeConstBaseNameSpace}{$path}";
        $namespaceArray = explode("\\", $fullPath);
        $className = array_pop($namespaceArray);
        $namespace = join("\\", $namespaceArray);
        $errorCodeConstConfig = new ErrorCodeConstConfig($className, $namespace, $extendClass);
        $errorCodeConstConfig->setRootPath($this->getRootPath());
        $errorCodeConstGeneration = new ErrorCodeConstGeneration($errorCodeConstConfig);
        return $errorCodeConstGeneration->generate();
    }

    /**
     * @return string
     */
    public function getControllerBaseNameSpace(): string
    {
        return $this->controllerBaseNameSpace;
    }

    /**
     * @param string $controllerBaseNameSpace
     */
    public function setControllerBaseNameSpace(string $controllerBaseNameSpace): void
    {
        $this->controllerBaseNameSpace = $controllerBaseNameSpace;
    }

    /**
     * @return string
     */
    public function getRouteBaseNameSpace(): string
    {
        return $this->routeBaseNameSpace;
    }

    /**
     * @param string $routeBaseNameSpace
     */
    public function setRouteBaseNameSpace(string $routeBaseNameSpace): void
    {
        $this->routeBaseNameSpace = $routeBaseNameSpace;
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        if (empty($this->rootPath)) {
            $this->rootPath = getcwd();
        }
        return $this->rootPath;
    }

    /**
     * @param string $rootPath
     */
    public function setRootPath(string $rootPath): void
    {
        $this->rootPath = $rootPath;
    }

    /**
     * @return string
     */
    public function getModelBaseNameSpace(): string
    {
        return $this->modelBaseNameSpace;
    }

    /**
     * @param string $modelBaseNameSpace
     */
    public function setModelBaseNameSpace(string $modelBaseNameSpace): void
    {
        $this->modelBaseNameSpace = $modelBaseNameSpace;
    }

    /**
     * @return string
     */
    public function getConstBaseNameSpace(): string
    {
        return $this->constBaseNameSpace;
    }

    /**
     * @param string $constBaseNameSpace
     */
    public function setConstBaseNameSpace(string $constBaseNameSpace): void
    {
        $this->constBaseNameSpace = $constBaseNameSpace;
    }

    /**
     * @return string
     */
    public function getLogicBaseNameSpace(): string
    {
        return $this->logicBaseNameSpace;
    }

    /**
     * @param string $logicBaseNameSpace
     */
    public function setLogicBaseNameSpace(string $logicBaseNameSpace): void
    {
        $this->logicBaseNameSpace = $logicBaseNameSpace;
    }

    /**
     * @return string
     */
    public function getValidateBaseNameSpace(): string
    {
        return $this->validateBaseNameSpace;
    }

    /**
     * @param string $validateBaseNameSpace
     */
    public function setValidateBaseNameSpace(string $validateBaseNameSpace): void
    {
        $this->validateBaseNameSpace = $validateBaseNameSpace;
    }

    /**
     * @return string
     */
    public function getErrorCodeConstBaseNameSpace(): string
    {
        return $this->errorCodeConstBaseNameSpace;
    }

    /**
     * @param string $errorCodeConstBaseNameSpace
     */
    public function setErrorCodeConstBaseNameSpace(string $errorCodeConstBaseNameSpace): void
    {
        $this->errorCodeConstBaseNameSpace = $errorCodeConstBaseNameSpace;
    }
}