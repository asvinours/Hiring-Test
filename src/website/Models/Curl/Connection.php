<?php
namespace SSENSE\HiringTest\Models\Curl;

/**
 * Class Connection
 * @package SSENSE\HiringTest\Models\Curl
 */
class Connection
{

    /**
     * @var resource
     */
    protected $curl = null;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var int
     */
    protected $errorNo;

    /**
     * @var string
     */
    protected $info;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0';

    /**
     * @var int|null The HTTP status of the last call, or null if none.
     */
    protected $httpStatus;

    /**
     * Connection constructor.
     */
    public function __construct()
    {
        $this->options = array(CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->userAgent);
    }

    /**
     * @param $option
     * @param $value
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return array|string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return !$this->errorNo;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return int
     */
    public function getErrorNo()
    {
        return $this->errorNo;
    }

    /**
     * @return int|null {@see $httpStatus}
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    /**
     * @param string $url
     * @param array $options
     *
     * @return bool|string
     */
    public function get($url, array $options = array())
    {
        $this->setOptions($this->mergeOptions(array(
            CURLOPT_URL => $url
        ), $options));

        $content = $this->exec();
        $this->close();

        return $content;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $this->mergeOptions($this->options, $options);
        return $this;
    }

    /**
     *
     * @param array $default_options
     * @param array $options
     *
     * @return array
     */
    private function mergeOptions(array $default_options, array $options)
    {
        $merged = $default_options;

        foreach ($options as $key => $value) {
            $merged[$key] = $value;
        }

        return $merged;
    }

    /**
     * @return boolean|string
     */
    public function exec()
    {
        $this->curl = \curl_init();
        $this->error = null;
        $this->info = null;

        \curl_setopt_array($this->curl, $this->options);

        return \curl_exec($this->curl);
    }

    /**
     * Close curl handle
     */
    public function close()
    {
        $this->error = \curl_error($this->curl);
        $this->info = \curl_getinfo($this->curl);
        $this->errorNo = \curl_errno($this->curl);
        $httpStatus = \curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->httpStatus = ($httpStatus !== false) ? (int)$httpStatus : null;

        \curl_close($this->curl);
    }

    /**
     * @param string $url
     * @param string $data
     * @param array $options
     *
     * @return bool|string
     */
    public function post($url, $data, array $options = array())
    {
        $this->setOptions($this->mergeOptions(array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data
        ), $options));

        $content = $this->exec();
        $this->close();

        return $content;
    }

    /**
     * @param string $url
     * @param string $pathToFile
     * @param array $data
     * @param array $options
     *
     * @return bool|string
     * @throws \Exception
     */
    public function postFile($url, $pathToFile, array $data, array $options = array())
    {
        if (!is_file($pathToFile)) {
            throw new \Exception('File not found at location: ' . $pathToFile);
        }

        $curlFile = new \CURLFile($pathToFile, mime_content_type($pathToFile), pathinfo($pathToFile, PATHINFO_BASENAME));

        $this->setOptions($this->mergeOptions(array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => array_merge(array('file' => $curlFile), $data)

        ), $options));

        $content = $this->exec();
        $this->close();

        return $content;
    }

    /**
     * @param string $url
     * @param string $data
     * @param array $options
     *
     * @return bool|string
     */
    public function put($url, $data, array $options = array())
    {
        $this->setOptions($this->mergeOptions(array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $data
        ), $options));

        $content = $this->exec();
        $this->close();

        return $content;
    }

    /**
     * @param string $url
     * @param string $data
     * @param array $options
     *
     * @return bool|string
     */
    public function delete($url, $data, array $options = array())
    {
        $this->setOptions($this->mergeOptions(array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS => $data
        ), $options));

        $content = $this->exec();
        $this->close();

        return $content;
    }
}
