<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDTO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Data Transfer Object (DTO)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $directory = app_path('DTOs');

        // Check if the DTOs directory exists, if not, create it
        if (!File::exists($directory)) {
            File::makeDirectory($directory);
        }

        $filePath = $directory . "/{$name}.php";

        // Check if the DTO file already exists
        if (File::exists($filePath)) {
            $this->error("DTO named {$name} already exists!");
            return 1;
        }

        // Create the content for the DTO class
        $dtoTemplate = $this->getDTOTemplate($name);

        // Write the file to the DTOs directory
        File::put($filePath, $dtoTemplate);

        $this->info("DTO {$name} created successfully.");
        return 0;
    }

    /**
     * Get the DTO class template.
     *
     * @param string $name
     * @return string
     */
    protected function getDTOTemplate($name)
    {
        return <<<EOT
<?php

namespace App\DTOs;

class {$name}
{
    // Define the properties for this DTO
    // public string \$property;

    public function __construct() {
        // Assign values to properties
    }

    // Add any necessary methods here
}
EOT;
    }
}
