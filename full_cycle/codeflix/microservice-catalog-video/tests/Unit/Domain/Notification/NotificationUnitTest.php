<?php

namespace Tests\Unit\Domain\Notification;

use Core\Domain\Notification\Notification;
use PHPUnit\Framework\TestCase;

class NotificationUnitTest extends TestCase
{
    public function testGetErrors()
    {
        $notification = new Notification();

        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
    }

    public function testAddErrors()
    {
        $notification = new Notification();

        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required',
        ]);

        $this->assertCount(1, $notification->getErrors());
    }

    public function testHasErrors()
    {
        $notification = new Notification();

        $this->assertFalse($notification->hasErrors());

        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required',
        ]);

        $this->assertTrue($notification->hasErrors());
    }

    public function testMessage()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'title is required',
        ]);
        $notification->addError([
            'context' => 'video',
            'message' => 'description is required',
        ]);

        $message = $notification->messages();

        $this->assertIsString($message);
        $this->assertEquals(
            expected: 'video: title is required,video: description is required,',
            actual: $message
        );
    }

    public function testMessageFilterContext()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'title is required',
        ]);
        $notification->addError([
            'context' => 'category',
            'message' => 'description is required',
        ]);

        $this->assertCount(2, $notification->getErrors());

        $message = $notification->messages('video');

        $this->assertIsString($message);
        $this->assertEquals(
            expected: 'video: title is required,',
            actual: $message
        );
    }
}
