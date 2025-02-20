<?php

namespace App\Tests\Service;

use App\Repository\CheckSlugExistsRepositoryInterface;
use App\Service\SlugGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class SlugGeneratorTest extends TestCase
{
    public function testGenerateSlug(): void
    {
        $repository = $this->createMock(CheckSlugExistsRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('checkSlugExists')
            ->with('test', null)
            ->willReturn(false);

        $slugGenerator = new SlugGenerator($repository);
        $this->assertSame('test', $slugGenerator('test'));
    }

    public function testGenerateSlugWithExistingSlug(): void
    {
        $repository = $this->createMock(CheckSlugExistsRepositoryInterface::class);
        $repository->expects($this->exactly(2))
            ->method('checkSlugExists')
            ->withConsecutive(
                ['test', null],
                ['test-1', null]
            )
            ->willReturnOnConsecutiveCalls(
                true,
                false
            );

        $slugGenerator = new SlugGenerator($repository);
        $this->assertSame('test-1', $slugGenerator('test'));
    }

    public function testGenerateSlugWithExistingSlugAndUuid(): void
    {
        $uuid = Uuid::v4();

        $repository = $this->createMock(CheckSlugExistsRepositoryInterface::class);
        $repository->expects($this->exactly(2))
            ->method('checkSlugExists')
            ->withConsecutive(
                ['test', $uuid],
                ['test-1', $uuid]
            )
            ->willReturnOnConsecutiveCalls(
                true,
                false
            );

        $slugGenerator = new SlugGenerator($repository);
        $this->assertSame('test-1', $slugGenerator('test', $uuid));
    }
}
