<?php
namespace Oro\Bundle\FlexibleEntityBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\FlexibleEntityBundle\Model\Behavior\TimestampableInterface;
use Oro\Bundle\FlexibleEntityBundle\Model\AbstractFlexible;

/**
 * Aims to add timestambable behavior
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
class TimestampableListener implements EventSubscriber
{
    /**
     * Specifies the list of events to listen
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate'
        );
    }

    /**
     * Before insert
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $om     = $args->getEntityManager();

        if ($entity instanceof \Oro\Bundle\FlexibleEntityBundle\Entity\Mapping\AbstractEntityFlexibleValue) {
            $flexible = $entity->getEntity();
            $this->updateFlexibleFields($om, $flexible, array('created', 'updated'));
        }

        if ($entity instanceof TimestampableInterface) {
            $entity->setCreated(new \DateTime('now', new \DateTimeZone('UTC')));
            $entity->setUpdated(new \DateTime('now', new \DateTimeZone('UTC')));
        }
    }

    /**
     * Before update
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $om     = $args->getEntityManager();

        if ($entity instanceof \Oro\Bundle\FlexibleEntityBundle\Entity\Mapping\AbstractEntityFlexibleValue) {
            $flexible = $entity->getEntity();
            $this->updateFlexibleFields($om, $flexible, array('updated'));
        }

        if ($entity instanceof \Oro\Bundle\FlexibleEntityBundle\Model\Behavior\TimestampableInterface) {
            $entity->setUpdated(new \DateTime('now', new \DateTimeZone('UTC')));
        }
    }

    /**
     * Update flexible fields when a value is updated
     *
     * @param ObjectManager $om
     * @param Flexible      $flexible
     * @param array         $fields
     */
    protected function updateFlexibleFields(ObjectManager $om, AbstractFlexible $flexible, $fields)
    {
        if ($flexible !== null) {
            $meta = $om->getClassMetadata(get_class($flexible));
            $uow  = $om->getUnitOfWork();
            $now  = new \DateTime('now', new \DateTimeZone('UTC'));
            $changes = array();
            foreach ($fields as $field) {
                $changes[$field]= array(null, $now);
            }
            $uow->scheduleExtraUpdate($flexible, $changes);
        }
    }
}
