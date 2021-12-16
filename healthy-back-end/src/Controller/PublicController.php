<?php

namespace App\Controller;

use App\Entity\Recommendation;
use App\Repository\RecommendationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PublicController extends AbstractController
{
    /**
     * @Route("/", name="public")
     */
    public function index()
    {
        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }
     /**
     * @Route("/404", name="error404")
     */
    public function index404()
    {
        return $this->render('error404/index.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }
    /**
     * @Route("/send/rec", name="recommendation_req")
     * @Method({"GET", "POST"})

     */
    public function recommendationAction(Request $request, EntityManagerInterface $manager)
    {
        if ($request->isXMLHttpRequest()) {
            $request->get("name");
            /** @var Recommendation $rec */
            $rec = new Recommendation();
            $rec->setName($request->get("name"));
            $rec->setEmail($request->get("email"));
            $rec->setMessage($request->get("message"));
            $rec->setPhone($request->get("phone"));
            $rec->setRating($request->get("rating"));
            $rec->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($rec);
            $manager->flush();
            return new JsonResponse([
                'status' => 200,
                'response' => 'added'
            ]);
        }

        return new Response('This is not ajax!', 400);
    }
}
