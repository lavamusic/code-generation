<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:25:29
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ModelGeneration;

use LavaMusic\GenerateCode\ClassGeneration\ClassGeneration;
use LavaMusic\GenerateCode\ModelGeneration\Method\Add;
use LavaMusic\GenerateCode\ModelGeneration\Method\Delete;
use LavaMusic\GenerateCode\ModelGeneration\Method\GetList;
use LavaMusic\GenerateCode\ModelGeneration\Method\GetOne;
use LavaMusic\GenerateCode\ModelGeneration\Method\Update;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;

class ModelGeneration extends ClassGeneration
{
    /**
     * @var $config ModelConfig
     */
    protected $config;

    public function addClassData()
    {
        $this->addUse($this->phpNamespace);
        $this->addProperty();
        $this->addGenerationMethod(new Add($this));
        $this->addGenerationMethod(new Update($this));
        $this->addGenerationMethod(new GetOne($this));
        $this->addGenerationMethod(new GetList($this));
        $this->addGenerationMethod(new Delete($this));
    }

    public function getClassName()
    {
        return $this->config->getClassName() . $this->config->getFileSuffix();
    }

    protected function addUse(PhpNamespace $phpNamespace)
    {
        $phpNamespace->addUse($this->config->getExtendClass());
    }

    protected function addProperty()
    {
        $tableProperty = new Property('table');
        $tableProperty->setProtected();
        $tableProperty->setValue($this->config->getTableName());
        $this->getPhpClass()->setProperties([$tableProperty]);
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