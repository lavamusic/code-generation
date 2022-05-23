<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 11:57:39
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\RouteGeneration;


use LavaMusic\GenerateCode\ClassGeneration\ClassGeneration;
use LavaMusic\GenerateCode\RouteGeneration\Method\Register;
use FastRoute\RouteCollector;
use Nette\PhpGenerator\PhpNamespace;

class RouteGeneration extends ClassGeneration
{
    /**
     * @var $config RouteConfig
     */
    protected $config;

    public function addClassData()
    {
        $this->addUse($this->phpNamespace);
        $this->addGenerationMethod(new Register($this));
    }

    public function getClassName()
    {
        return $this->config->getClassName() . $this->config->getFileSuffix();
    }

    protected function addUse(PhpNamespace $phpNamespace)
    {
        $phpNamespace->addUse(RouteCollector::class);
        $phpNamespace->addUse($this->config->getImplementsClass());
    }

    public function addGenerationMethod(\LavaMusic\GenerateCode\ClassGeneration\MethodAbstract $abstract)
    {
        $this->methodGenerationList[$abstract->getMethodName()] = $abstract;
    }

    public function addComment()
    {
        parent::addComment();
    }
}