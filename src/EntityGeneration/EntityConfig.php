<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/6/7 18:47:46
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\EntityGeneration;

use App\Common\Lib\Entity\AbstractEntity;
use LavaMusic\GenerateCode\ClassGeneration\Config;

class EntityConfig extends Config
{
    protected $fileSuffix = 'Entity';

    protected $tableName;

    protected $propertyConstData;

    protected $tableComment = '';

    public function __construct(string $className, string $namespace = "App\\Entity", string $extendClass = AbstractEntity::class)
    {
        // 重写父类构造函数而不直接调用
//        $this->setClassName($className);
//        $this->setNamespace($nameSpace);
//        $this->setExtendClass($extendClass);
        parent::__construct($className, $namespace, $extendClass);
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

    /**
     * @return mixed
     */
    public function getPropertyConstData()
    {
        return $this->propertyConstData;
    }

    /**
     * @param mixed $propertyConstData
     */
    public function setPropertyConstData($propertyConstData): void
    {
        $this->propertyConstData = $propertyConstData;
    }

    /**
     * @return string
     */
    public function getTableComment(): string
    {
        return $this->tableComment;
    }

    /**
     * @param string $tableComment
     */
    public function setTableComment(string $tableComment): void
    {
        $this->tableComment = $tableComment;
    }
}