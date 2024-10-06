<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    protected $files;

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function __construct(Filesystem $files){
        parent::__construct();

        $this->files = $files;
    }

    
    public function handle()
    {
        $serviceName = $this->argument('name');
        $servicePath = app_path('Services');

        if(!$this->files->exists($servicePath)){
            $this->files->makeDirectory($servicePath, 0755, true);

        }

        $filePath = $servicePath . '/' . $serviceName . '.php';

        if ($this->files->exists($filePath)) {
            $this->error("Service {$serviceName} already exists!");
            return Command::FAILURE;
        }

        $this->files->put($filePath, $this->getStubContent($serviceName));

        return Command::SUCCESS;
    }

    protected function getStubContent($serviceName)
    {
        return <<<EOT
<?php

namespace App\Services;

class {$serviceName}
{
    // Add your service methods here
}
EOT;
}

}
