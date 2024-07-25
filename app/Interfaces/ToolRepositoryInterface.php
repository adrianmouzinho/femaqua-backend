<?php

namespace App\Interfaces;

use App\Models\Tool;

interface ToolRepositoryInterface
{
    public function list(string $userId, ?string $tag);
    public function getById(string $toolId);
    public function store(array $data);
    public function update(Tool $tool, array $data);
    public function delete(Tool $tool);
}
