<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\Product;
use AppBundle\FileUploader;

class PictureUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if(!$entity instanceof Product)
            return;

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if(!$entity instanceof Product)
            return;

        $filename = $args->getOldValue('picture');

        unlink($this->uploader->getTargetDir().'/'.$filename);

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof Product) {
            return;
        }

        $file = $entity->getPicture();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);
        $entity->setPicture($fileName);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if(!$entity instanceof Product)
            return;

        $filename = $entity->getPicture();

        if($filename != null)
            $entity->setPicture(new File($this->uploader->getTargetDir().'/'.$filename));
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if(!$entity instanceof Product)
            return;

        $filename = $entity->getPicture();

        if($filename != null)
            unlink($filename);
    }
}