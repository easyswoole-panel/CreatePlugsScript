<?php
/**
 * Team : EasyswoolePanelPlugs Team
 * name : specification directory script
 * author : chrisQx
 */
const ROOT_SUFFIX       = 'Plugs';
Class CreatePlugs
{
    //目录
    static private $dirTree = [
        'src'       => 'src',
        'common'    => 'src/common/',
        'controller'=> 'src/controller/',
        'database'  => 'src/database/',
        'model'     => 'src/model/',
        'service'   => 'src/service/',
        'view'      => 'src/view/'
    ];
    //文件
    static private $fileTree = [
        'composer'            => 'composer.json',
        'esPlugsConfig'       => 'esPlugsConfig.php',
        'PlugsInitialization' => 'src/PlugsInitialization.php',
        'install'             => 'src/database/install_1.0.php',
        'gitignore'           => 'src/view/.gitignore'

    ];
    //插件名
    static private $plugName;
    //插件根目录
    static private $baseDir;

    static public function run(string $plugName)
    {
        self::$plugName = $plugName;
        self::$baseDir = __DIR__.'/'.self::$plugName.ROOT_SUFFIX;
        if (self::createDir()){
            echo('create directories successful');
            echo PHP_EOL;
        }
        if(self::createFile()){
            echo('create file successful');
            echo PHP_EOL;
        }
        echo "Your Plug Path : ".self::$baseDir;
        echo PHP_EOL;

    }

    /**
     * 生成目录结构
     * @return bool
     * @throws Exception
     */
    static public function createDir()
    {
        echo('Start creating directories ......');
        echo "\r\n";

        if(!file_exists(self::$baseDir)){
                foreach (self::$dirTree as $v){
                    $dirName = self::$baseDir.'/'.$v;
                    $make = mkdir($dirName,0777,true);
                    chmod($dirName,0777);
                    if(!$make){
                        self::delDir(self::$baseDir);
                        throw new \Exception($dirName .' created error');
                    }
                }
            return true;
        }
        throw new \Exception(self::$baseDir .' is exists');
    }

    /**
     * 生成文件
     * @return bool
     * @throws Exception
     */
    static public function createFile()
    {
        echo('Start create Files ......');
        echo "\r\n";

        foreach (self::$fileTree as $key => $v){
            $file = self::$baseDir.'/'.$v;
            if (!file_exists($file)){
                    //创建并写入
                    $createFile = self::writeFile($file,$key);
                    if(!$createFile) {
                        throw new \Exception($file. ' File touch error');
                    }
            }
        }
        return true;
    }

    /**
     * 写入
     * @param $file
     * @param $key
     * @return bool
     * @throws Exception
     */
    static protected function writeFile($file, $key)
    {
        $str = '';
        switch ($key){
            case 'composer' :
                $str = "{".PHP_EOL;
                $str .= "    \"name\":\"\"," .PHP_EOL;
                $str .= "    \"type\":\"library\"," .PHP_EOL;
                $str .= "    \"description\":\"EasySwoole Panel's Plugs\"," .PHP_EOL;
                $str .= "    \"keywords\":" . '[' . "\"easyswoole panel\", \"easyswoole plugs\"]," .PHP_EOL;
                $str .= "    \"homepage\":\"\",".PHP_EOL;
                $str .= "    \"license\":\"Apache-2.0\"," .PHP_EOL;
                $str .= "    \"authors\":" . '[{' .PHP_EOL. "        \"name\":\"\"," .PHP_EOL."        \"email\":\"\"".PHP_EOL."    }]," . PHP_EOL;
                $str .= "    \"autoload\":{".PHP_EOL."    \"psr-4\":{}".PHP_EOL.'}'.PHP_EOL;
                $str .= "}";
                break;
            case 'esPlugsConfig':
                $str  = "<?php".PHP_EOL;
                $str .= " return [".PHP_EOL;
                $str .= " 'name'=>'插件名',".PHP_EOL;
                $str .= " 'des'=>'插件详情描述',".PHP_EOL;
                $str .= " 'namespace'=>'xxx\\\Plugs\\\',".PHP_EOL;
                $str .= " 'version'=>'1.0',//当前插件版本号,".PHP_EOL;
                $str .= " 'logo'=>'插件图标 可访问url'".PHP_EOL;
                $str .= " 'home_view' => '插件主页地址，可以是配置项编辑页面 或者外部页面等等',".PHP_EOL;
                $str .= "];";
                break;
            case 'PlugsInitialization':
                $str = "<?php".PHP_EOL;
                $str .= "/**
* 插件初始化 只要引入composer 每次ES运行都会初始化
*/";
                break;
            case 'install':
                $str = "<?php".PHP_EOL;
                $str .= "/**
 * Your Plug Name
 * Description
 * version 1.0
 * Author: 
 * Date: 
 */

// 可以做的事....
// 创建表结构,更改表结构,添加数据 等等用于安装时的插件初始化必要内容
";
                break;
            case 'gitignore':
                $str = "*";
                break;
        }
        if(file_put_contents($file,$str) === false){
            throw new \Exception("File:".$file."Write Error");
        }
        return true;
    }

    /**
     * @param string $dir 错误后调用命令删除
     */
    static private function delDir(string $dir)
    {
        if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $str = "rmdir /s/q " . $dir;
        } else {
            $str = "rm -Rf " . $dir;
        }
        exec($str);
    }
}



array_shift($argv);
//过滤
$search = array('*','$','\\','/',"'",'"','<','《','>','》','@','#');
$plugName =  str_replace($search, '', $argv[0]);
if(!$plugName){
    throw new \Exception('The plug-in name is not set');
}
CreatePlugs::run($plugName);



