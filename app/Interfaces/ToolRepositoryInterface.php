<?php

namespace App\Interfaces;

interface ToolRepositoryInterface
{
    public function index($tag);
    public function getById($toolId);
    public function store(array $data);
    public function update(array $data, $toolId);
    public function delete($toolId);
}
