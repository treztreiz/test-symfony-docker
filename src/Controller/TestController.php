<?php

namespace App\Controller;

use App\Entity\Test;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $faker = Factory::create('fr_FR');
        $name = $faker->name();

        $test = new Test();
        $test->setName($name);

        $em->persist($test);
        $em->flush();

        return $this->json([
            'message' => 'A new test has been created',
            'path' => $name,
        ]);
    }

    #[Route('/tests', name: 'app_tests')]
    public function list(TestRepository $testRepository): JsonResponse
    {
        $tests = array_map(function ($t) {
            return $t->getName();
        }, $testRepository->findAll());

        return $this->json($tests);
    }
}
