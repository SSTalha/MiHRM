<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {name : The name of the helper file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new helper file';

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
        $helperName = $this->argument('name');
        $helperPath = app_path('Helpers');

        if(!$this->files->exists($helperPath)){
            $this->files->makeDirectory($helperPath, 0755, true);
        }

        $filePath = $helperPath . '/' . $helperName . '.php';

        if ($this->files->exists($filePath)) {
            $this->error("Helper {$helperName} already exists!");
            return Command::FAILURE;
        }

        $this->files->put($filePath, $this->getStubContent($helperName));

        $this->info("Helper {$helperName} created successfully.");
        return Command::SUCCESS;
    }

    protected function getStubContent($helperName)
    {
        return <<<EOT
<?php

namespace App\Helpers;

class {$helperName}
{
    // Add your helper methods here
}
EOT;
    }
}
