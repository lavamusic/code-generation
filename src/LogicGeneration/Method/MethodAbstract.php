<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:24:48
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\LogicGeneration\Method;

use LavaMusic\GenerateCode\LogicGeneration\LogicConfig;
use LavaMusic\GenerateCode\LogicGeneration\LogicGeneration;
use Nette\PhpGenerator\Parameter;

abstract class MethodAbstract extends \LavaMusic\GenerateCode\ClassGeneration\MethodAbstract
{
    /**
     * @var \Nette\PhpGenerator\Method $method
     */
    protected $method;

    /**
     * @var LogicConfig
     */
    protected $modelConfig;

    protected $methodName = 'methodName';

    public function __construct(LogicGeneration $classGeneration)
    {
        parent::__construct($classGeneration);
        $this->classGeneration = $classGeneration;
        $method = $classGeneration->getPhpClass()->addMethod($this->getMethodName());
        $this->method = $method;
        $this->modelConfig = $classGeneration->getConfig();
    }

    public function run()
    {
        $this->addMethodComment();
        $this->addMethodParameters();
        $this->addMethodBody();
    }

    public function addMethodParameters()
    {
        $method = $this->method;

        // 配置参数
        $parameter = new Parameter('params');
        $parameter->setType('array');
        $method->setParameters([$parameter]);
    }

    abstract public function addMethodComment();

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