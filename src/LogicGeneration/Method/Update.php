<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 11:31:28
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\LogicGeneration\Method;

use Nette\PhpGenerator\Parameter;

class Update extends MethodAbstract
{
    protected $methodName = 'updateData';
    protected $methodDescription = '更新数据';

    public function addMethodComment()
    {
        $method = $this->method;

        // 配置基础注释
        $method->addComment("Description: {$this->methodDescription}");
        $method->addComment("Author: LavaMusic");
        $method->addComment("Date: " . date('Y/m/d H:i:s'));
    }

    public function addMethodBody()
    {
        $method = $this->method;
        $methodBody = <<<Body
// todo:: {$this->methodDescription}
\$where = ['id' => \$params['id']];
\$updateData = ['data' => \$params['data']];
return XxxModel::factory()->updateData(\$where, \$updateData);
Body;
        // 配置方法内容
        $method->setBody($methodBody);
    }
}