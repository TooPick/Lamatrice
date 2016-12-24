<?php

namespace AppBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Classe qui gère l'upload des images du site.
 * @package AppBundle
 */
class FileUploader {

    private $targetDir;

    /**
     * FileUploader constructor. Prends en paramètre le dossier de destination des images.
     * @param $targetDir
     */
    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * Sauvegarde du fichier depuis le dossier temporaire dans le dossier de destination.
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $filename = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->targetDir, $filename);

        return $filename;
    }

    /**
     * Retourne le dossier de destination.
     * @return mixed
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}