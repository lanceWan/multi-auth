<?php
namespace Iwanli\MultiAuth\Console;
use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Iwanli\MultiAuth\Generators\AuthGenerator;
class MultiAuthCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:multi-auth {name} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold basic login and registration views and routes';

    protected $generator;
    /**
     * The views that need to be exported.
     *
     * @var array
     */
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AuthGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $plain = $this->option('force');
        $name = $this->argument('name');

        if (!$this->generator->config->get('multi')) {
            $this->call('vendor:publish',[
                '--tag' => 'multiatuh'
            ]);
            $this->comment('create config/mulit.php successfully !');exit();
        }

        $this->generator->create($plain,$this);

        $this->generator->generators($this->getAuthNameSegments($name),$plain);

        $this->comment('Authentication scaffolding generated successfully!');
    }

    protected function getAuthNameSegments($authname)
    {
        if (count(explode('/', $authname)) > 1 ) {
            return array_map('studly_case', explode('/', $authname));
        }

        return array_map('studly_case', explode(DIRECTORY_SEPARATOR, $authname));
    }

}