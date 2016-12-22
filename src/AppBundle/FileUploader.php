<?php

namespace AppBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {

    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $filename = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->targetDir, $filename);

        return $filename;
    }

    /**
     * @return mixed
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}