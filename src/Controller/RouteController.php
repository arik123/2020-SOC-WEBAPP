<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Route as myRoute;
use \DateTime;

class RouteController extends AbstractController
{
    /**
     * @Route("/route", name="route", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('route/index.html.twig', [
            'controller_name' => 'RouteController',
        ]);
    }
    /**
     * @Route("/route", methods={"POST"})
     */
    public function post(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        if($request->request->get("driver") == true) {
            $route = new myRoute();
            $dt = new DateTime( $request->request->get("kedy") );
            $route->setSource(preg_replace("/^LatLng\((-?\d+.?\d*?), (-?\d+.?\d*?)\)$/", "SRID=4326;POINT($2 $1)", $request->request->get("start")))
                ->setTarget(preg_replace("/^LatLng\((-?\d+.?\d*?), (-?\d+.?\d*?)\)$/", "SRID=4326;POINT($2 $1)", $request->request->get("end")))
                ->setDriver($this->getUser())
                ->setSeats($request->request->get("miesta"))
                ->setTime($dt)
                ->setZachadzka($request->request->get("zachadzka"))
            ;
            $errors = $validator->validate($route);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }
            $entityManager->persist($route);
            $entityManager->flush();

            $conn = $entityManager->getConnection();
            $sql = '
                UPDATE route as r
                SET way = (select routeBetweenPointsCar(
                    r.source,
                    r.target)
                )
                WHERE r.id = :id';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $route->getId()]);

            //TODO: FINISH THIS FUNCTION - VERIFY, SEND TO DB, process
            return new Response(
                "Start:" . $route->getSource()
                . " End" . $route->getTarget()
                . " Kedy" . $request->request->get("kedy")
                . " miesta" . $request->request->get("miesta")
                . " zachadzka" . $request->request->get("zachadzka")
                . " Driver: " . $request->request->get("driver")
            );
        } else {
            return new Response();
        }
        
    }

    /**
     * @Route("/route/list", name="my_routes")
     */
    public function myroute(): Response
    {
        return $this->render('route/index.html.twig', [
            'controller_name' => 'RouteController',
        ]);
    }
}
