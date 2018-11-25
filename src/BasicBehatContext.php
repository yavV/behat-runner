<?php

namespace YavV\BehatRunner;

use Behat\Behat\Context\Context;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 *
 *
 * @author  derksen mediaopt GmbH
 * @version ${VERSION}, ${REVISION}
 * @package Mediaopt\AutomatedTesting
 */
class BasicBehatContext implements Context
{
    /**
     * @var string
     */
    const HUB_URL = 'SELENIUM_HUB_URL';

    /**
     * @var string
     */
    const BASE_URL = 'TEST_BASE_URL';

    /**
     * @var string
     */
    const OPTIONS = 'TEST_OPTIONS';

    /**
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * @var \stdClass
     */
    protected $options;

    /**
     * @return RemoteWebDriver
     */
    public function getWebDriver()
    {
        if ($this->driver !== null) {
            return $this->driver;
        }

        $this->driver = $this->buildWebDriver();
        return $this->driver;
    }

    /**
     * @return RemoteWebDriver
     */
    protected function buildWebDriver()
    {
        return RemoteWebDriver::create(getenv(self::HUB_URL), $this->getCapabilities(), 5000, 0);
    }

    /**
     * @return DesiredCapabilities
     */
    protected function getCapabilities()
    {
        $browser = $this->getOption('browser');
        return method_exists(DesiredCapabilities::class, $browser)
            ? call_user_func([DesiredCapabilities::class, $browser])
            : null;
    }

    /**
     * @param string $name
     * @return string|null
     */
    protected function getOption($name)
    {
        return property_exists($this->getOptions(), $name)
            ? $this->getOptions()->{$name}
            : null;
    }

    /**
     * @return \stdClass
     */
    protected function getOptions()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $this->options = json_decode(getenv(self::OPTIONS) ?: '{}');
        return $this->options;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return getenv(self::BASE_URL);
    }
}
