<?php

namespace Cyberway_Climatechange\Api;

use DateTime;

class Api_GlobalWarming extends Api
{


  public function getCo2()
  {

    $url = 'https://global-warming.org/api/co2-api';

    $response = $this->get($url);
    if (isset($response['body'])) {
      $formatted = json_decode($response['body'], true)['co2'] ?? [];
      foreach ($formatted as $obs) {
        $day = $obs['day'];
        $month = $obs['month'];
        $year = $obs['year'];
        /**
         *
         * 'date' => DateTime,
         * 'trend' => value,
         * 'cycle' => value
         *
         */
        $data[] = [
          'date' => DateTime::createFromFormat('d-m-Y', "$day-$month-$year"),
          'trend' => $obs['trend'],
          'cycle' => $obs['cycle']
        ];
      }
    }


    return $data;
  }


  public function getTemperature()
  {

    $url = 'https://global-warming.org/api/temperature-api';

    $response = $this->get($url);
    if (isset($response['body'])) {
      $formatted = json_decode($response['body'], true)['result'] ?? [];
      foreach ($formatted as $obs) {

        $time = $obs['time'];
        $station = $obs['station'];
        //$land = $obs['land'];
        /**
         *
         * 'date' => DateTime,
         * 'temperature' => value,
         *
         */
        $data[] = [
          'date' => $this->formatTemperatureTime($time),
          'temperature' => $station,
        ];
      }
    }

    return $data;
  }

  private function formatTemperatureTime($time)
  {
    $times = explode('.', $time);
    $year = $times[0];
    $monthNum = $times[1] ?? "04";
    $day = 1;

    switch ($monthNum) {
      case "04":
        $month = 1;
        break;
      case "13":
        $month = 2;
        break;
      case "21":
        $month = 3;
        break;
      case "29":
        $month = 4;
        break;
      case "38":
        $month = 5;
        break;
      case "46":
        $month = 6;
        break;
      case "54":
        $month = 7;
        break;
      case "63":
        $month = 8;
        break;
      case "71":
        $month = 9;
        break;
      case "79":
        $month = 10;
        break;
      case "88":
        $month = 11;
        break;
      case "96":
        $month = 12;
        break;
    }

    return DateTime::createFromFormat('d-m-Y', "$day-$month-$year");
  }
}