<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 12:12:48
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\RouteGeneration\Method;

use LavaMusic\GenerateCode\RouteGeneration\RouteConfig;
use LavaMusic\GenerateCode\RouteGeneration\RouteGeneration;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\Traits\NameAware;

abstract class MethodAbstract extends \LavaMusic\GenerateCode\ClassGeneration\MethodAbstract
{
    /**
     * @var \Nette\PhpGenerator\Method $method
     */
    protected $method;

    /**
     * @var RouteConfig
     */
    protected $routeConfig;

    protected $methodName = 'methodName';

    public function __construct(RouteGeneration $classGeneration)
    {
        parent::__construct($classGeneration);
        $this->classGeneration = $classGeneration;
        $method = $classGeneration->getPhpClass()->addMethod($this->getMethodName());
        $this->method = $method;
        $this->routeConfig = $classGeneration->getConfig();
    }

    public function run()
    {
        $this->addMethodComment();
        $this->addMethodParameters();
        $this->addMethodBody();
    }

    protected function addMethodComment()
    {
        $method = $this->method;

        // 配置基础注释
        $method->addComment("@param RouteCollector \$routeCollector");
        $method->addComment("Author: LavaMusic");
        $method->addComment("Date: " . date('Y/m/d H:i:s'));
    }

    protected function addMethodParameters()
    {
        $parameters = new Parameter('routeCollector');
        $parameters->setReference();
        $this->method->setParameters([$parameters]);
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