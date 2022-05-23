<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:25:50
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ErrorCodeConstGeneration;

use LavaMusic\GenerateCode\ClassGeneration\Config;

class ErrorCodeConstConfig extends Config
{
    protected $fileSuffix = 'ErrorCode';

    public function __construct(
        string $className,
        string $namespace = "App\\Consts\\ErrorCode",
        string $extendClass = null)
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
}