<?php

namespace Commcloud\VoiceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
//use Services_Twilio;
//use Services_Twilio_Capability;
//use TaskRouter_Services_Twilio;
use Services_Twilio_Twiml;
use Services_Twilio_Auth_IpMessagingGrant;
use Services_Twilio_AccessToken;

class AgentController extends Controller
{
	/**
     * @Route("/agentindex", name="agent_index")
     */
    public function indexAction($name)
    {
        return $this->render('CommcloudVoiceBundle:Default:index.html.twig', array('name' => $name));
    }
    
    /**
     * @Route("/app/content", name="commcloud_voice_welcome")
     */
    public function welcomeAction()
    {
    	if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
    		
    		return $this->render('CommcloudVoiceBundle:Admin:configureproject.html.twig');
    	}
        else if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISOR')){
        	
        	return $this->render('CommcloudVoiceBundle:Supervisor:index.html.twig');
        }
        else  if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
       	
       		return $this->render('CommcloudVoiceBundle:Agent:content.html.twig');
		}
        //return new Response("Hello Vinit Dave");
        //return $this->render('default/index.html.twig');
    }
    
    /**
     * @Route("/token", name="commcloud_voice_token")
     */
	public function tokenAction()
	{
		//include_once $this->get('kernel')->getRootDir().'/../vendor/twilio/sdk/Services/Twilio/CapabilityTaskRouter.php';
		//echo 'hello ashwin';
		//require __DIR__.'/../vendor/twilio/sdk/Services/Twilio/CapabilityTaskRouter.php';
		//$accountSid = 'ACb4883ed14aeeb6b36b1b06b379264544';
		//$authToken = '2376daf17874639a98ad0e3ead2698d2';
		//$workerspaceSid = 'WS6a4a9866db792b821c8562e16558ace1';
		//$workerSid = 'WK46ab0572b3548ce888a00e1418a1ff1a';
		
	//	$twilio = new Services_Twilio($accountSid, $authToken);
	//	$capability = new Services_Twilio_Capability($accountSid, $authToken);
	//	$capability->allowClientOutgoing('AP2fb4db26e2c87f98124d9dbc8e21342c');
	//	$capability->allowClientIncoming('jenny');
	//	$token = $capability->generateToken(28800);	
		
	//	$worker_capability = new  \Services_Twilio_TaskRouter_Worker_Capability($accountSid, $authToken, $workerspaceSid, $workerSid);
		
		//$worker_capability = new Services_Twilio_Capability($accountSid, $authToken, $workerspaceSid, $workerSid);
	//	$worker_capability->allowActivityUpdates();
	//	$worker_capability->allowReservationUpdates();
	//	$worker_token = $worker_capability->generateToken(28800);
		//return new JsonResponse(array('message' => 'Success!'), 200);
	//	return new JsonResponse(array(['token' => $token,'workerToken' =>  $worker_token]), 200);
		//$twilioCapability = new \Services_Twilio_TaskRouter_Worker_Capability($this->getParameter('sid'), $this->getParameter('authToken'), $this->getParameter('workspaceSid'), $this->get('security.token_storage')->getToken()->getUser()->getWsid());
		//Commented the line 66 on Feb 22, 2016 as the twilio_task_router_worker_capability_wrapper is statically creating the instance based on the workersid mentioned in the co
		$twilioCapability = $this->get('commcloud_twilio_wrapper.twilio_capability');
		$twilioCapability->allowClientIncoming($this->get('security.token_storage')->getToken()->getUser()->getWloginid());
		$twilioCapability->allowClientOutgoing('APfd14dd8ac4b0b5f55b7f3faa375035df');
		$twilioToken = $twilioCapability->generateToken(28800);
		
		$twilioWorker = $this->get('commcloud_twilio_wrapper.twilio_taskrouter_worker_capability');
		$workerCapability = $twilioWorker->createInstance($this->getParameter('sid'), $this->getParameter('authToken'), $this->getParameter('workspaceSid'), 'WK33610027409c289bfd25786c200d0511');
		$workerCapability->allowActivityUpdates();
		$workerCapability->allowReservationUpdates();
		$workerToken = $workerCapability->generateToken(28800);
		//return new Response(var_dump($this));
		return new JsonResponse(array(['token' => $twilioToken,'workerToken' =>  $workerToken]), 200);
	}
	
	/**
     * @Route("/assginmentcallback", name="commcloud_voice_assignmentcallback")
     */
	public function assginmentcallbackAction(){
		
		$assignment_instruction = [
		  'instruction' => 'dequeue',
		  'from' => '+17378742833',
		];
		$response = new Response(json_encode($assignment_instruction));
		$response->headers->set('Content-Type', 'application/json'); 
		return $response;
		//echo json_encode($assignment_instruction);
		
	}
	
	/**
     * @Route("/app/calls/list", name="commcloud_voice_calllist")
     */
	public function calllistAction(){
		
		//	$accountSid = 'ACb4883ed14aeeb6b36b1b06b379264544';
		//	$authToken = '2376daf17874639a98ad0e3ead2698d2';
			$twilio = $this->get('commcloud_twilio_wrapper.twilio_api');
		 //	$twilio = new Services_Twilio($accountSid, $authToken);
		 	$calls = $twilio->account->calls->getIterator(0, 50, array('Status' => 'in-progress')); 
		 	return $this->render('CommcloudVoiceBundle:Agent:call_list.html.twig', array('calls' => $calls));
	
			
			//$calls = $twilio->account->calls->getIterator(0, 50, array('Status' => 'in-progress')); 
	}
	
	/**
     * @Route("/app/calls/history", name="commcloud_voice_callhistory")
     */
	public function callhistoryAction(){
			//$accountSid = 'ACb4883ed14aeeb6b36b1b06b379264544';
		//	$authToken = '2376daf17874639a98ad0e3ead2698d2';
		 //	$twilio = new Services_Twilio($accountSid, $authToken);
		 	$twilio = $this->get('commcloud_twilio_wrapper.twilio_api');
		 	$calls = $twilio->account->calls->getIterator(0, 50, array());
		 	
		 	return $this->render('CommcloudVoiceBundle:Agent:callhistory.html.twig', array('calls' => $calls));
	}
	/**
     * @Route("/app/agent/list", name="commcloud_voice_agentlist")
     */
	public function agentlistAction(){
		//	$accountSid = 'ACb4883ed14aeeb6b36b1b06b379264544';
		//	$authToken = '2376daf17874639a98ad0e3ead2698d2';
		//	$workspaceSid = 'WS6a4a9866db792b821c8562e16558ace1';
		 //	$twilio = new Services_Twilio($accountSid, $authToken);
		 	$taskrouterClient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
		 //	var_dump($taskrouterclient->workspace->workers);
		 //	$taskrouterclient = new TaskRouter_Services_Twilio($accountSid, $authToken, $workspaceSid);
		 	return $this->render('CommcloudVoiceBundle:Agent:agentlist.html.twig', array('workers' => $taskrouterClient->workspace->workers));
	}
	/**
     * @Route("/app/call/recording", name="commcloud_voice_callrecording")
     */
	public function callrecordingAction(){
		//	$accountSid = 'ACb4883ed14aeeb6b36b1b06b379264544';
		//	$authToken = '2376daf17874639a98ad0e3ead2698d2';
			$twilio = $this->get('commcloud_twilio_wrapper.twilio_api');
		// 	$twilio = new Services_Twilio($accountSid, $authToken);
			$calls = $twilio->account->recordings;
			//$from = array();
		//	$to = array();
		//	foreach ($calls as $call) {
		//	    $from = $twilio->account->calls->get($call->call_sid)->from;
		//		$to = $twilio->account->calls->get($call->call_sid)->to;
		//	};
			
			//var_dump('from: ' .$from);
		//	var_dump(array('calls' => $calls));
			return $this->render('CommcloudVoiceBundle:Agent:callrecording.html.twig', array('calls' => $calls));
	}
	/**
     * @Route("/app/call/recording/from_to", name="commcloud_voice_getfromtoofcallrecording")
     */
	public function getfromtoofcallrecordingAction($call_sid){
		 $twilio = $this->get('commcloud_twilio_wrapper.twilio_api');
		 //$filterArray = $request->request->all();
		// var_dump($filterArray);
		 $from = $twilio->account->calls->get($call_sid)->from;
		 $to = $twilio->account->calls->get($call_sid)->to;
		 
		 //return array('from1' => $from, 'to' => $to);
		 return new Response('<td>'. $from .'</td><td>'. $to .'</td>');
	}

	/**
     * @Route("/ivr", name="commcloud_voice_ivr")
     */
	public function ivrAction()
    {       			
		define('workflowSid', 'WW61fcc3b0e481ddfa7610cd68b78e550a');
		
		$client = new Services_Twilio_Twiml;
		//$response->enqueue(array( workflowSid => 'WW61fcc3b0e481ddfa7610cd68b78e550a'));
		$client->say('hello ashwin');
		$client->enqueue(array('workflowSid' => workflowSid))->task('{"selected_langauge" : "es"}');		
        //return $response;
		return new Response($client);
    }
	/**
     * @Route("/greeting", name="commcloud_voice_greeting")
     */
    public function greetingAction()
    {       			
		define('workflowSid', 'WW61fcc3b0e481ddfa7610cd68b78e550a');
		
		$client = new Services_Twilio_Twiml;
		//$response->enqueue(array( workflowSid => 'WW61fcc3b0e481ddfa7610cd68b78e550a'));
		$client->say('Hello. Thank you for calling C I M Communications Cloud. Please select a language you prefer.', array('voice'=>'alice', 'language'=>'en-US'));
		$gather = $client->gather(array('numDigits'=>1, 'action'=>'incomingcallhandler', 'method'=>'POST'));
		$gather->say('For English, press 1.', array('voice'=>'alice', 'language'=>'en-US'));
		$gather->say('For Spanish, press 2.', array('voice'=>'alice', 'language'=>'es-ES'));
		$gather->say('For German, press 3.', array('voice'=>'alice', 'language'=>'de-DE'));
		$gather->say('Press 0 to repeat the menu.', array('voice'=>'alice', 'language'=>'en-US'));		
		//$client->enqueue(array('workflowSid' => workflowSid))->task('{"selected_langauge" : "es"}');		
        //return $response;
		return new Response($client);
    }
    /**
     * @Route("/incomingcallhandler", name="commcloud_voice_incomingcallhandler")
     */
    public function incomingcallhandlerAction(Request $request)
    {       			
		$workflowSid = "WW542a85c0b81a847430d95d3dbc75f8a5";
		$collected_digits = $request->request->get('Digits');
		//$collected_digits = '1';
		if($collected_digits != '1' and $collected_digits != '2' and $collected_digits != '3'){
			 return $this->redirectToRoute('comcloud_voice_greeting', array(), 301);
		}
		$client = new Services_Twilio_Twiml;
		if ($collected_digits == '1'){
			$client->say('Your call is important to us. An Customer Service Executive will shortly attend to your query.', array('voice'=>'alice', 'language'=>'en-US'));
			$taskAttr = array("selected_language"=>"en");
			$jsonAttr = json_encode($taskAttr);
			//$client->record(array('maxLength'=>120));
			$client->enqueue(array('workflowSid' => $workflowSid))->task($jsonAttr, array('timeout' => 500, 'priority' => 5));
			$client->say('Error has occurred.',array('voice'=>'alice', 'language'=>'en-US'));
		}elseif($collected_digits == '2'){
			$client->say('Your call is important to us. An Customer Service Executive will shortly attend to your query.', array('voice'=>'alice', 'language'=>'es-ES'));
			$taskAttr = array("selected_language"=>"es");
			$jsonAttr = json_encode($taskAttr);
			//$client->record(array('maxLength'=>120));			
			$client->enqueue(array('workflowSid' => $workflowSid))->task($jsonAttr, array('timeout' => 500, 'priority' => 5));
			$client->say('Error has occurred.',array('voice'=>'alice', 'language'=>'en-US'));
		}elseif($collected_digits == '3'){
			$client->say('Your call is important to us. An Customer Service Executive will shortly attend to your query.', array('voice'=>'alice', 'language'=>'de-DE'));
			$taskAttr = array("selected_language"=>"de");
			$jsonAttr = json_encode($taskAttr);
			//$client->record(array('maxLength'=>120));			
			$client->enqueue(array('workflowSid' => $workflowSid))->task($jsonAttr, array('timeout' => 500, 'priority' => 5));
			$client->say('Error has occurred.',array('voice'=>'alice', 'language'=>'en-US'));
		}
		//$response->enqueue(array( workflowSid => 'WW61fcc3b0e481ddfa7610cd68b78e550a'));
		return new Response($client);
    }   
    /**
     * @Route("/outboundtwiml", name="commcloud_voice_outboundtwiml")
     */
    public function outboundtwimlAction(Request $request){
    	$caller_id = "+19164262378";
    	//$client_name = $this->get('security.token_storage')->getToken()->getUser()->getWfname();
    	$number = "";
    	// get the phone number from the page request parameters, if given
		if ($request->request->get('PhoneNumber')) {
		    $number = htmlspecialchars($request->request->get('PhoneNumber'));
		}
		
		$client = new Services_Twilio_Twiml;
		$client->say('You are making an outbound call.');		 
		$dial = $client->dial(NULL, array('callerId' => $caller_id));		
		// wrap the phone number or client name in the appropriate TwiML verb
		// by checking if the number given has only digits and format symbols
		if (preg_match("/^[\d\+\-\(\) ]+$/", $number)) {
		    //$numberOrClient = "<Number>" . $number . "</Number>";
			$dial->number($number);		    
		} //else {
		    //$numberOrClient = "<Client>" . $client_name . "</Client>";
		
		//	$dial->client($client_name);
		//	$dial->client('jenny');
		//}
		return new Response($client);
    }
    /**
     * @Route("/taskassignmentcallback", name="commcloud_voice_taskassignmentcallback")
     */
    public function taskassignmentcallbackAction(Request $request){
		$assignment_instruction = ['instruction' => 'dequeue','from' => '+19164262378','record' => 'record-from-answer'];
		$response = new Response(json_encode($assignment_instruction));
		$response->headers->set('Content-Type', 'application/json'); 
		return $response;
		//echo json_encode($assignment_instruction);
    }
    /**
     * @Route("/getchattoken", name="getchattoken")
     */
    public function getchattoken(){
    	$TWILIO_ACCOUNT_SID = '';
		$TWILIO_IPM_SERVICE_SID = '';
		$TWILIO_API_KEY = '';
		$TWILIO_API_SECRET = '';
    	
    	$appName = 'TwilioChatDemo';
    	$identity = 'Ashwin';
    	$deviceId = 'Browser';
    	$endpointId = $appName . ':' . $identity . ':' . $deviceId;
    	$token = new Services_Twilio_AccessToken(
		    $TWILIO_ACCOUNT_SID, 
		    $TWILIO_API_KEY, 
		    $TWILIO_API_SECRET, 
		    3600, 
		    $identity
		);
		// Create IP Messaging grant
		$ipmGrant = new Services_Twilio_Auth_IpMessagingGrant();
		$ipmGrant->setServiceSid($TWILIO_IPM_SERVICE_SID);
		$ipmGrant->setEndpointId($endpointId);
		
		// Add grant to token
		$token->addGrant($ipmGrant);
		
		// return serialized token and the user's randomly generated ID
		echo json_encode(array(
		    'identity' => $identity,
		    'token' => $token->toJWT(),
		));
    }
}
