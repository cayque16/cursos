<?php

use Core\SeedWork\Domain\ValueObjects\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

test("should generate a uuid", function () {
    $uuid = (string) Uuid::random();
    expect($uuid)->not->toBeNull();
    expect($uuid)->toBeString();
});

test('should valid uuid', function (){
    $uuid = Uuid::random();
    expect(RamseyUuid::isValid($uuid))->toBeTrue();
});

test('should instance of uuid', function () {
    expect(Uuid::random())->toBeInstanceOf(Uuid::class);
});

test("should throw an exception with an invalid uuid", function () {
    $this->expectException(InvalidArgumentException::class);
    new Uuid('invalid-uuid');
});
