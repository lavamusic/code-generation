<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/6/7 18:40:00
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\EntityGeneration;

use LavaMusic\GenerateCode\ClassGeneration\ClassGeneration;
use LavaMusic\GenerateCode\EntityGeneration\Method\ApiOutputKey;
use LavaMusic\GenerateCode\EntityGeneration\Method\GetCreateTimeFormat;
use LavaMusic\GenerateCode\EntityGeneration\Method\GetUpdateTimeFormat;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;

class EntityGeneration extends ClassGeneration
{
    /**
     * @var $config EntityConfig
     */
    protected $config;

    public function addClassData()
    {
        $this->addUse($this->phpNamespace);

        $this->addProperty();

        $this->addGenerationMethod(new GetCreateTimeFormat($this));
        $this->addGenerationMethod(new GetUpdateTimeFormat($this));
        $this->addGenerationMethod(new ApiOutputKey($this));
    }

    public function getClassName()
    {
        return $this->config->getClassName() . $this->config->getFileSuffix();
    }

    public function addComment()
    {
        $this->phpClass->addComment("{$this->getClassName()} 实体");
        $this->phpClass->addComment($this->config->getTableComment() . "实体");
        $this->phpClass->addComment("Class {$this->getClassName()}");
        $this->phpClass->addComment('Create With ClassGeneration');
        $package = $this->config->getNamespace() . "\\" . $this->getClassName();
        $this->phpClass->addComment("@package {$package}");

        $propertyArray = $this->config->getPropertyConstData();
        foreach ($propertyArray as $property) {
            $this->getPhpClass()->addComment("@property {$property['propertyType']} \${$property['propertyName']}");
        }
    }

    protected function addProperty()
    {
        // 添加常量和属性
        $propertyArray = $this->config->getPropertyConstData();
        $propertyObjs = [];
        foreach ($propertyArray as $property) {
            $propertyObj = new Property($property['propertyName']);
            $propertyObj->setPublic();
            $propertyObj->addComment($property['propertyComment']);
            $propertyObj->addComment("@var {$property['propertyType']}");
            $propertyObjs[] = $propertyObj;
            // 添加常量
            $this->getPhpClass()->addConstant($property['constName'], $property['propertyName']);
        }

        // 添加属性
        $this->getPhpClass()->setProperties($propertyObjs);
    }

    protected function addUse(PhpNamespace $phpNamespace)
    {
        $phpNamespace->addUse($this->config->getExtendClass());
    }

    public function addGenerationMethod(\LavaMusic\GenerateCode\ClassGeneration\MethodAbstract $abstract)
    {
        $this->methodGenerationList[$abstract->getMethodName()] = $abstract;
    }
}