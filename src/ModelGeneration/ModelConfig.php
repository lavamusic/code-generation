<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:25:50
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ModelGeneration;

use LavaMusic\GenerateCode\ClassGeneration\Config;
use App\Model\LavaBaseModel;

class ModelConfig extends Config
{
    protected $fileSuffix = 'Model';
    protected $businessName = 'Unknown';
    protected $tableName;

    public function __construct(
        string $className,
        string $tableName,
        string $namespace = "App\\Api",
        string $extendClass = LavaBaseModel::class)
    {
        // 重写父类构造函数而不直接调用
//        $this->setClassName($className);
//        $this->setNamespace($nameSpace);
//        $this->setExtendClass($extendClass);
        parent::__construct($className, $namespace, $extendClass);
        $this->setTableName($tableName);
    }

    /**
     * @return string
     */
    public function getFileSuffix(): string
    {
        return $this->fileSuffix;
    }

    /**
     * @param string $fileSuffix
     */
    public function setFileSuffix(string $fileSuffix): void
    {
        $this->fileSuffix = $fileSuffix;
    }

    /**
     * @return string
     */
    public function getBusinessName(): string
    {
        return $this->businessName;
    }

    /**
     * @param string $businessName
     */
    public function setBusinessName(string $businessName): void
    {
        $this->businessName = $businessName;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }
}