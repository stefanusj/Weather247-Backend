<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time_model extends CI_MODEL
{
	function __construct()
	{
		$this->load->database();
	}

	public function get_last_time_submitted()
	{
		$query = $this->db->get('time');
		return $query->first_row();
	}

	public function update_last_time_submitted($time_now)
	{
		$this->db->update('time', $time_now);
	}

}