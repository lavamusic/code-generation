<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 12:12:36
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\RouteGeneration\Method;

class Register extends MethodAbstract
{
    protected $methodName = 'register';
    protected $methodDescription = '路由配置';

    public function addMethodBody()
    {
        $method = $this->method;
        $module = $this->routeConfig->getModuleName();
        $moduleToLower = strtolower($module);
        $businessName = $this->routeConfig->getBusinessName();

        $path = '';
        if ($module !== $businessName) {
            $path = "/" . strtolower($businessName);
        }

        $methodBody = <<<Body
// todo:: {$module} {$this->methodDescription}
\$routeCollector->addGroup('/{$moduleToLower}', function (RouteCollector \$routeCollector) {
    \$routeCollector->addGroup('/v1', function (RouteCollector \$routeCollector) {
        // todo:: 新增数据
        \$routeCollector->addRoute('POST', '{$path}/add', '/{$module}/V1/{$businessName}/add');
        // todo:: 更新数据
        \$routeCollector->addRoute('POST', '{$path}/update', '/{$module}/V1/{$businessName}/update');
        // todo:: 获取一条数据
        \$routeCollector->addRoute('POST', '{$path}/getone', '/{$module}/V1/{$businessName}/getOne');
        // todo:: 获取数据列表
        \$routeCollector->addRoute('POST', '{$path}/getlist', '/{$module}/V1/{$businessName}/getList');
        // todo:: 删除数据
        \$routeCollector->addRoute('POST', '{$path}/delete', '/{$module}/V1/{$businessName}/delete');
    });
});
Body;
        // 配置方法内容
        $method->setBody($methodBody);
    }
}