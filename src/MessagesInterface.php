<?php

declare(strict_types=1);

namespace Firehead996\Flash;

/**
 * Flash messages
 */
interface MessagesInterface
{
    /**
     * Add flash message for the next request
     *
     * @param string $key The key to store the message under
     * @param mixed  $message Message to show on next request
     */
    public function addMessage(string $key, mixed $message): void;

    /**
     * Add flash message for current request
     *
     * @param string $key The key to store the message under
     * @param mixed  $message Message to show for the current request
     */
    public function addMessageNow(string $key, mixed $message): void;

    /**
     * Get flash messages
     *
     * @return array Messages to show for current request
     */
    public function getMessages(): array;

    /**
     * Get Flash Message
     *
     * @param string $key The key to get the message from
     * 
     * @return mixed|null Returns the message
     */
    public function getMessage(string $key): mixed;

    /**
     * Get the first Flash message
     *
     * @param  string $key The key to get the message from
     * @param  string $default Default value if key doesn't exist
     * 
     * @return mixed Returns the message
     */
    public function getFirstMessage(string $key, mixed $default = null): mixed;

    /**
     * Has Flash Message
     *
     * @param string $key The key to get the message from
     * 
     * @return bool Whether the message is set or not
     */
    public function hasMessage(string $key): bool;

    /**
     * Clear all messages
     *
     * @return void
     */
    public function clearMessages(): void;

    /**
     * Clear specific message
     *
     * @param string $key The key to clear
     * 
     * @return void
     */
    public function clearMessage(string $key): void;
}
