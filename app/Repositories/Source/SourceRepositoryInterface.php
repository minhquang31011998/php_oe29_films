<?php
namespace App\Repositories\Source;

interface SourceRepositoryInterface
{
    public function storeSource($request);

    public function sortSource($prioritize, $videoSources);

    public function updateSource($request, $sourceId);

    public function deleteSource($sourceId);
}
