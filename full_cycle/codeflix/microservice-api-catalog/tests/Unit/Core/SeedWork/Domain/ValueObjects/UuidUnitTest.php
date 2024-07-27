<?php

use Core\SeedWork\Domain\ValueObjects\Uuid;

test("should generate a uuid", function () {
    $uuid = (string) Uuid::random();
    expect($uuid)->not->toBeNull();
    expect($uuid)->toBeString();
});

test("should throw an exception with an invalid uuid", function () {
    $this->expectException(InvalidArgumentException::class);
    new Uuid('invalid-uuid');
});
