<?php


namespace App\Tests\Repository;


use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    use FixturesTrait;

    public function testCount()
    {
        self::bootKernel();
        $this->loadFixtures([UserFixtures::class]);
        $users = self::$container->get(UserRepository::class)->count([]);

        $this->assertEquals(10,$users);
    }

    public function testCountWithAlice()
    {
        self::bootKernel();
        $this->loadFixtureFiles([dirname(__DIR__) . '/Fixtures/UserReposittoryFixtures.yaml']);

        $users = self::$container->get(UserRepository::class)->count([]);

        $this->assertEquals(10,$users);
    }
}