<?php

namespace Upload;

class Exception extends \RuntimeException
{
    /**
     * @var FileInfoInterface
     */
    protected FileInfoInterface $fileInfo;

    /**
     * Constructor
     *
     * @param string $message The Exception message
     * @param FileInfoInterface|null $fileInfo The related file instance
     */
    public function __construct(string $message, FileInfoInterface $fileInfo = null)
    {
        $this->fileInfo = $fileInfo;
        parent::__construct($message);
    }

    /**
     * Get related file
     *
     * @return FileInfoInterface|null
     */
    public function getFileInfo(): ?FileInfoInterface
    {
        return $this->fileInfo;
    }
}
