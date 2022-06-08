<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/6/7 19:13:11
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\EntityGeneration\Method;

class ApiOutputKey extends MethodAbstract
{
    protected $methodName = 'apiOutputKey';
    protected $methodDescription = 'API接口输出字段';

    public function addMethodBody()
    {
        $method = $this->method;
        $methodBody = <<<Body
\$outputKey = [
    // todo:: 选择输出字段
];
return \$outputKey;
Body;
        // 配置方法内容
        $method->setBody($methodBody);
    }

    public function addMethodComment()
    {
        $method = $this->method;

        // 配置基础注释
        $method->addComment("Description: API接口输出字段");
        $method->addComment("@return array");
    }
}