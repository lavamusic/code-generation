<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:24:48
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ControllerGeneration\Method;

use LavaMusic\GenerateCode\ControllerGeneration\ControllerConfig;
use LavaMusic\GenerateCode\ControllerGeneration\ControllerGeneration;

abstract class MethodAbstract extends \LavaMusic\GenerateCode\ClassGeneration\MethodAbstract
{
    /**
     * @var \Nette\PhpGenerator\Method $method
     */
    protected $method;

    /**
     * @var ControllerConfig
     */
    protected $controllerConfig;

    protected $methodName = 'methodName';

    public function __construct(ControllerGeneration $classGeneration)
    {
        parent::__construct($classGeneration);
        $this->classGeneration = $classGeneration;
        $method = $classGeneration->getPhpClass()->addMethod($this->getMethodName());
        $this->method = $method;
        $this->controllerConfig = $classGeneration->getConfig();
    }

    public function run()
    {
        $this->addMethodComment();
        $this->addMethodBody();
    }

    protected function addMethodComment()
    {
        $method = $this->method;

        // 配置基础注释
        $method->addComment("Path: /xxx");
        $method->addComment("Description: 接口描述");
        $method->addComment("Author: LavaMusic");
        $method->addComment("Date: " . date('Y/m/d H:i:s'));
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function addMethodBody()
    {
        // TODO: Implement addMethodBody() method.
    }
}