<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/6/8 9:30:54
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\EntityGeneration\Method;

class GetUpdateTimeFormat extends MethodAbstract
{
    protected $methodName = 'getUpdateTimeFormat';
    protected $methodDescription = '格式化更新时间格式化方法';

    public function addMethodBody()
    {
        $method = $this->method;
        $methodBody = <<<Body
// todo::
return \$this->update_time;
Body;
        // 配置方法内容
        $method->setBody($methodBody);
    }

    public function addMethodComment()
    {
        $method = $this->method;

        // 配置基础注释
        $method->addComment("Description: 格式化更新时间格式化方法");
        $method->addComment("@return string");
    }
}