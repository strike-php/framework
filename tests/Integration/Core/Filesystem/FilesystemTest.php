<?php

declare(strict_types=1);

namespace Tests\Bambamboole\Framework\Integration\Core\Filesystem;

use Bambamboole\Framework\Core\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Tests\Bambamboole\Framework\Fixtures\HasFixtures;

class FilesystemTest extends TestCase
{
    use HasFixtures;

    public function testItCanSearchForFilesInPath(): void
    {
        $fs = new Filesystem();

        $files = $fs->allFiles($this->getFilesystemFixturePath());

        self::assertCount(4, $files);
    }

    public function testDotFilesAreIgnoredByDefault(): void
    {
        $fs = new Filesystem();

        $files = $fs->allFiles(path: $this->getFilesystemFixturePath(), ignoreHidden: false);

        self::assertCount(5, $files);
    }

    public function testItCanFilterBySuffix(): void
    {
        $fs = new Filesystem();

        $files = $fs->allFiles($this->getFilesystemFixturePath(), 'php');

        self::assertCount(3, $files);
    }

    public function testExists(): void
    {
        $fs = new Filesystem();

        self::assertTrue($fs->exists($this->getFilesystemFixturePath('random.php')));
    }

    public function testPutAndGetAndRemoveFile(): void
    {
        $testContent = \bin2hex(\random_bytes(10));
        $path = $this->getFilesystemFixturePath(\uniqid() . '.php');
        $fs = new Filesystem();

        $fs->put($path, $testContent);
        $content = $fs->get($path);

        self::assertEquals($testContent, $content);

        $fs->remove($path);
    }
}
