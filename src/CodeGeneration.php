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
use App\Common\Lib\Entity\AbstractEntity;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Mysqli\Client;
use LavaMusic\GenerateCode\ConstGeneration\ConstConfig;
use LavaMusic\GenerateCode\ConstGeneration\ConstGeneration;
use LavaMusic\GenerateCode\ControllerGeneration\ControllerConfig;
use LavaMusic\GenerateCode\ControllerGeneration\ControllerGeneration;
use LavaMusic\GenerateCode\EntityGeneration\EntityConfig;
use LavaMusic\GenerateCode\EntityGeneration\EntityGeneration;
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
    protected $entityBaseNameSpace = "App\\Entity";
    protected $rootPath;

    public function __construct(string $tableName = null)
    {

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

    public function generationEntity(string $path, string $connectName, string $tableName, string $extendClass = AbstractEntity::class)
    {
        $fullPath = "{$this->entityBaseNameSpace}{$path}";
        $namespaceArray = explode("\\", $fullPath);
        $className = array_pop($namespaceArray);
        $namespace = join("\\", $namespaceArray);
        $entityConfig = new EntityConfig($className, $namespace, $extendClass);
        $entityConfig->setRootPath($this->getRootPath());
        $entityConfig->setTableName($tableName);

        list($tableComment, $propertyConstData) = $this->getPropertyConstData($connectName, $tableName);

        // 补充字段 create_time 和 update_time
        $propertyNameArray = array_column($propertyConstData, 'propertyName');
        $needProperty = ['create_time', 'update_time'];
        foreach ($needProperty as $need) {
            if (!in_array($need, $propertyNameArray, true)) {
                $constName = $this->decamelize($need);
                $propertyConstData[] = [
                    'propertyName'    => $need,
                    'propertyType'    => 'int',
                    'constName'    => $constName,
                    'propertyComment' => $need === 'create_time' ? '创建时间' : '更新时间'
                ];
            }
        }

        $entityConfig->setTableComment($tableComment);
        $entityConfig->setPropertyConstData($propertyConstData);
        $entityGeneration = new EntityGeneration($entityConfig);
        return $entityGeneration->generate();
    }

    const MYSQL_TYPE_MAP = [
        'int'       => 'int',
        'tinyint'   => 'int',
        'smallint'  => 'int',
        'mediumint' => 'int',
        'float'     => 'float',
        'bigint'    => 'int',
        'double'    => 'float',
        'decimal'   => 'float',
        'char'      => 'string',
        'varchar'   => 'string',
        'text'      => 'string',
        'longtext'  => 'string',
        'datetime'  => 'string',
        'string'    => 'string',
    ];

    /**
     * 驼峰转大写下划线常量
     *
     * @param string $word
     *
     * @return string
     */
    private function decamelize(string $word)
    {
        return strtoupper(
            preg_replace(
                ["/([A-Z]+)/", "/_([A-Z]+)([A-Z][a-z])/"],
                ["_$1", "_$1_$2"],
                lcfirst($word)
            )
        );
    }

    private function getPropertyConstData(string $connectName, string $tableName)
    {
        $mysqlConfig = Config::getInstance()->getConf("MYSQL.{$connectName}");
        if (empty($mysqlConfig)) {
            throw new \Exception("Config MYSQL.{$connectName} is not set.");
        }

        $dbName = $mysqlConfig['database'];

        $mysqliConfObj = new \EasySwoole\Mysqli\Config($mysqlConfig);

        $client = new Client($mysqliConfObj);

        // 读取表信息
        $tableSql = "SELECT TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$dbName}' AND TABLE_NAME = '{$tableName}';";
        $client->queryBuilder()->raw($tableSql);
        $tableInfo = $client->execBuilder();
        if (empty($tableInfo) || !isset($tableInfo[0]) || empty($tableInfo[0])) {
            throw new \Exception("{$dbName}.{$tableName} is not exist.");
        }
        $tableComment = $tableInfo[0]['TABLE_COMMENT'];

        // 读取表字段
        $fieldSql = "SELECT COLUMN_NAME, DATA_TYPE, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$dbName}' AND TABLE_NAME = '{$tableName}';";
        $client->queryBuilder()->raw($fieldSql);
        $tableFieldInfo = $client->execBuilder();
        if (empty($tableFieldInfo)) {
            throw new \Exception("读取{$dbName}.{$tableName}表字段信息失败.");
        }

        $columnInfo = [];
        foreach ($tableFieldInfo as $item) {
            $constName = $this->decamelize($item['COLUMN_NAME']);
            $columnInfo[] = [
                'propertyName'    => $item['COLUMN_NAME'],
                'propertyType'    => self::MYSQL_TYPE_MAP[$item['DATA_TYPE']],
                'constName'       => $constName,
                'propertyComment' => trim($item['COLUMN_COMMENT']),
            ];
        }

        return [$tableComment, $columnInfo];
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