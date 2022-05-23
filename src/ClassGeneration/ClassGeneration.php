<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 10:15:58
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\ClassGeneration;

use EasySwoole\Utility\File;
use Nette\PhpGenerator\PhpNamespace;

class ClassGeneration
{
    /**
     * @var $config Config;
     */
    protected $config;
    protected $phpClass;
    protected $phpNamespace;
    protected $methodGenerationList = [];

    /**
     * BeanBuilder constructor.
     *
     * @param        $config
     *
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        File::createDirectory($config->getDirectory());
        $phpNamespace = new PhpNamespace($this->config->getNamespace());
        $this->phpNamespace = $phpNamespace;
        $className = $this->getClassName();
        $phpClass = $phpNamespace->addClass($className);
        $this->phpClass = $phpClass;
        $this->addExtend();
        $this->setImplements();
    }

    protected function addExtend()
    {
        $extendClass = $this->config->getExtendClass();
        if (!empty($extendClass)) {
            $this->phpNamespace->addUse($extendClass);
            $this->phpClass->addExtend($extendClass);
        }
    }

    protected function setImplements()
    {
        $implementClass = $this->config->getImplementsClass();
        if (!empty($implementClass)) {
            $this->phpNamespace->addUse($implementClass);
            $this->phpClass->setImplements([$implementClass]);
        }
    }

    final public function generate()
    {
        $this->addComment();
        $this->addClassData();
        /**
         * @var $method MethodAbstract
         */
        foreach ($this->methodGenerationList as $method) {
            $method->run();
        }
        return $this->createPHPDocument();
    }

    public function addClassData()
    {

    }

    protected function addComment()
    {
        $this->phpClass->addComment("{$this->getClassName()}");
        $this->phpClass->addComment("Class {$this->getClassName()}");
        $this->phpClass->addComment('Create With ClassGeneration');
    }

    protected function getClassName()
    {
        return $this->config->getClassName();
    }

    /**
     * createPHPDocument
     *
     * @return bool|int
     * @author Tioncico
     * Time: 19:49
     */
    protected function createPHPDocument()
    {
        $fileName = $this->config->getDirectory() . '/' . $this->getClassName();
        $content = "<?php\n\n{$this->phpNamespace}\n";
        $result = File::createFile($fileName . '.php', $content);
        return $result == false ? $result : $fileName . '.php';
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @return \Nette\PhpGenerator\ClassType
     */
    public function getPhpClass(): \Nette\PhpGenerator\ClassType
    {
        return $this->phpClass;
    }

    /**
     * @return PhpNamespace
     */
    public function getPhpNamespace(): PhpNamespace
    {
        return $this->phpNamespace;
    }

    public function addGenerationMethod(MethodAbstract $abstract)
    {
        $this->methodGenerationList[$abstract->getMethodName()] = $abstract;
    }
}
