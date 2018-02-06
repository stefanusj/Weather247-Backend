<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weather_model extends CI_MODEL
{
	function __construct()
	{
		$this->load->database();
		define('TABLE_NAME', 'detail_weather');
	}

	public function get_weathers()
	{
		$this->db->select("MAX(id) id, MAX(weather) weather, day, date, 
		DATE_FORMAT((SELECT time from detail_weather ORDER BY id DESC LIMIT 1), '%H:%i') as time, CEIL(avg(temperature)) temperature, CEIL(avg(pressure)) pressure, CEIL(avg(humidity)) humidity");
		$this->db->from(TABLE_NAME);
		$this->db->join('weather', 'weather.id_weather = detail_weather.id_weather');
		$this->db->order_by('id', 'DESC');
		$this->db->group_by('date');
		$this->db->limit(7,0);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_weathers_detail($date)
	{
		$this->db->select("id, weather, day, date, DATE_FORMAT(time, '%H:%i') as time, temperature, pressure, humidity");
		$this->db->from(TABLE_NAME);
		$this->db->join('weather', 'weather.id_weather = detail_weather.id_weather');
		$this->db->order_by('id', 'DESC');
		$this->db->where('date', $date);
		$query = $this->db->get();

		return $query->result();
	}

	public function store_weather($data)
	{
		$query = $this->db->insert(TABLE_NAME, $data);
		return $query;	
	}
}