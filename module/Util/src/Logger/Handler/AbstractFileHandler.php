<?php

namespace Util\Logger\Handler;

use Util\Logger\Message\ErrorMessage;
use Util\Logger\MessageLogger;

/**
 * Class AbstractFileHandler
 *
 * @package Util\Logger\Handler
 */
abstract class AbstractFileHandler extends AbstractHandler
{
    const DEFAULT_NAME_PATTERN = 'logfile_*.json';
    const DEFAULT_CHMOD        = 0666;
    const DEFAULT_LIMIT        = 1;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $namePattern;

    /**
     * @var int
     */
    private $chmod;

    /**
     * @var int
     */
    private $limit;

    /**
     * @param array $config
     */
    public function init(array $config = [])
    {
        parent::init($config);

        if (!isset($config['path'])) {
            $exception = new \LogicException('Log file path must be provided.', 1);
            MessageLogger::getInstance()->pushMessage(new ErrorMessage($exception));
        }

        $this->path        = $config['path'];
        $this->namePattern = isset($config['namePattern']) ? $config['namePattern'] : self::DEFAULT_NAME_PATTERN;
        $this->chmod       = isset($config['chmod']) ? $config['chmod'] : self::DEFAULT_CHMOD;
        $this->limit       = isset($config['limit']) ? $config['limit'] : self::DEFAULT_LIMIT;
    }

    /**
     * @param string $contentString
     */
    protected function putContent($contentString)
    {
        $file = $this->getCurrentFile();
        if (true === is_dir($file)) {
            $exception = new \LogicException('Set valid logfile name.', 1);
            MessageLogger::getInstance()->pushMessage(new ErrorMessage($exception));
        }

        $fileExistance = file_exists($file);
        if (false === $fileExistance) {
            $this->rotateFile();
        }

        file_put_contents($file, (string)$contentString, FILE_APPEND | LOCK_EX);

        if (false === $fileExistance) {
            chmod($file, $this->chmod);
        }
    }

    /**
     * Delete if log files count limit overflowed
     */
    private function rotateFile()
    {
        $oldFiles = $this->getOldFiles();
        if (count($oldFiles) >= $this->limit) {
            $this->deleteOverflow($oldFiles);
        }
    }

    /**
     * @return string
     */
    private function getCurrentFile()
    {
        $dateString = (new \DateTime())->format('Y-m-d');
        $datedName  = sprintf($this->namePattern, $dateString);

        $trimmedPath = trim($this->path, '/');

        return sprintf('/%s/%s', $trimmedPath, $datedName);
    }

    /**
     * @param array $files
     */
    private function deleteOverflow(array $files)
    {
        foreach ($files as $key => $file) {
            if (count($files) < $this->limit) {
                break;
            }

            if (false === is_writable($file) && false === is_file($file)) {
                continue;
            }

            unlink($file);
            unset($files[$key]);
        }
    }

    /**
     * @return array
     */
    private function getOldFiles()
    {
        $pattern = $this->getGlobPattern();
        return glob($pattern);
    }

    /**
     * @return string
     */
    private function getGlobPattern()
    {
        $searchPattern = sprintf($this->namePattern, '*');
        $trimmedPath   = trim($this->path, '/');

        return sprintf('/%s/%s', $trimmedPath, $searchPattern);
    }
}
