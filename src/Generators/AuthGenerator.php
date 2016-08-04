<?php
namespace Iwanli\MultiAuth\Generators;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\AppNamespaceDetectorTrait;
/**
* 创建文件类
*/
class AuthGenerator
{
	use AppNamespaceDetectorTrait;

	protected $file;

	protected $config;

	protected $views;

	protected $controllerPath;

	public function __construct(Filesystem $file,Repository $config)
	{
		dd('123');
		$this->file = $file;
		$this->config = $config;

		$this->controllerPath = app_path('Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR);

		$this->views = [
	        'auth/login.stub' 			=> $this->config->get('multi.views.login'),
	        'auth/register.stub' 		=> $this->config->get('multi.views.register'),
	        'auth/passwords/email.stub' => $this->config->get('multi.views.email'),
	        'auth/passwords/reset.stub' => $this->config->get('multi.views.reset'),
	        'auth/emails/password.stub' => $this->config->get('multi.views.password'),
	        'layouts/app.stub' 			=> $this->config->get('multi.views.app'),
	        'home.stub' 				=> $this->config->get('multi.views.home'),
	        'welcome.stub' 				=> $this->config->get('multi.views.welcome'),
	    ];
	}

	public function create($plain,$command)
	{
		$this->createDirectories();

		$this->exportViews($plain,$command);
	}

	/**
	 * 创建视图目录
	 * @author 晚黎
	 * @date   2016-08-03T13:21:14+0800
	 * @return [type]                   [description]
	 */
	protected function createDirectories()
    {
        if (! is_dir(base_path($this->config->get('multi.directory.layouts')))) {
            $this->file->makeDirectory(base_path($this->config->get('multi.directory.layouts')), 0755, true);
        }

        if (! is_dir(base_path($this->config->get('multi.directory.auth.passwords')))) {
            $this->file->makeDirectory(base_path($this->config->get('multi.directory.auth.passwords')), 0755, true);
        }

        if (! is_dir(base_path($this->config->get('multi.directory.auth.emails')))) {
            $this->file->makeDirectory(base_path($this->config->get('multi.directory.auth.emails')), 0755, true);
        }
    }

     /**
     * 复制视图
     *
     * @return void
     */
    protected function exportViews($plain,$command)
    {
        foreach ($this->views as $key => $value) {
            $path = base_path('resources/views/'.$value);

            if (!$this->file->exists($path) || $plain) {

            	$command->line('<info>Created View:</info> '.$path);

	            $this->file->copy(__DIR__.'/../../templates/views/'.$key, $path);
            }
        }
    }

    public function generators($segments,$plain)
    {
    	$controllerName = collect($segments)->last();

    	$authConfig = $this->getAuthConfig($segments);

    	$this->generatorController($authConfig,$plain);

        $this->generatorMigration($authConfig,$plain);
        
    }

    /**
     * 控制器
     * @author 晚黎
     * @date   2016-08-03T15:08:51+0800
     * @return [type]                   [description]
     */
    protected function compileControllerStub(array $data,$stub)
    {
    	foreach ($data as $key => $value) {
	    	$stub = str_replace('{{'.$key.'}}' ,$value, $stub);
    	}
    	return $stub;
    }

    /**
     * 获取配置
     * @author 晚黎
     * @date   2016-08-04T17:52:27+0800
     * @param  [type]                   $segments [description]
     * @return [type]                             [description]
     */
    protected function getAuthConfig($segments)
    {
    	$dir = collect($segments);
    	$dir->pop();
    	$directory = implode(DIRECTORY_SEPARATOR,$dir->all());
    	$guards = collect(array_keys($this->config->get('multi.auth.guards')))->first();
        $provider = $this->config->get('multi.auth.guards.'.$guards.'.provider');
        $model = class_basename($this->config->get('multi.auth.providers.'.$provider.'.model'));
        $namespace = 'App\Http\Controllers\\'.implode('\\',$dir->all());
        return ['guards' => $guards,'provider' => $provider,'model' => $model,'namespace' => $namespace,'directory' =>$directory];
    }

    /**
     * 创建控制器
     * @author 晚黎
     * @date   2016-08-04T18:06:11+0800
     * @param  [type]                   $authConfig [description]
     * @param  [type]                   $plain      [description]
     * @return [type]                               [description]
     */
    public function generatorController($authConfig,$controllerName,$plain)
    {
    	if (! is_dir($this->controllerPath.$authConfig['directory'])) {
            $this->file->makeDirectory($this->controllerPath.$authConfig['directory'], 0755, true);
        }

        // 判断文件是否存在
        if ($plain || !$this->file->exists($this->controllerPath.$authConfig['directory'].DIRECTORY_SEPARATOR.'HomeController.php')) {
	        // HomeController
	        $homeController = $this->compileControllerStub([
	        		'namespace' => $authConfig['namespace'],
	        		'guards' => $authConfig['guards'],
	        	],
	        	$this->file->get(__DIR__.'/../../templates/controllers/HomeController.stub'));
	        $this->file->put($this->controllerPath.$authConfig['directory'].DIRECTORY_SEPARATOR.'HomeController.php',$homeController);
        }
        /**
         * auth-controller生成
         */
        if ($plain || !$this->file->exists($this->controllerPath.$authConfig['directory'].DIRECTORY_SEPARATOR.$controllerName.'.php')) {

	        $authController = $this->compileControllerStub([
	        		'namespace' => $authConfig['namespace'],
	        		'controller' => $controllerName,
	        		'redirectTo' => $this->config->get('multi.auth.redirectTo'),
	        		'guard' => $authConfig['guards'],
	        		'loginView' => $this->config->get('multi.auth.loginView'),
	        		'registerView' => $this->config->get('multi.auth.registerView'),
	        		'model' => $authConfig['model'],
	        		'table' => strtolower(str_plural($authConfig['model'])),
	        		'tableModel' => ucfirst($authConfig['model']),
	        	],
	        	$this->file->get(__DIR__.'/../../templates/controllers/AuthController.stub'));
	        $this->file->put($this->controllerPath.$authConfig['directory'].DIRECTORY_SEPARATOR.$controllerName.'.php',$authController);
        }
    }

    /**
     * 创建迁移文件
     * @author 晚黎
     * @date   2016-08-04T17:52:15+0800
     * @param  [type]                   $plain [description]
     * @return [type]                          [description]
     */
    protected function generatorMigration($config,$plain)
    {
    	$migrationName = '2106_08_04_000000_create_'.strtolower(str_plural($authConfig['model'])).'_table.php';
    	 if ($plain || !$this->file->exists(base_path('database/migrations/'.$migrationName)) {

	        $authController = $this->compileControllerStub([
	        		'migration' => ucfirst(str_plural($authConfig['model'])),
	        		'table' => strtolower(str_plural($authConfig['model']))
	        	],
	        	$this->file->get(__DIR__.'/../../templates/controllers/AuthMigration.stub'));
	        $this->file->put(base_path('database/migrations/'.$migrationName),$authController);
        }
    }


}