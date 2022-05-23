<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 11:34:33
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ControllerGeneration\Method;


class Delete extends MethodAbstract
{
    protected $methodName = 'delete';
    protected $methodDescription = '删除数据';

    public function addMethodBody()
    {
        $method = $this->method;
        $methodBody = <<<Body
// todo:: {$this->methodDescription}
try {
    \$params = \$this->_request->post();
    \$result = XxxLogic::factory()->{$this->methodName}(\$params);
} catch (\Throwable \$throwable) {
    return \$this->handleThrowable(\$throwable);
}

return Util::respWithCodeData(ErrorCode::SUCCESS, \$result);
Body;
        // 配置方法内容
        $method->setBody($methodBody);
    }
}