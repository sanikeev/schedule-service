<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.12.18
 * Time: 15:48
 */

namespace App\Controller\Api;


use App\DTO\ArrivalDTO;
use App\DTO\CheckDTO;
use App\DTO\ScheduleDTO;
use App\Entity\Schedule;
use App\Form\ArrivalType;
use App\Form\CheckType;
use App\Form\ScheduleType;
use App\Service\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ScheduleController
 * @package App\Controller\Api
 * @Route("/api/schedule")
 */
class ScheduleController extends AbstractController
{

    /**
     * @Route("", name = "api_schedule_list", methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function list(Request $request)
    {
        $start = $request->get('start', '-30 days');
        $end = $request->get('end', 'now');
        $page = $request->get('page', 1);
        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);
        $em = $this->getDoctrine()->getManager();
        /** @var Schedule[] $list */
        $list = $em->getRepository(Schedule::class)->getByPeriod($startDate, $endDate, $page - 1);
        $result = [];
        foreach ($list['data'] as $item) {
            $result['data'][] = [
                'started_at' => $item->getStartedAt()->format('Y-m-d'),
                'ended_at' => $item->getEndedAt()->format('Y-m-d'),
                'courier' => $item->getCourier()->getName(),
                'city' => $item->getCity()->getName(),
            ];
        }
        $result['total'] = $list['total'];
        $result['totalPages'] = $list['totalPages'];
        return $this->json($result);
    }

    /**
     * @Route("", name="api_schedule_create", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request)
    {
        $form = $this->createForm(ScheduleType::class);
        $form->submit(json_decode($request->getContent(), true), true);

        if (!$form->isValid() && !$form->isSubmitted()) {
            return $this->json($form->getErrors(), 400);
        }

        /** @var ScheduleDTO $dto */
        $dto = $form->getData();
        $schedule = new Schedule();
        $schedule->setCourier($dto->getCourier());
        $schedule->setCity($dto->getCity());
        $schedule->setStartedAt($dto->getStartedAt());
        $scheduleService = new ScheduleService();
        $endedAt = $scheduleService->calcFinishDate($dto->getCity()->getTripDuration(), $dto->getStartedAt());
        $schedule->setEndedAt($endedAt);
        $em = $this->getDoctrine()->getManager();
        $em->persist($schedule);
        $em->flush();

        return $this->json([], 201);
    }

    /**
     * @Route("/arrival", name="api_schedule_arrival", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function arrival(Request $request)
    {

        $form = $this->createForm(ArrivalType::class);
        $form->submit(json_decode($request->getContent(), true), true);
        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->json($form->getErrors(), 400);
        }

        /** @var ArrivalDTO $dto */
        $dto = $form->getData();

        $scheduleService = new ScheduleService();

        $arrivalDate = $scheduleService->calcArrivalDate($dto->getCity(), $dto->getDate());

        return $this->json([
            'arrival_date' => $arrivalDate->format('Y-m-d'),
        ]);
    }

    /**
     * @Route("/check", name="api_schedule_check", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function check(Request $request)
    {
        $form = $this->createForm(CheckType::class);
        $form->submit(json_decode($request->getContent(), true), true);

        if (!$form->isValid() && !$form->isSubmitted()) {
            return $this->json($form->getErrors(), 400);
        }
        /** @var CheckDTO $dto */
        $dto = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $isBusy = $em->getRepository(Schedule::class)->isBusyCourier($dto->getCourier(), $dto->getDate());
        return $this->json(['check' => !empty($isBusy)]);
    }
}