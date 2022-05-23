<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:25:29
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ValidateGeneration;

use LavaMusic\GenerateCode\ClassGeneration\ClassGeneration;
use Nette\PhpGenerator\Attribute;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;

class ValidateGeneration extends ClassGeneration
{
    /**
     * @var $config ValidateConfig
     */
    protected $config;

    public function addClassData()
    {
        $this->addUse($this->phpNamespace);
        $this->addAttributes($this->phpNamespace);
    }

    public function getClassName()
    {
        return $this->config->getClassName() . $this->config->getFileSuffix();
    }

    protected function addUse(PhpNamespace $phpNamespace)
    {
        $phpNamespace->addUse($this->config->getExtendClass());
    }

    protected function addAttributes(PhpNamespace $phpNamespace)
    {
        $ruleProperty = new Property('rule');
        $ruleProperty->setProtected();
        $ruleProperty->setValue([
            'param1' => 'required',
            'param2' => 'require',
        ]);

        $messageProperty = new Property('message');
        $messageProperty->setProtected();
        $messageProperty->setValue([
            'param1.required' => 'param1参数必填',
            'param2.require'  => 'param1参数不能为空',
        ]);

        $sceneProperty = new Property('scene');
        $sceneProperty->setProtected();
        $sceneProperty->setValue([
            'add'    => ['param1', 'param2'],
            'update' => ['param1', 'param2'],
        ]);

        $this->getPhpClass()->setProperties([$ruleProperty, $messageProperty, $sceneProperty]);
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