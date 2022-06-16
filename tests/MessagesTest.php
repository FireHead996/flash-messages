<?php

declare(strict_types=1);

namespace Firehead996\Flash\Tests;

use PHPUnit\Framework\TestCase;

use Firehead996\Flash\Messages;
use Firehead996\Flash\MessagesInterface;

class MessagesTest extends TestCase
{
    private MessagesInterface $messages;

    // Test get messages from previous request
    public function testGetMessagesFromPrevRequest()
    {
        $storage = ['flash' => ['Test']];
        $this->messages = new Messages($storage);

        $this->assertEquals(['Test'], $this->messages->getMessages());
    }

    // Test a string can be added to a message array for the current request
    public function testAddMessageFromAnIntegerForCurrentRequest()
    {
        $storage = ['flash' => []];
        $flash   = new Messages($storage);

        $flash->addMessageNow('key', 46);
        $flash->addMessageNow('key', 48);

        $messages = $flash->getMessages();
        $this->assertEquals(['46','48'], $messages['key']);

        $this->assertArrayHasKey('flash', $storage);
        $this->assertEmpty($storage['flash']);
    }

    // Test a string can be added to a message array for the current request
    public function testAddMessageFromStringForCurrentRequest()
    {
        $storage = ['flash' => []];
        $flash   = new Messages($storage);

        $flash->addMessageNow('key', 'value');

        $messages = $flash->getMessages();
        $this->assertEquals(['value'], $messages['key']);

        $this->assertArrayHasKey('flash', $storage);
        $this->assertEmpty($storage['flash']);
    }

    // Test an array can be added to a message array for the current request
    public function testAddMessageFromArrayForCurrentRequest()
    {
        $storage = ['flash' => []];
        $flash   = new Messages($storage);

        $formData = [
            'username'     => 'Scooby Doo',
            'emailAddress' => 'scooby@mysteryinc.org',
        ];

        $flash->addMessageNow('old', $formData);

        $messages = $flash->getMessages();
        $this->assertEquals($formData, $messages['old'][0]);

        $this->assertArrayHasKey('flash', $storage);
        $this->assertEmpty($storage['flash']);
    }

    // Test an object can be added to a message array for the current request
    public function testAddMessageFromObjectForCurrentRequest()
    {
        $storage = ['flash' => []];
        $flash   = new Messages($storage);

        $user = new \stdClass();
        $user->name         = 'Scooby Doo';
        $user->emailAddress = 'scooby@mysteryinc.org';

        $flash->addMessageNow('user', $user);

        $messages = $flash->getMessages();
        $this->assertInstanceOf(\stdClass::class, $messages['user'][0]);

        $this->assertArrayHasKey('flash', $storage);
        $this->assertEmpty($storage['flash']);
    }

    // Test a string can be added to a message array for the next request
    public function testAddMessageFromAnIntegerForNextRequest()
    {
        $storage = ['flash' => []];
        $flash   = new Messages($storage);

        $flash->addMessage('key', 46);
        $flash->addMessage('key', 48);

        $this->assertArrayHasKey('flash', $storage);
        $this->assertEquals(['46', '48'], $storage['flash']['key']);
    }

    // Test a string can be added to a message array for the next request
    public function testAddMessageFromStringForNextRequest()
    {
        $storage = ['flash' => []];
        $flash   = new Messages($storage);

        $flash->addMessage('key', 'value');

        $this->assertArrayHasKey('flash', $storage);
        $this->assertEquals(['value'], $storage['flash']['key']);
    }

    // Test an array can be added to a message array for the next request
    public function testAddMessageFromArrayForNextRequest()
    {
        $storage = ['flash' => []];
        $flash   = new Messages($storage);

        $formData = [
            'username'     => 'Scooby Doo',
            'emailAddress' => 'scooby@mysteryinc.org',
        ];

        $flash->addMessage('old', $formData);

        $this->assertArrayHasKey('flash', $storage);
        $this->assertEquals($formData, $storage['flash']['old'][0]);
    }

    // Test an object can be added to a message array for the next request
    public function testAddMessageFromObjectForNextRequest()
    {
        $storage = ['flash' => []];
        $flash   = new Messages($storage);

        $user = new \stdClass();
        $user->name         = 'Scooby Doo';
        $user->emailAddress = 'scooby@mysteryinc.org';

        $flash->addMessage('user', $user);

        $this->assertArrayHasKey('flash', $storage);
        $this->assertInstanceOf(\stdClass::class, $storage['flash']['user'][0]);
    }

