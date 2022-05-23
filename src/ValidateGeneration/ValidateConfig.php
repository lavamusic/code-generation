<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:25:50
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ValidateGeneration;

use LavaMusic\GenerateCode\ClassGeneration\Config;
use App\Common\Lib\Validate\Validate;

class ValidateConfig extends Config
{
    protected $fileSuffix = 'Validate';

    public function __construct(string $className, string $namespace = "App\\Validate", string $extendClass = Validate::class)
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