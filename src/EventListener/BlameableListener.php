<?php

namespace App\EventListener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

class BlameableListener
{

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getDocument();
        $dm = $args->getDocumentManager();

        if (property_exists('createdBy')) {

        }
    }
}