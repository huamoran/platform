<?php

namespace Oro\Bundle\EntityBundle\Tests\Unit\ORM\Repository;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityRepository;

use Oro\Bundle\EntityBundle\ORM\Repository\EntityRepositoryFactory;
use Oro\Bundle\EntityBundle\Tests\Unit\ORM\Stub\TestEntityRepository;

class EntityRepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->getMock(ContainerInterface::class);
    }

    public function testGetDefaultRepositoryNoManagerNoRepositoryClass()
    {
        $entityName = 'TestEntity';

        $classMetadata = new ClassMetadata($entityName);
        $classMetadata->customRepositoryClassName = null;

        $doctrineConfiguration = new Configuration();
        $doctrineConfiguration->setDefaultRepositoryClassName(TestEntityRepository::class);

        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($entityName)
            ->willReturn($classMetadata);
        $entityManager->expects($this->any())
            ->method('getConfiguration')
            ->willReturn($doctrineConfiguration);

        $managerRegistry = $this->getMock(ManagerRegistry::class);
        $managerRegistry->expects($this->any())
            ->method('getManagerForClass')
            ->with($entityName)
            ->willReturn($entityManager);

        $this->container->expects($this->any())
            ->method('get')
            ->with('doctrine')
            ->willReturn($managerRegistry);

        $repositoryFactory = new EntityRepositoryFactory($this->container, []);
        /** @var TestEntityRepository $defaultRepository */
        $defaultRepository = $repositoryFactory->getDefaultRepository($entityName);

        $this->assertInstanceOf(TestEntityRepository::class, $defaultRepository);
        $this->assertEquals($entityName, $defaultRepository->getClassName());
        $this->assertAttributeEquals($entityManager, '_em', $defaultRepository);
        $this->assertAttributeEquals($classMetadata, '_class', $defaultRepository);
    }

    public function testGetDefaultRepositoryWithManagerWithRepositoryClass()
    {
        $entityName = 'TestEntity';
        $repositoryClass = TestEntityRepository::class;

        $classMetadata = new ClassMetadata($entityName);
        $classMetadata->customRepositoryClassName = null;

        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($entityName)
            ->willReturn($classMetadata);

        $repositoryFactory = new EntityRepositoryFactory($this->container, []);
        /** @var TestEntityRepository $defaultRepository */
        $defaultRepository = $repositoryFactory->getDefaultRepository($entityName, $repositoryClass, $entityManager);

        $this->assertInstanceOf($repositoryClass, $defaultRepository);
        $this->assertEquals($entityName, $defaultRepository->getClassName());
        $this->assertAttributeEquals($entityManager, '_em', $defaultRepository);
        $this->assertAttributeEquals($classMetadata, '_class', $defaultRepository);
    }

    /**
     * @expectedException \Oro\Bundle\EntityBundle\Exception\NotManageableEntityException
     * @expectedExceptionMessage Entity class "TestEntity" is not manageable.
     */
    public function testGetDefaultRepositoryNotManageableEntity()
    {
        $entityName = 'TestEntity';

        $managerRegistry = $this->getMock(ManagerRegistry::class);
        $managerRegistry->expects($this->any())
            ->method('getManagerForClass')
            ->with($entityName)
            ->willReturn(null);

        $this->container->expects($this->once())
            ->method('get')
            ->with('doctrine')
            ->willReturn($managerRegistry);

        $repositoryFactory = new EntityRepositoryFactory($this->container, []);
        $repositoryFactory->getDefaultRepository($entityName);
    }

    public function testGetRepositoryFromContainer()
    {
        $entityName = 'TestEntity';
        $repositoryService = 'test.entity.repository';

        $entityRepository = $this->getMockBuilder(TestEntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $classMetadata = new ClassMetadata($entityName);
        $classMetadata->customRepositoryClassName = TestEntityRepository::class;

        /** @var EntityManager|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($entityName)
            ->willReturn($classMetadata);

        $this->container->expects($this->once())
            ->method('get')
            ->with($repositoryService)
            ->willReturn($entityRepository);

        $repositoryFactory = new EntityRepositoryFactory($this->container, [$entityName => $repositoryService]);

        // double check is used to make sure that object is stored in the internal cache
        $this->assertEquals($entityRepository, $repositoryFactory->getRepository($entityManager, $entityName));
        $this->assertEquals($entityRepository, $repositoryFactory->getRepository($entityManager, $entityName));
    }

    public function testGetRepositoryDefaultRepository()
    {
        $entityName = 'TestEntity';

        $classMetadata = new ClassMetadata($entityName);
        $classMetadata->customRepositoryClassName = TestEntityRepository::class;

        /** @var EntityManager|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->exactly(4))
            ->method('getClassMetadata')
            ->with($entityName)
            ->willReturn($classMetadata);

        $this->container->expects($this->never())
            ->method('get');

        $repositoryFactory = new EntityRepositoryFactory($this->container, []);

        // double check is used to make sure that object is stored in the internal cache
        $repository = $repositoryFactory->getRepository($entityManager, $entityName);
        $this->assertEquals($repository, $repositoryFactory->getRepository($entityManager, $entityName));

        $this->assertInstanceOf(TestEntityRepository::class, $repository);
        $this->assertEquals($entityName, $repository->getClassName());
        $this->assertAttributeEquals($entityManager, '_em', $repository);
        $this->assertAttributeEquals($classMetadata, '_class', $repository);
    }

    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\LogicException
     * @expectedExceptionMessage Repository for class TestEntity must be instance of EntityRepository
     */
    public function testGetRepositoryFromContainerNotEntityRepository()
    {
        $entityName = 'TestEntity';
        $repositoryService = 'test.entity.repository';

        $classMetadata = new ClassMetadata($entityName);
        $classMetadata->customRepositoryClassName = TestEntityRepository::class;

        /** @var EntityManager|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($entityName)
            ->willReturn($classMetadata);

        $entityRepository = new \stdClass();

        $this->container->expects($this->once())
            ->method('get')
            ->with($repositoryService)
            ->willReturn($entityRepository);

        $repositoryFactory = new EntityRepositoryFactory($this->container, [$entityName => $repositoryService]);
        $repositoryFactory->getRepository($entityManager, $entityName);
    }

    // @codingStandardsIgnoreStart
    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\LogicException
     * @expectedExceptionMessage Repository for class TestEntity must be instance of Oro\Bundle\EntityBundle\Tests\Unit\ORM\Stub\TestEntityRepository
     */
    // @codingStandardIgnoreEnd
    public function testGetRepositoryFromContainerInvalidEntityRepository()
    {
        $entityName = 'TestEntity';
        $repositoryService = 'test.entity.repository';

        $classMetadata = new ClassMetadata($entityName);
        $classMetadata->customRepositoryClassName = TestEntityRepository::class;

        /** @var EntityManager|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($entityName)
            ->willReturn($classMetadata);

        $entityRepository = new EntityRepository($entityManager, $classMetadata);

        $this->container->expects($this->once())
            ->method('get')
            ->with($repositoryService)
            ->willReturn($entityRepository);

        $repositoryFactory = new EntityRepositoryFactory($this->container, [$entityName => $repositoryService]);
        $repositoryFactory->getRepository($entityManager, $entityName);
    }
}
