<?php

namespace Core\UseCase\Interfaces;

interface FileStorageInterface
{
    public function store(string $path): string;
    public function delete(string $path);
}
