<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('notification_push');
		$this->load->model('notification_send');
		$this->load->database();
		
		//$this->load->model('Notification_send');
		//$this->load->model('Notification_push');
		
		// for notification
	}
	
	// public function index()
	// {
		// //$osa="fl8e08rRM2U:APA91bG2xO8uHD1KJ2GTXe";
		// $topic='abdd';
		// //$topic='dWas0vYmwzQ:APA91bE5TDq0G4ng377KGVVQJLRJxk1Xu13ZOfofa0zOfAQZT9bs55dK4V1rNYCIA1Zfjc6vKQdi4MO8rMlTFzLUwPUDV0o8pzibV0fhHkySyoRqkGwl6IE1YE2USOQ5d78FCDj4mvnq';		
		// $t="first_message_n".$topic.'';
		// $this->notification_push->set('by_divce_group_ana_mehani_first_notiniction',$t,'eval');
		// $mPushNotification =$this->notification_push->getpush();
		// print_r($mPushNotification);
		// //$topic='mohammed1';
		// //$osama_t='fI8e08rRM2U:APA91bG2xO8uHD1kJ2GTXeS3PBJv4K6WlJBizb0NT9bwzVkDfpgtBGWMwhPYcyK2V1CHVOOYU8AsPUzhe561aX82Dy0O4tVMKV51ICMHUgmW7_9V_jcGPNVMKKmaB59QkCikat2SkOl8';
		
		// echo 'this is the result   '.$this->notification_send->send($topic,$mPushNotification,2);
	// }
	public function set_user_token()
	{
		if($this->check_authority())
		{
			$user_id = $this->input->get('user_id',TRUE);
			
			$user_token = $this->input->get('user_token',TRUE);
			
			$user_old_token   = $this->input->get('user_old_token',TRUE);
			
			$lang=$this->input->get('lang',TRUE);
			
			if( isset($user_id) && isset($user_token) && !empty($user_id) && !empty($user_token))
			{
				if(isset( $user_old_token )&&!empty( $user_old_token ))
				{
					$this->notification_send->update_user_token($user_id,$user_token,$user_old_token);
					$remove_result=$this->notification_send->remove_from_divce_group($user_id,$user_old_token);
					$add_result=$this->notification_send->subscribe_to_device_group($user_id,$user_token);
					
				}
				else
				{
					$this->notification_send->save_user_token($user_id,$user_token);
					$add_result=$this->notification_send->subscribe_to_device_group($user_id,$user_token);
					//echo 'dddff';
				}
				$user_notifications =$this->notification_send->get_user_notification($user_id);
				foreach($user_notifications as $notification)
				{
					//print_r($notification);
					if(isset( $lang )&&!empty( $lang ))
					{
							if($lang === 'ar')
							{
								$this->notification_push->set($notification->user_id,$notification->ar_message,$notification->ar_title,$notification->N_type_name);
							}
							else if($lang === 'en')
							{
								$this->notification_push->set($notification->user_id,$notification->en_message,$notification->en_title,$notification->N_type_name);
							}
						
						$mPushNotification =$this->notification_push->getpush();
						$result = $this->notification_send->send_to_divce_group($user_id,$mPushNotification);
						 //echo $result;
						//echo '<br><br>'.$result;
						$result_array=json_decode($result,true);
						if(isset($result_array['success'])&& $result_array['success']>=1)
						{
							$this->notification_send->delete_user_notification($notification->id);
							echo '{"msg":"success"}';
						}
						else
						{
							echo '{"msg":"failed"}';
						}
						
					}
					
					
					//$topic='mohammed1';
					//$osama_t='fI8e08rRM2U:APA91bG2xO8uHD1kJ2GTXeS3PBJv4K6WlJBizb0NT9bwzVkDfpgtBGWMwhPYcyK2V1CHVOOYU8AsPUzhe561aX82Dy0O4tVMKV51ICMHUgmW7_9V_jcGPNVMKKmaB59QkCikat2SkOl8';
					
					}
			}
		
        }
	}
	 private function check_user_authority()
    {
      $key = $this->input->get('akey',TRUE);

      $id  = $this->input->get('id',TRUE);

      if($key)
       {
          $user = $this->db->where('id',$id)->get('users')->row();

         if(count($user) == 1)
         {
           if(sha1($user->username) === $key)
           {
             return $arrayName = array('return' => TRUE,'data' => $user );
           }
           else
            return $arrayName = array('return' => FALSE,'data' => NULL );
         }
         
         else
         {
           return $arrayName = array('return' => FALSE,'data' => NULL );
         }
       }
       else
        {
          return $arrayName = array('return' => FALSE,'data' => NULL );
        }

    }
	// public function subscribe_to_topic()
	// {
		// $topic='abd';
		// //$topic='dWas0vYmwzQ:APA91bE5TDq0G4ng377KGVVQJLRJxk1Xu13ZOfofa0zOfAQZT9bs55dK4V1rNYCIA1Zfjc6vKQdi4MO8rMlTFzLUwPUDV0o8pzibV0fhHkySyoRqkGwl6IE1YE2USOQ5d78FCDj4mvnq';
		// $token='dWas0vYmwzQ:APA91bE5TDq0G4ng377KGVVQJLRJxk1Xu13ZOfofa0zOfAQZT9bs55dK4V1rNYCIA1Zfjc6vKQdi4MO8rMlTFzLUwPUDV0o8pzibV0fhHkySyoRqkGwl6IE1YE2USOQ5d78FCDj4mvnq';
		
		// echo $this->notification_send->subscribe_to_topic($topic,$token);
	// }
	
	// public function add_to_topic($token)
	// {
		
	// }
	// public function add_to_device_groub()
	// {
		// $osama_t='fI8e08rRM2U:APA91bG2xO8uHD1kJ2GTXeS3PBJv4K6WlJBizb0NT9bwzVkDfpgtBGWMwhPYcyK2V1CHVOOYU8AsPUzhe561aX82Dy0O4tVMKV51ICMHUgmW7_9V_jcGPNVMKKmaB59QkCikat2SkOl8';
		// $token='dWas0vYmwzQ:APA91bE5TDq0G4ng377KGVVQJLRJxk1Xu13ZOfofa0zOfAQZT9bs55dK4V1rNYCIA1Zfjc6vKQdi4MO8rMlTFzLUwPUDV0o8pzibV0fhHkySyoRqkGwl6IE1YE2USOQ5d78FCDj4mvnq';		
		// $this->notification_send->subscribe_to_device_group('abdd',$osama_t);
		
	// }
}