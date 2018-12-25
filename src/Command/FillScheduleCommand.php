<?php

namespace App\Command;

use App\Entity\City;
use App\Entity\Courier;
use App\Entity\Schedule;
use App\Service\ScheduleService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FillScheduleCommand extends Command
{
    protected static $defaultName = 'schedule:fill';
    protected $conn;

    public function __construct(Connection $conn)
    {
        parent::__construct();
        $this->conn = $conn;
    }

    protected function configure()
    {
        $this
            ->setDescription('Заполняет расписание с 2015 года');
    }

    /**
     * Тут специально использовал прямые sql запросы для того чтобы была максимальная производительность скрипта
     * и он не падал от переполнения стека при создании кучи объектов
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $startDate = new \DateTimeImmutable('2015-01-01');
        $endDate = new \DateTimeImmutable();
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($startDate, $interval, $endDate);

        $couriers = $this->conn->executeQuery("SELECT * FROM courier")->fetchAll();
        $cities = $this->conn->executeQuery("SELECT * FROM city")->fetchAll();;


        /**
         * хотя здесь используются вложенные циклы - это наиболее оптимальный вариант по производительности
         */
        foreach ($couriers as $courier) {
            shuffle($cities);
            $result = $this->generateSchedule($courier, $cities, $period);
            $io->text($courier['name']);
            $this->conn->exec(implode('', $result['insertSql']));
        }

        $io->success('Success');
    }

    /**
     * @param $courier
     * @param $cities
     * @param \DatePeriod $period
     * @param null $params
     * @return array
     */
    protected function generateSchedule($courier, $cities, $period, $params = null)
    {
        $scheduleService = new ScheduleService();
        $sql = "INSERT INTO schedule(courier_id, city_id, started_at, ended_at) VALUES('%s', '%s', '%s', '%s');";

        $schedule = [];
        $insertSql = [];
        if ($params) {
            $schedule = $params['schedule'];
            $insertSql = $params['insertSql'];
        }

        foreach ($cities as $city) {
            /** @var \DateTime $date */
            $currDate = $this->nextStartedAt($schedule, $period);
            if (empty($currDate)) {
                return [
                    'schedule' => $schedule,
                    'insertSql' => $insertSql,
                ];
            }
            $params = [
                'courier_id' => $courier['id'],
                'city_id' => $city['id'],
                'started_at' => $currDate->format('Y-m-d'),
                'ended_at' => $scheduleService->calcFinishDate($city['trip_duration'], $currDate)->format('Y-m-d')
            ];
            $schedule[] = $params;
            $insertSql[] = vsprintf($sql, $params);

        }
        if (end($schedule)['ended_at'] < $period->end->format('Y-m-d')) {
            return $this->generateSchedule($courier, $cities, $period,[
                'schedule' => $schedule,
                'insertSql' => $insertSql
            ]);
        }

        return [
            'schedule' => $schedule,
            'insertSql' => $insertSql,
        ];
    }

    protected function nextStartedAt($schedule, $period)
    {
        $currDate = null;
        foreach ($period as $date) {
            if (!empty($schedule) && $date->format('Y-m-d') > end($schedule)['ended_at']) {
                $currDate = $date;
                break;
            }
            if (empty($schedule)) {
                $currDate = $date;
                break;
            }
        }
        return $currDate;
    }

}
