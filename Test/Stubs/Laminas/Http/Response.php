<?php
declare(strict_types=1);

namespace Laminas\Http;

interface Response
{
    /**
     * Get response body
     *
     * @return string
     */
    public function getBody();

    /**
     * Get response status code
     *
     * @return int
     */
    public function getStatusCode();
}
