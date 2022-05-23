<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 9:31:26
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\Utility;

use EasySwoole\ORM\Utility\Schema\Table;

class Utility
{
    public static function getNamespacePath($rootPath, $namespace)
    {
        $composerJson = json_decode(file_get_contents($rootPath . '/composer.json'), true);
        if (isset($composerJson['autoload']['psr-4']["{$namespace}\\"])) {
            return $composerJson['autoload']['psr-4']["{$namespace}\\"];
        }
        if (isset($composerJson['autoload-dev']['psr-4']["{$namespace}\\"])) {
            return $composerJson['autoload-dev']['psr-4']["{$namespace}\\"];
        }
        return "$namespace/";
    }

    /**
     * convertDbTypeToDocType
     *
     * @param $fieldType
     *
     * @return string
     * @author Tioncico
     * Time: 19:49
     */
    public static function convertDbTypeToDocType($fieldType)
    {
        if (in_array($fieldType, ['tinyint', 'smallint', 'mediumint', 'int', 'bigint'])) {
            $newFieldType = 'int';
        } elseif (in_array($fieldType, ['float', 'double', 'real', 'decimal', 'numeric'])) {
            $newFieldType = 'float';
        } elseif (in_array($fieldType, ['char', 'varchar', 'text'])) {
            $newFieldType = 'string';
        } else {
            $newFieldType = 'mixed';
        }
        return $newFieldType;
    }

    public static function chunkTableColumn(Table $table, callable $callback)
    {
        foreach ($table->getColumns() as $column) {
            $columnName = $column->getColumnName();
            $result = $callback($column, $columnName);
            if ($result === true) {
                break;
            }
        }
    }

    public static function getModelName($modelClass)
    {
        $modelNameArr = (explode('\\', $modelClass));
        $modelName = end($modelNameArr);
        return $modelName;
    }
}