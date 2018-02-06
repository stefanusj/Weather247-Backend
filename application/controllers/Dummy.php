<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dummy extends CI_Controller {

    // Construct the parent class
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('time_model');
        $this->load->model('weather_model');
    }

    public function index()
    {
        $last_time = $this->time_model->get_last_time_submitted()->last_time;

        if ($last_time != date('H'))
        {
            $hour_now = date('H');
            $this->time_model->update_last_time_submitted(array('last_time' => $hour_now));

            $id_weather = (date('d')%5)+1;
            $day = date('l');
            $date = date('Y-m-d');

            $time = $hour_now.':00:00';
            $time = date_create_from_format('H:i:s', "$time");
            $time =  date_format($time, 'H:i:s');

            $temperature = rand(30, 35);
            $pressure = rand(900, 1000);
            $humidity = rand(70,90);

            $data = array(
                'id_weather' => $id_weather,
                'day' => $day,
                'date' => $date,
                'time' => $time,
                'temperature' => $temperature,
                'pressure' => $pressure,
                'humidity' => $humidity
            );
            $result = $this->weather_model->store_weather($data);
        }
        redirect('');
    }
}
