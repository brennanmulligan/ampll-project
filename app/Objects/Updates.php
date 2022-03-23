<?php

namespace App\Objects;

class Updates
{
    private mixed $title;
    private mixed $type;
    private mixed $private;
    private mixed $authorized;

    /**
     * @param string|null $title - title of updated type
     * @param string|null $type - type of what is being updated
     * @param bool $private - always either true or false
     * @param bool $authorized - only given on de-authorization events which will be false, otherwise default to true
     */
    function __construct(string $title = null, string $type = null, bool $private = null, bool $authorized = true)
    {
        $this->title = $title;
        $this->type = $type;
        $this->private = $private;
        $this->authorized = $authorized;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function getPrivate(): mixed
    {
        return $this->private;
    }

    /**
     * @return bool
     */
    public function getAuthorized(): mixed
    {
        return $this->authorized;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param bool $private
     */
    public function setPrivate(bool $private): void
    {
        $this->private = $private;
    }

    /**
     * @param bool $authorized
     */
    public function setAuthorized(bool $authorized): void
    {
        $this->authorized = $authorized;
    }
}