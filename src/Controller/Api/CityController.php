<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.12.18
 * Time: 15:48
 */

namespace App\Controller\Api;


use App\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CityController
 * @package App\Controller\Api
 * @Route("/api/city")
 */

class CityController extends AbstractController
{

    /**
     * @Route("", name="api_city_list", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list()
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(City::class)->list();
        return $this->json($list);
    }
}