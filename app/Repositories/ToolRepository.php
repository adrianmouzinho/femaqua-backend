<?php

namespace App\Repositories;

use App\Interfaces\ToolRepositoryInterface;
use App\Models\Tool;

class ToolRepository implements ToolRepositoryInterface
{
    public function index($tag)
    {
        return Tool::where(function ($query) use ($tag) {
            if ($tag) {
                $query->whereRaw("JSON_SEARCH(tags, 'one', '%{$tag}%') IS NOT NULL");
            }
        })->get();
    }

    public function getById($toolId)
    {
        return Tool::find($toolId);
    }

    public function store(array $data)
    {
        return Tool::create($data);
    }

    public function update(array $data, $toolId)
    {
        $tool = $this->getById($toolId);

        if (!$tool) {
            return null;
        }

        $tool->update($data);

        return $tool;
    }

    public function delete($toolId)
    {
        Tool::destroy($toolId);
    }
}
