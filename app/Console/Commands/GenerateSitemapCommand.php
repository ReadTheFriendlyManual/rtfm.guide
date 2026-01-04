<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Guide;
use App\Models\SitemapableModel;
use App\Sitemap\SitemapableEntity;
use Illuminate\Console\Command;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Symfony\Component\Console\Output\BufferedOutput;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {model? : (Optional) The model to generate the sitemap for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap XML files for the application';
    private SitemapIndex $sitemapIndex;

    public function __construct()
    {
        parent::__construct();

        $this->sitemapIndex = SitemapIndex::create();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->verifyRequiredDirectoriesExist()
            ->processGeneralPages()
            ->processSitemapFor([
                Category::class,
                Guide::class,
            ]);


        // Write the final Index file that Google will crawl
        $this->sitemapIndex->writeToFile(public_path('sitemap.xml'));
    }

    public function processSitemapFor(Collection|Arrayable|iterable|string $entities): static
    {
        if (is_string($entities)) {
            $entities = [$entities];
        }

        if (! $entities instanceof Collection) {
            $entities = collect($entities);
        }

        $entities
            ->map(function (mixed $entity) {
                if (is_string($entity) && ! class_exists($entity)) {
                    throw new \InvalidArgumentException(
                        "The provided entity class '$entity' does not exist"
                    );
                } elseif (is_string($entity)) {
                    $entity = new $entity();
                }

                if (! is_subclass_of($entity, SitemapableModel::class)) {
                    throw new \InvalidArgumentException(
                        "The provided entity must be an instance of " . SitemapableModel::class . var_dump($entity)
                    );
                }

                return $entity;
            })
            ->filter()
            ->each(function (SitemapableModel $entity) {
                $counter = 1;
                $modelName = strtolower(class_basename($entity));

                $entity::chunk(40000, function ($records) use (&$counter, $entity, $modelName) {
                    $filename = "sitemaps/sitemap-$modelName-$counter.xml";

                    // Create an individual sitemap for this batch
                    Sitemap::create()
                        ->add(
                            $records->map(function (SitemapableModel $record) use ($modelName) {
                                return $record->getSitemapRoute(true);
                            })->flatten()
                        )
                        ->writeToFile(public_path($filename));

                    // Add this specific sitemap to the main Index
                    $this->sitemapIndex->add("/{$filename}");

                    $counter++;
                });
        });

        return $this;
    }

    private function processGeneralPages(): static
    {
        $output = new BufferedOutput();
        $routes = Artisan::call(
            'route:list',
            [
                '--method' => 'GET',
                '--json' => 'true',
            ],
            $output
        );

        if ($routes !== 0) {
            $this->error('Failed to retrieve route list for sitemap generation.');

            $this->error('Error: ' . $output->fetch());

            return $this;
        }

        dd($output->fetch());

        Collection::fromJson($output->fetch())
            ->each(function ($route) {
                // Only process named routes
                if (empty($route->name)) {
                    return;
                }

                $filename = "sitemaps/sitemap-general.xml";

                // Create or append to the general sitemap
                Sitemap::create()
                    ->add(route($route->name))
                    ->writeToFile(public_path($filename));

                // Add this general sitemap to the main Index (only once)
                if (! $this->sitemapIndex->hasSitemap("/{$filename}")) {
                    $this->sitemapIndex->add("/{$filename}");
                }
            });

        return $this;
    }

    private function verifyRequiredDirectoriesExist(): static
    {
        $sitemapsPath = public_path('sitemaps');

        if (! is_dir($sitemapsPath)) {
            mkdir($sitemapsPath, 0755, true);
        }

        return $this;
    }
}
