<?php
namespace Chrisplugs\CreatePlugsScript;

use EasySwoole\Command\AbstractInterface\CommandHelpInterface;
use EasySwoole\Command\CommandManager;
use EasySwoole\EasySwoole\Command\CommandInterface;
use EasySwoole\Utility\File;

class PlugsCreateCommand implements CommandInterface
{
    private $ROOT_SUFFIX = 'Plugs';

    public function commandName(): string
    {
        return "create_plugs";
    }

    public function exec(): ?string
    {
        $plugsName = CommandManager::getInstance()->getArg(0);
        //过滤
        $search = array('*','$','\\',"'",'"','<','《','>','》','@','#');
        $plugName =  str_replace($search, '', $plugsName);
        if(!$plugName){
            throw new \Exception('The plug-in name is not set');
        }
        //规定使用者无须添加插件后缀，程序会加上。
        $plugsName .= $this->ROOT_SUFFIX;
        //判断格式
        if (false === strstr($plugsName, "/")){
            return '错误，格式需要 a/b';
        }

        list ($packName, $plugsName) = explode("/", $plugsName);
        if(!preg_match('/^[A-Z]+$/', $packName) || !preg_match('/^[A-Z]+$/', $plugsName)){
            return '错误，包名和插件名必须使用大驼峰。';

        }

            // 放到Addons中
        if(EASYSWOOLE_ROOT){
            $path = EASYSWOOLE_ROOT."/Addons/{$packName}/$plugsName";
        }else{
            $path = getcwd()."/Addons/{$packName}/$plugsName";
        }
        // 把defaultFile的所有东西copy 到目标目录即可
        File::copyDirectory(__DIR__."/defaultFile/", $path);

        return "Create Finish! Your Plug Path : ". $path;
    }

    public function help(CommandHelpInterface $commandHelp): CommandHelpInterface
    {
        return $commandHelp->addAction("create_plugs", "创建新插件");
    }

    public function desc(): string
    {
        return "创建新插件";
    }
}