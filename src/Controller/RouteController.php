<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
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
                ->setRepeat($request->request->get("repeat"))
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

            
            return new Response(
                "Start:" . $route->getSource()
                . " End" . $route->getTarget()
                . " Kedy" . $request->request->get("kedy")
                . " miesta" . $request->request->get("miesta")
                . " zachadzka" . $request->request->get("zachadzka")
                . " Driver: " . $request->request->get("driver")
            );
        } else {
            $conn = $entityManager->getConnection();
            $sql = '
                select * from matchRoute(ST_GeomFromEWKT(\'' . preg_replace("/^LatLng\((-?\d+.?\d*?), (-?\d+.?\d*?)\)$/", "SRID=4326;POINT($2 $1)", $request->request->get("start")) . '\')::geography, 
                ST_GeomFromEWKT(\'' . preg_replace("/^LatLng\((-?\d+.?\d*?), (-?\d+.?\d*?)\)$/", "SRID=4326;POINT($2 $1)", $request->request->get("end")) . '\')::geography,
                timestamp \'' . $request->request->get("kedy") . '\',
                ' . $request->request->get("repeat") . '
            );';
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $route_ids = $stmt->fetchAllAssociative();
            if(count($route_ids) == 0) {
                return $this->render('message.html.twig', [
                    'message' => "nič sme nenašli :("
                ]);
            }
            array_map(function($value) {
                return $value["id"];
            }, $route_ids);

            $query = $entityManager->createQuery(
                'SELECT r
                FROM App\Entity\Route r
                WHERE r.id in (:route_ids)
                AND r.driver != :user'
            )->setParameter('route_ids', $route_ids)
            ->setParameter('user', $this->getUser());

            $routes = $query->getResult();
            if(count($routes) == 0) {
                return $this->render('message.html.twig', [
                    'message' => "nič sme nenašli :("
                ]);
            }
            return $this->render('route/list.html.twig', [
                'routes' => $routes,
                'joinRoute' => true
            ]);
        }
        
    }

    /**
     * @Route("/route/list", name="my_routes")
     * @return Response
     */
    public function myRoutes(Request $request): Response
    {

        $routes = new ArrayCollection(
            array_merge($this->getUser()->getDriver()->toArray(), $this->getUser()->getPassenger()->toArray())
        );
        if($request->query->get("json") == true) {
            //return $this->json($routes); TODO FINISH SERIALIZATION and use it in calendar
        }
        return $this->render('route/list.html.twig', [
            'routes' => $routes,
            'joinRoute' => false
        ]);

    }

    /**
     * @Route("/route/join/{id}", name="route_join")
     */
    public function joinRoute(int $id, EntityManagerInterface $entityManager): Response
    {
		$repository = $this->getDoctrine()->getRepository(myRoute::class);

        $route = $repository->find($id);
        if($route->getSeats() > 0 && !$route->getPassenger()->contains($this->getUser())) {
            $route->addPassenger($this->getUser());
            $route->setSeats($route->getSeats()-1);
            $entityManager->persist($route);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
            return $this->render('message.html.twig', [
                'message' => "pridanie k jazde bolo úspešné"
            ]);
        } else {
            return $this->render('message.html.twig', [
                'message' => "v jazde nieje dosť miest"
            ]);
        }
    }

    /**
     * @Route("/route/cancel/{id}", name="route_cancel")
     */
    public function cancelRoute(int $id, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // TODO EMAIL NOTIFICATIONS
        $repository = $this->getDoctrine()->getRepository(myRoute::class);

        $route = $repository->find($id);
        if($route->getDriver() == $this->getUser()) {
            $addresses = $route->getPassenger()->toArray();
            $addresses = array_map(function($value) {
                return $value->getEmail();
            }, $addresses);
            foreach($addresses as $address) {
                $email = (new TemplatedEmail())
                    ->from('cspolocne@gmail.com')
                    ->to($address)
                    //->cc('cc@example.com')
                    ->bcc('cspolocne@gmail.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Time for Symfony Mailer!')
                    ->text('Sending emails is fun again!')
                    ->htmlTemplate('email/cancel.html.twig')
                    ->context(['row' => $route]);;

                $mailer->send($email);
            }

            $entityManager->remove($route);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();


            return $this->render('message.html.twig', [
                'message' => "jazda uspesne zrusena"
            ]);
        } else if ($route->getPassenger()->contains($this->getUser())) {
            $route->removePassenger($this->getUser());
            $route->setSeats($route->getSeats()+1);
            $entityManager->persist($route);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
            return $this->render('message.html.twig', [
                'message' => "uspesne odstraneny z jazdy"
            ]);
        }
    }
    /**
     * @Route("/route/info/{id}", name="route_info")
     */
    public function routeInfo(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(myRoute::class);

        $route = $repository->find($id);
        if($route->getDriver() == $this->getUser()) {
            return $this->render('route/route_info.html.twig', [
                'users' => $route->getPassenger()
            ]);
        } else {
            return new Response('Detaily dostupne iba k tvojim jazdam', 401);
        }
    }

    //TODO: REMOVE passanger from route
}
