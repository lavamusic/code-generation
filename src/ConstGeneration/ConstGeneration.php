<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:25:29
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ConstGeneration;

use LavaMusic\GenerateCode\ClassGeneration\ClassGeneration;
use LavaMusic\GenerateCode\ClassGeneration\Config;
use Nette\PhpGenerator\PhpNamespace;

class ConstGeneration extends ClassGeneration
{
    /**
     * @var $config Config
     */
    protected $config;

    public function addClassData()
    {

    }

    public function getClassName()
    {
        return $this->config->getClassName() . $this->config->getFileSuffix();
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