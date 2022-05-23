<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:23:45
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ClassGeneration;

abstract class MethodAbstract
{
    /**
     * @var ClassGeneration
     */
    protected $classGeneration;

    /**
     * @var \Nette\PhpGenerator\Method
     */
    protected $method;

    public function __construct(ClassGeneration $classGeneration)
    {
        $this->classGeneration = $classGeneration;
        $method = $classGeneration->getPhpClass()->addMethod($this->getMethodName());
        $this->method = $method;
    }

    public function run()
    {
        $this->addComment();
        $this->addMethodBody();
    }

    public function addComment()
    {
        return;
    }

    abstract public function addMethodBody();

    abstract public function getMethodName(): string;
}