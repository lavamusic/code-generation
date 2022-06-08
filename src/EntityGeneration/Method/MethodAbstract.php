<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:24:48
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\EntityGeneration\Method;

use LavaMusic\GenerateCode\EntityGeneration\EntityConfig;
use LavaMusic\GenerateCode\EntityGeneration\EntityGeneration;

abstract class MethodAbstract extends \LavaMusic\GenerateCode\ClassGeneration\MethodAbstract
{
    /**
     * @var \Nette\PhpGenerator\Method $method
     */
    protected $method;

    /**
     * @var EntityConfig
     */
    protected $entityConfig;

    protected $methodName = 'methodName';

    public function __construct(EntityGeneration $classGeneration)
    {
        parent::__construct($classGeneration);
        $this->classGeneration = $classGeneration;
        $method = $classGeneration->getPhpClass()->addMethod($this->getMethodName());
        $this->method = $method;
        $this->entityConfig = $classGeneration->getConfig();
    }

    public function run()
    {
        $this->addMethodComment();
        $this->addMethodBody();
    }

    protected function addMethodComment()
    {
        // TODO: Implement addMethodBody() method.
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