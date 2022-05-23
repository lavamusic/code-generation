<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:25:29
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ErrorCodeConstGeneration;

use LavaMusic\GenerateCode\ClassGeneration\ClassGeneration;
use LavaMusic\GenerateCode\ClassGeneration\Config;

class ErrorCodeConstGeneration extends ClassGeneration
{
    /**
     * @var $config Config
     */
    protected $config;

    public function addClassData()
    {
        $this->addConstant();
    }

    public function getClassName()
    {
        return $this->config->getClassName() . $this->config->getFileSuffix();
    }

    protected function addConstant()
    {
        $this->getPhpClass()->addConstant('ERROR_CODE', 500);
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