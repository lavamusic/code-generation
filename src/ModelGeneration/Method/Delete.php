<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 11:34:33
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ModelGeneration\Method;


use Nette\PhpGenerator\Parameter;

class Delete extends MethodAbstract
{
    protected $methodName = 'deleteData';
    protected $methodDescription = '删除数据';

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
return \$this->delete(\$where);
Body;
        // 配置方法内容
        $method->setBody($methodBody);
    }
}