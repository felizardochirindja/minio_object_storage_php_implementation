<?php

namespace Modules\Storage;

interface StorageInterface {
    public function write(string $path, string $content): bool;
    public function read(string $path): string;
    public function delete(string $path): void;
}
