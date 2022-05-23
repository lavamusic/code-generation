<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 11:32:30
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ModelGeneration\Method;

use Nette\PhpGenerator\Parameter;

class GetOne extends MethodAbstract
{
    protected $methodName = 'getOne';
    protected $methodDescription = '获取一条数据';

    public function addMethodComment()
    {
        $method = $this->method;

        // 配置基础注释
        $method->addComment("Description: {$this->methodDescription}");
        $method->addComment("Author: LavaMusic");
        $method->addComment("Date: " . date('Y/m/d H:i:s'));
    }

    public function addMethodParameters()
    {
        $method = $this->method;

        // 配置参数
        $parameter = new Parameter('where');
        $parameter->setType('array');
        $method->setParameters([$parameter]);
    }

    public function addMethodBody()
    {
        $method = $this->method;
        $methodBody = <<<Body
// todo:: {$this->methodDescription}
return \$this->where(\$where)->get();
Body;
        // 配置方法内容
        $method->setBody($methodBody);
    }
}