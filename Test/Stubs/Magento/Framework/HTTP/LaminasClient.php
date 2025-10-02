<?php
declare(strict_types=1);

namespace Magento\Framework\HTTP;

use Laminas\Http\Response;

interface LaminasClient
{
    /**
     * Set URI
     *
     * @param string $uri
     * @return LaminasClient
     */
    public function setUri($uri);

    /**
     * Set options
     *
     * @param array $options
     * @return LaminasClient
     */
    public function setOptions(array $options);

    /**
     * Set headers
     *
     * @param array $headers
     * @return LaminasClient
     */
    public function setHeaders(array $headers);

    /**
     * Set HTTP method
     *
     * @param string $method
     * @return LaminasClient
     */
    public function setMethod($method);

    /**
     * Send HTTP request
     *
     * @return Response
     */
    public function send();
}
