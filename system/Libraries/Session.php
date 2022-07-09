<?php

namespace System\Libraries;

use ArrayIterator;
use System\FastApp;
use Traversable;

class Session
{
    protected static Session $instance;

    public static function getInstance(): Session
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    /**
     * Init a session
     */
    public function init(): void
    {
        session_name(FastApp::getInstance()->getConfig("session_id"));
        session_start();
    }

    /**
     * Destroy the session.
     */
    public static function destroy(): void
    {
        if (self::id()) {
            session_unset();
            session_destroy();
            session_write_close();
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 4200,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
        }
    }

    /**
     * Get or regenerate current session ID.
     *
     * @param bool $new
     *
     * @return string
     */
    public static function id(bool $new = false): string
    {
        if ($new && session_id()) {
            session_regenerate_id(true);
        }
        return session_id() ?: '';
    }

    /**
     * Set a flash data
     *
     * @param $key string
     * @param $message string|mixed
     */
    public function setFlash(string $key, mixed $message): void
    {
        $Flash = $this->get("flash_data");

        if (isset($Flash[$key])) {
            unset($Flash[$key]);
        }

        $data = array_merge(is_array($Flash) ? $Flash : [], [$key => $message]);

        $this->set("flash_data", $data);
    }

    /**
     * Get a session variable.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->exists($key)
            ? $_SESSION[$key]
            : $default;
    }

    /**
     * Check if a session variable is set.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Set a session variable.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set(string $key, mixed $value): static
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    /**
     * Get a flash data session
     *
     * @param $key string
     * @return null|string|mixed
     */
    public function getFlash(string $key): mixed
    {
        $flashData = $this->get("flash_data");
        $value = $flashData[$key] ?? null;
        if (isset($flashData[$key])) {
            unset($flashData[$key]);
            $this->set("flash_data", $flashData);
        }
        return $value;
    }

    /**
     * Merge values recursively.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function merge(string $key, mixed $value): static
    {
        if (is_array($value) && is_array($old = $this->get($key))) {
            $value = array_merge_recursive($old, $value);
        }
        return $this->set($key, $value);
    }

    /**
     * Clear all session variables.
     *
     * @return $this
     */
    public function clear(): static
    {
        $_SESSION = [];
        return $this;
    }

    /**
     * Magic method for get.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * Magic method for set.
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set(string $key, mixed $value)
    {
        $this->set($key, $value);
    }

    /**
     * Magic method for delete.
     *
     * @param string $key
     */
    public function __unset(string $key)
    {
        $this->delete($key);
    }

    /**
     * Delete a session variable.
     *
     * @param string $key
     *
     * @return $this
     */
    public function delete(string $key): static
    {
        if ($this->exists($key)) {
            unset($_SESSION[$key]);
        }
        return $this;
    }

    /**
     * Magic method for exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset(string $key)
    {
        return $this->exists($key);
    }

    /**
     * Count elements of an object.
     *
     * @return int
     */
    public function count(): int
    {
        return count($_SESSION);
    }

    /**
     * Retrieve an external Iterator.
     *
     * @return Traversable|ArrayIterator
     */
    public function getIterator(): Traversable|ArrayIterator
    {
        return new ArrayIterator($_SESSION);
    }

    /**
     * Whether an array offset exists.
     *
     * @param mixed $offset
     *
     * @return boolean
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->exists($offset);
    }

    /**
     * Retrieve value by offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Set a value by offset.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * Remove a value by offset.
     *
     * @param mixed $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->delete($offset);
    }
}