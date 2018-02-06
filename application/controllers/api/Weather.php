<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

class Weather extends REST_Controller {

    // Construct the parent class
    function __construct()
    {
        parent::__construct();
        $this->load->model('time_model');
        $this->load->model('weather_model');
    }

    public function weather_get()
    {
        $response['error'] = FALSE;
        $response['message'] = "";
        $response['data'] = $this->weather_model->get_weathers();
        $this->response($response, REST_Controller::HTTP_OK); 
    }

    public function weather_detail_get()
    {

        $date = $this->get('date');
        if ( ! empty($date))
        {
            $response['error'] = FALSE;
            $response['message'] = "";
            $response['data'] = $this->weather_model->get_weathers_detail($date);
            $this->response($response, REST_Controller::HTTP_OK); 
        }
        else
        {
            $response['error'] = TRUE;
            $response['message'] = "Empty date";
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); 
        }
    }

    public function weather_post()
    {
        $last_time = $this->time_model->get_last_time_submitted()->last_time;

        if ($last_time != date('H'))
        {
            $hour_now = date('H');

            $id_weather = $this->post('id_weather');
            $day = date('l');
            $date = date('Y-m-d');

            $time = $hour_now.':00:00';
            $time = date_create_from_format('H:i:s', "$time");
            $time =  date_format($time, 'H:i:s');

            $temperature = $this->post('temperature');
            $pressure = $this->post('pressure');
            $humidity = $this->post('humidity');

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

            if ($result)
            {
                $this->time_model->update_last_time_submitted(array('last_time' => $hour_now));
                $response['error'] = FALSE;
                $response['message'] = "";
                $this->response($response, REST_Controller::HTTP_CREATED);
            }
            else
            {
                $response['error'] = TRUE;
                $response['message'] = "Failed insert data";
                $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            $response['error'] = TRUE;
            $response['message'] = "Not time yet";
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }

}
