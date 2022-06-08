<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/6/8 9:29:42
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\EntityGeneration\Method;

class GetCreateTimeFormat extends MethodAbstract
{
    protected $methodName = 'getCreateTimeFormat';
    protected $methodDescription = '格式化创建时间格式化方法';

    public function addMethodBody()
    {
        $method = $this->method;
        $methodBody = <<<Body
// todo::
return \$this->create_time;
Body;
        // 配置方法内容
        $method->setBody($methodBody);
    }

    public function addMethodComment()
    {
        $method = $this->method;

        // 配置基础注释
        $method->addComment("Description: 格式化创建时间格式化方法");
        $method->addComment("@return string");
    }
}