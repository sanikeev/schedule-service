<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.12.18
 * Time: 15:48
 */

namespace App\Controller\Api;


use App\Entity\Courier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CourierController
 * @package App\Controller\Api
 * @Route("/api/courier")
 */

class CourierController extends AbstractController
{

    /**
     * @Route("", name="api_courier_list", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list()
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Courier::class)->list();
        return $this->json($list);
    }
}