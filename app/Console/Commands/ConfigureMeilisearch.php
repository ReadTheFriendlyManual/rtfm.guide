<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MeiliSearch\Client;

class ConfigureMeilisearch extends Command
{
    protected $signature = 'meilisearch:configure';

    protected $description = 'Configure Meilisearch index settings for optimal search';

    public function handle(): int
    {
        $this->info('Configuring Meilisearch index settings...');

        try {
            $client = new Client(
                config('scout.meilisearch.host'),
                config('scout.meilisearch.key')
            );

            $index = $client->index('guides_index');

            // Configure searchable attributes (order matters for ranking)
            $index->updateSearchableAttributes([
                'title',
                'tldr',
                'content',
                'category',
            ]);
            $this->info('✓ Searchable attributes configured');

            // Configure filterable attributes
            $index->updateFilterableAttributes([
                'difficulty',
                'category_slug',
                'os_tags',
            ]);
            $this->info('✓ Filterable attributes configured');

            // Configure sortable attributes
            $index->updateSortableAttributes([
                'view_count',
                'published_at',
            ]);
            $this->info('✓ Sortable attributes configured');

            // Configure ranking rules
            $index->updateRankingRules([
                'words',
                'typo',
                'proximity',
                'attribute',
                'sort',
                'exactness',
                'view_count:desc',
            ]);
            $this->info('✓ Ranking rules configured');

            // Configure synonyms
            $index->updateSynonyms([
                'restart' => ['reboot', 'reload'],
                'reboot' => ['restart', 'reload'],
                'ssl' => ['tls', 'https'],
                'tls' => ['ssl', 'https'],
                'nginx' => ['engine-x'],
                'config' => ['configuration', 'configure'],
                'auth' => ['authentication', 'authorize'],
            ]);
            $this->info('✓ Synonyms configured');

            // Configure typo tolerance
            $index->updateTypoTolerance([
                'enabled' => true,
                'minWordSizeForTypos' => [
                    'oneTypo' => 4,
                    'twoTypos' => 8,
                ],
            ]);
            $this->info('✓ Typo tolerance configured');

            $this->newLine();
            $this->info('Meilisearch configuration completed successfully!');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to configure Meilisearch: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
