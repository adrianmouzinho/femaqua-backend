<?php

namespace App\Repositories;

use App\Interfaces\ToolRepositoryInterface;
use App\Models\Tool;

class ToolRepository implements ToolRepositoryInterface
{
    public function list(string $userId, ?string $tag)
    {
        return Tool::where('user_id', $userId)
            ->where(function ($query) use ($tag) {
                if ($tag) {
                    $query->whereRaw("JSON_SEARCH(tags, 'one', '%{$tag}%') IS NOT NULL");
                }
            })
            ->get();
    }

    public function getById(string $toolId)
    {
        $tool = Tool::find($toolId);

        return $tool;
    }

    public function store(array $data)
    {
        $tool = Tool::create($data);

        return $tool;
    }

    public function update(Tool $tool, array $data)
    {
        $tool->update($data);

        return $tool;
    }

    public function delete(Tool $tool)
    {
        $tool->delete();
    }
}
