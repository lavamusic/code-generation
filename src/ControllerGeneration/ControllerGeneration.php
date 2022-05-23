<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:25:29
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ControllerGeneration;

use LavaMusic\GenerateCode\ClassGeneration\ClassGeneration;
use LavaMusic\GenerateCode\ControllerGeneration\Method\Add;
use LavaMusic\GenerateCode\ControllerGeneration\Method\Delete;
use LavaMusic\GenerateCode\ControllerGeneration\Method\GetList;
use LavaMusic\GenerateCode\ControllerGeneration\Method\GetOne;
use LavaMusic\GenerateCode\ControllerGeneration\Method\Update;
use LavaMusic\GenerateCode\Templates\XxxLogic;
use App\Common\Util;
use App\Consts\ErrorCode\ErrorCode;
use Nette\PhpGenerator\PhpNamespace;

class ControllerGeneration extends ClassGeneration
{
    /**
     * @var $config ControllerConfig
     */
    protected $config;

    public function addClassData()
    {
        $this->addUse($this->phpNamespace);
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
        $phpNamespace->addUse(Util::class);
        $phpNamespace->addUse(XxxLogic::class);
        $phpNamespace->addUse(ErrorCode::class);
        $phpNamespace->addUse($this->config->getExtendClass());
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