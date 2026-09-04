<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ControllerQueryBuilderTest extends TestCase
{
    #[DataProvider('databaseControllerProvider')]
    public function test_database_controllers_use_laravel_query_builder(string $path): void
    {
        $source = File::get($path);

        $this->assertStringContainsString('DB::table(', $source);
        $this->assertDoesNotMatchRegularExpression(
            '/\b[A-Z][A-Za-z0-9_]*::(?:query|create|find|where)\s*\(/',
            $source
        );
        $this->assertDoesNotMatchRegularExpression(
            '/->(?:load|fresh|save|sync)\s*\(/',
            $source
        );
    }

    public static function databaseControllerProvider(): array
    {
        $controllers = glob(
            dirname(__DIR__, 2).'/app/Http/Controllers/*Controller.php'
        );

        return collect($controllers)
            ->reject(fn (string $path): bool => basename($path) === 'Controller.php')
            ->mapWithKeys(fn (string $path): array => [
                basename($path) => [$path],
            ])
            ->all();
    }
}
