<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 12:05:55
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\RouteGeneration;

use LavaMusic\GenerateCode\ClassGeneration\Config;
use App\Route\RouteInterface;

class RouteConfig extends Config
{
    protected $fileSuffix = 'Route';
    protected $moduleName = 'unknownModule'; // 模块名称
    protected $businessName = 'unknownLogic'; // 业务名称
    protected $version = 'V1'; // 版本

    public function __construct(
        string $className,
        string $namespace = "App\\Route",
        string $extendClass = null,
        string $implementsClass = RouteInterface::class
    )
    {
        parent::__construct($className, $namespace, $extendClass);
        $this->setImplementsClass($implementsClass);
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
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     */
    public function setModuleName(string $moduleName): void
    {
        $this->moduleName = $moduleName;
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
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }
}