    // Test get empty messages from previous request
    public function testGetEmptyMessagesFromPrevRequest()
    {
        $storage = [];
        $flash = new Messages($storage);

        $this->assertEquals([], $flash->getMessages());
    }

    // Test set messages for current request
    public function testSetMessagesForCurrentRequest()
    {
        $storage = ['flash' => [ 'error' => ['An error']]];

        $flash = new Messages($storage);
        $flash->addMessageNow('error', 'Another error');
        $flash->addMessageNow('success', 'A success');
        $flash->addMessageNow('info', 'An info');

        $messages = $flash->getMessages();
        $this->assertEquals(['An error', 'Another error'], $messages['error']);
        $this->assertEquals(['A success'], $messages['success']);
        $this->assertEquals(['An info'], $messages['info']);

        $this->assertArrayHasKey('flash', $storage);
        $this->assertEquals([], $storage['flash']);
    }

    // Test set messages for next request
    public function testSetMessagesForNextRequest()
    {
        $storage = [];
        
        $flash = new Messages($storage);
        $flash->addMessage('Test', 'Test');
        $flash->addMessage('Test', 'Test2');

        $this->assertArrayHasKey('flash', $storage);
        $this->assertEquals(['Test', 'Test2'], $storage['flash']['Test']);
    }
    
    //Test getting the message from the key
    public function testGetMessageFromKey()
    {
        $storage = ['flash' => [ 'Test' => ['Test', 'Test2']]];
        $flash = new Messages($storage);

        $this->assertEquals(['Test', 'Test2'], $flash->getMessage('Test'));
    }

    //Test getting the first message from the key
    public function testGetFirstMessageFromKey()
    {
        $storage = ['flash' => [ 'Test' => ['Test', 'Test2']]];
        $flash = new Messages($storage);

        $this->assertEquals('Test', $flash->getFirstMessage('Test'));
    }

    //Test getting the default message if the key doesn't exist
    public function testDefaultFromGetFirstMessageFromKeyIfKeyDoesntExist()
    {
        $storage = ['flash' => []];
        $flash = new Messages($storage);

        $this->assertEquals('This', $flash->getFirstMessage('Test', 'This'));
    }

    //Test getting the message from the key
    public function testGetMessageFromKeyIncludingCurrent()
    {
        $storage = ['flash' => [ 'Test' => ['Test', 'Test2']]];
        $flash = new Messages($storage);
        $flash->addMessageNow('Test', 'Test3');

        $messages = $flash->getMessages();

        $this->assertEquals(['Test', 'Test2','Test3'], $flash->getMessage('Test'));
    }

    public function testHasMessage()
    {
        $storage = ['flash' => []];
        $flash = new Messages($storage);
        $this->assertFalse($flash->hasMessage('Test'));

        $storage = ['flash' => [ 'Test' => ['Test']]];
        $flash = new Messages($storage);
        $this->assertTrue($flash->hasMessage('Test'));
    }

    public function testClearMessages()
    {
        $storage = ['flash' => []];
        $flash = new Messages($storage);

        $storage = ['flash' => [ 'Test' => ['Test']]];
        $flash = new Messages($storage);
        $flash->addMessageNow('Now', 'hear this');
        $this->assertTrue($flash->hasMessage('Test'));
        $this->assertTrue($flash->hasMessage('Now'));

        $flash->clearMessages();
        $this->assertFalse($flash->hasMessage('Test'));
        $this->assertFalse($flash->hasMessage('Now'));
    }

    public function testClearMessage()
    {
        $storage = ['flash' => []];
        $flash = new Messages($storage);

        $storage = ['flash' => [ 'Test' => ['Test'], 'Foo' => ['Bar']]];
        $flash = new Messages($storage);
        $flash->addMessageNow('Now', 'hear this');
        $this->assertTrue($flash->hasMessage('Test'));
        $this->assertTrue($flash->hasMessage('Foo'));
        $this->assertTrue($flash->hasMessage('Now'));

        $flash->clearMessage('Test');
        $flash->clearMessage('Now');
        $this->assertFalse($flash->hasMessage('Test'));
        $this->assertFalse($flash->hasMessage('Now'));
        $this->assertTrue($flash->hasMessage('Foo'));
    }

    public function testSettingCustomStorageKey()
    {
        $storage = ['some-key' => [ 'Test' => ['Test']]];
        $flash = new Messages($storage);
        $this->assertFalse($flash->hasMessage('Test'));

        $flash = new Messages($storage, 'some-key');
        $this->assertTrue($flash->hasMessage('Test'));
    }
}
