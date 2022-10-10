<?php

namespace Student\App\UnitTests\Container;

use PHPUnit\Framework\TestCase;
use Student\App\Container\DIContainer;
use Student\App\Exceptions\NotFoundException;
use Student\App\UnitTests\Container\ClassDependingOnAnother;
use Student\App\UnitTests\Container\SomeClassWithoutDependencies;
use Student\App\Repo\UserRepo\InMemoryUsersRepository;

class DIContainerTest extends TestCase 
{
    public function testItThrowsAnExceptionIfCannotResolveType(): void
    {
        $container = new DIContainer();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
        'Cannot resolve type: Student\App\UnitTests\Container\SomeClass'
        );
        
        $container->get(SomeClass::class);
    }

    public function testItResolvesClassWithoutDependencies(): void
    {
        $container = new DIContainer();

        $object = $container->get(SomeClassWithoutDependencies::class);

        $this->assertInstanceOf(
            SomeClassWithoutDependencies::class,
            $object
        );
    }

    public function testItResolvesClassByContract(): void
    {
        $container = new DIContainer();
        
        $container->bind(
            UsersRepositoryInterface::class,
            InMemoryUsersRepository::class
        );
        
        $object = $container->get(UsersRepositoryInterface::class);
       
        $this->assertInstanceOf(
            InMemoryUsersRepository::class,
            $object
        );
    }

    public function testItReturnsPredefinedObject(): void
    {
        $container = new DIContainer();
        
        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );
        
        $object = $container->get(SomeClassWithParameter::class);
        
        $this->assertInstanceOf(
            SomeClassWithParameter::class,
            $object
        );
       
        $this->assertSame(42, $object->value());
    }

    public function testItResolvesClassWithDependencies(): void
    {
        $container = new DIContainer();
        
        $container->bind(
        SomeClassWithParameter::class,
        new SomeClassWithParameter(42)
        );
        
        $object = $container->get(ClassDependingOnAnother::class);
        
        $this->assertInstanceOf(
        ClassDependingOnAnother::class,
        $object
        );
    }


}