<?php

namespace Tests\Bambamboole\Framework\Integration\Filesystem;

use Bambamboole\Framework\Filesystem\Filesystem;
use Bambamboole\Framework\Filesystem\FinderFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class FilesystemTest extends TestCase
{
    public function testItCanSearchForFilesInPath(): void
    {
        $fs = new Filesystem();

        $files = $fs->allFiles(__DIR__ . '/fixtures');

        self::assertCount(4, $files);
    }

    public function testDotFilesAreIgnoredByDefault(): void
    {
        $fs = new Filesystem();

        $files = $fs->allFiles(path: __DIR__ . '/fixtures', ignoreHidden: false);

        self::assertCount(5, $files);
    }

    public function testItCanFilterBySuffix(): void
    {
        $fs = new Filesystem();

        $files = $fs->allFiles(__DIR__ . '/fixtures', 'php');

        self::assertCount(3, $files);
    }

    public function testExists(): void
    {
        $fs = new Filesystem();

        self::assertTrue($fs->exists(__DIR__ . '/fixtures/random.php'));
    }

    public function testPutAndGetAndRemoveFile(): void
    {
        $testContent = bin2hex(random_bytes(10));
        $path = __DIR__ . '/fixtures/' . uniqid() . '.php';
        $fs = new Filesystem();

        $fs->put($path, $testContent);
        $content = $fs->get($path);

        self::assertEquals($testContent, $content);

        $fs->remove($path);
    }
}
