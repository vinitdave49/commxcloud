<?php

namespace Commcloud\VoiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SupervisorController extends Controller
{
    public function generate_color() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * @Route("/postTwilioEvent", name="eventcallbackURL")
     */
    public function postTwilioEventAction(Request $request)
    {
        $this->get('logger')->debug("Event Logger: Start ");
        $eventParamArray = $request->request->all();
        $this->get('logger')->debug("Event Array: ".var_dump($eventParamArray));
        return new Response("Hello World!!!".print_r($eventParamArray));
    }
    /**
     * @Route("/supervisorindex", name="reporting_home")
     */
    public function indexAction()
    {
        return $this->render('CommcloudVoiceBundle:Default:index.html.twig');
    }
    /**
     * @Route("/app/load_rt_workflow_report_filter", name="load_rt_workflow_report_filter")
     */
    public function rtworkflowreportfilterAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $taskqueuesArray = array();
        foreach($taskrouterclient->workspace->workflows as $workflow)
        {
        	$workflowsArray[] = $workflow;
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Realtime:rt_workflowreport.html.twig', array('workflows'=>$workflowsArray));
    }
    /**
     * @Route("/load_rt_workflow_report", name="load_rt_workflow_report")
     */
    public function rtworkflowreportAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $filterArray = $request->request->all();
        $startdate_iso = date('c', strtotime($filterArray['startdate']));
        $enddate_iso = date('c', strtotime($filterArray['enddate']));
        $args = array();
        if ($filterArray['minutes'] == 0){
             $args = array("StartDate"=>$startdate_iso, "EndDate"=>$enddate_iso);
        } else {
             $args = array("Minutes"=>$filterArray['minutes']);
        }
        $statistics = $taskrouterclient->getWorkflowStatistics($filterArray['workflow'], $args);
        $realtimestatsString = json_encode($statistics->realtime);
        $realtimestatsArray = json_decode($realtimestatsString, true);
        $realtimeactivityArray = $realtimestatsArray['tasks_by_status'];
        $rt_response = array();
        $i = 0;
        //$color_array = array("#F7464A", "#46BFBD", "#FDB45C");
        //$highlight_array = array("#FF5A5E","#5AD3D1","#FFC870");
        foreach ($realtimeactivityArray as $key=>$value){
            $rt_response[] = array("value"=>$value, "color"=>$this->generate_color(), "highlight"=>$this->generate_color(), "label"=>$key);
             //print_r($key." is ".$value);
            $i = $i + 1;
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Realtime:rt_workflowdatatable.html.twig', array('realtime_stats'=>$realtimestatsArray, 'realtime_tasks'=>$rt_response));
        //return new Response(var_dump($rt_response));
    }    
    /**
     * @Route("/app/load_rt_worker_report_filter", name="load_rt_worker_report_filter")
     */
    public function rtworkerreportfilterAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $taskqueuesArray = array();
        foreach($taskrouterclient->workspace->task_queues as $taskqueue)
        {
        	$taskqueuesArray[] = $taskqueue;
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Realtime:rt_workerreport.html.twig', array('taskqueues'=>$taskqueuesArray));
    }
    /**
     * @Route("/load_rt_worker_report", name="load_rt_worker_report")
     */
    public function rtworkerreportAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $filterArray = $request->request->all();
        $startdate_iso = date('c', strtotime($filterArray['startdate']));
        $enddate_iso = date('c', strtotime($filterArray['enddate']));
        $args = array();
        if ($filterArray['minutes'] == 0){
             $args = array("StartDate"=>$startdate_iso, "EndDate"=>$enddate_iso);
        } else {
             $args = array("Minutes"=>$filterArray['minutes']);
        }
        $statistics = $taskrouterclient->getWorkersStatistics($args);
        $realtimestatsString = json_encode($statistics->realtime);
        $realtimestatsArray = json_decode($realtimestatsString, true);
        $realtimeactivityArray = $realtimestatsArray['activity_statistics'];
        $rt_response = array();
        $i = 0;
        $color_array = array("#F7464A", "#46BFBD", "#FDB45C", "#FFD700");
        $highlight_array = array("#FF5A5E","#5AD3D1","#FFC870", "#FFFF00");
        foreach ($realtimeactivityArray as $rt_array){
            $rt_response[] = array("value"=>$rt_array['workers'], "color"=>$this->generate_color(), "highlight"=>$this->generate_color(), "label"=>$rt_array['friendly_name']);
            //print_r("Count: ".$i."<br>");
            $i = $i + 1;
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Realtime:rt_workerdatatable.html.twig', array('realtime_tasks'=>$rt_response));
        //return new Response(var_dump($realtimeactivityArray));
    }    
    /**
     * @Route("/app/load_rt_taskqueue_report_filter", name="load_rt_taskqueue_report_filter")
     */
    public function rttaskqueuereportfilterAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $taskqueuesArray = array();
        foreach($taskrouterclient->workspace->task_queues as $taskqueue)
        {
        	$taskqueuesArray[] = $taskqueue;
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Realtime:rt_taskqueuereport.html.twig', array('taskqueues'=>$taskqueuesArray));
    }
    /**
     * @Route("/load_rt_taskqueue_report", name="load_rt_taskqueue_report")
     */
    public function rttaskqueuereportAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $filterArray = $request->request->all();
        $startdate_iso = date('c', strtotime($filterArray['startdate']));
        $enddate_iso = date('c', strtotime($filterArray['enddate']));
        $args = array();
        if ($filterArray['minutes'] == 0){
             $args = array("StartDate"=>$startdate_iso, "EndDate"=>$enddate_iso);
        } else {
             $args = array("Minutes"=>$filterArray['minutes']);
        }
        $statistics = $taskrouterclient->getTaskQueueStatistics($filterArray['taskqueue'], $args);
        $realtimestatsString = json_encode($statistics->realtime);
        $realtimestatsArray[] = json_decode($realtimestatsString, true);
        $realtimeactivityArray = $realtimestatsArray[0]['activity_statistics'];
        $realtimetasksArray = $realtimestatsArray[0]['tasks_by_status'];
        $color_array = array("#F7464A", "#46BFBD", "#FDB45C");
        $highlight_array = array("#FF5A5E","#5AD3D1","#FFC870");
        $rt_response = array();
        $rt_response[]=array("value"=>$realtimetasksArray['pending'], "color"=>$color_array[0], "highlight"=>$highlight_array[0], "label"=>"Pending Tasks");
        $rt_response[]=array("value"=>$realtimetasksArray['assigned'], "color"=>$color_array[1], "highlight"=>$highlight_array[1], "label"=>"Assigned Tasks");
        $rt_response[]=array("value"=>$realtimetasksArray['reserved'], "color"=>$color_array[2], "highlight"=>$highlight_array[2], "label"=>"Reserved Tasks");
        return $this->render('CommcloudVoiceBundle:Supervisor/Realtime:rt_taskqueuedatatable.html.twig', array('realtime_data'=>$realtimestatsArray,'realtime_activitystats'=>$realtimeactivityArray,'realtime_tasks'=>$rt_response));
        //return new Response(var_dump($rt_response));
    }
    
    /**
     * @Route("/load_ht_taskqueue_report", name="load_ht_taskqueue_report")
     */
    public function httaskqueuereportAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $filterArray = $request->request->all();
        //$startdate = \DateTime::createFromFormat('Y-m-d', $filterArray['startdate']);
        //$enddate = \DateTime::createFromFormat('Y-m-d', $filterArray['enddate']);
        $startdate_iso = date('c', strtotime($filterArray['startdate']));
        $enddate_iso = date('c', strtotime($filterArray['enddate']));
        $args = array();
        if ($filterArray['minutes'] == 0){
             $args = array("StartDate"=>$startdate_iso, "EndDate"=>$enddate_iso);
        } else {
             $args = array("Minutes"=>$filterArray['minutes']);
        }
        $statistics = $taskrouterclient->getTaskQueueStatistics($filterArray['taskqueue'], $args);
        $historicalstatsString = json_encode($statistics->cumulative);
        $historicalstatsArray[] = json_decode($historicalstatsString, true);
        return $this->render('CommcloudVoiceBundle:Supervisor/Historical:ht_taskqueuedatatable.html.twig', array('historical_data'=>$historicalstatsArray));
        //return new Response(var_dump($historicalstatsArray));
    }
    
    /**
     * @Route("/app/load_ht_taskqueue_report_filter", name="load_ht_taskqueue_report_filter")
     */
    public function httaskqueuereportfilterAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $taskqueuesArray = array();
        foreach($taskrouterclient->workspace->task_queues as $taskqueue)
        {
        	$taskqueuesArray[] = $taskqueue;
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Historical:ht_taskqueuereport.html.twig', array('taskqueues'=>$taskqueuesArray));
    }
    /**
     * @Route("/load_ht_workflow_report", name="load_ht_workflow_report")
     */
    public function htworkflowreportAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $filterArray = $request->request->all();
        //$startdate = \DateTime::createFromFormat('Y-m-d', $filterArray['startdate']);
        //$enddate = \DateTime::createFromFormat('Y-m-d', $filterArray['enddate']);
        $startdate_iso = date('c', strtotime($filterArray['startdate']));
        $enddate_iso = date('c', strtotime($filterArray['enddate']));
        $args = array();
        if ($filterArray['minutes'] == 0){
             $args = array("StartDate"=>$startdate_iso, "EndDate"=>$enddate_iso);
        } else {
             $args = array("Minutes"=>$filterArray['minutes']);
        }
        $statistics = $taskrouterclient->getWorkflowStatistics($filterArray['workflow'], $args);
        $historicalstatsString = json_encode($statistics->cumulative);
        $historicalstatsArray[] = json_decode($historicalstatsString, true);
        return $this->render('CommcloudVoiceBundle:Supervisor/Historical:ht_workflowdatatable.html.twig', array('historical_data'=>$historicalstatsArray));
        //return new Response(var_dump($historicalstatsArray));
    }
    
    /**
     * @Route("/app/load_ht_workflow_report_filter", name="load_ht_workflow_report_filter")
     */
    public function htworkflowreportfilterAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $workflowsArray = array();
        foreach($taskrouterclient->workspace->workflows as $workflow)
        {
        	$workflowsArray[] = $workflow;
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Historical:ht_workflowreport.html.twig', array('workflows'=>$workflowsArray));
    }
    /**
     * @Route("/load_ht_worker_report", name="load_ht_worker_report")
     */
    public function htworkerreportAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $filterArray = $request->request->all();
        //$startdate = \DateTime::createFromFormat('Y-m-d', $filterArray['startdate']);
        //$enddate = \DateTime::createFromFormat('Y-m-d', $filterArray['enddate']);
        $startdate_iso = date('c', strtotime($filterArray['startdate']));
        $enddate_iso = date('c', strtotime($filterArray['enddate']));
        $args = array();
        if ($filterArray['minutes'] == 0){
             $args = array("StartDate"=>$startdate_iso, "EndDate"=>$enddate_iso);
        } else {
             $args = array("Minutes"=>$filterArray['minutes']);
        }
        $statistics = $taskrouterclient->getWorkerStatistics($filterArray['worker'], $args);
        $historicalstatsString = json_encode($statistics->cumulative);
        $historicalstatsArray[] = json_decode($historicalstatsString, true);
        return $this->render('CommcloudVoiceBundle:Supervisor/Historical:ht_workerdatatable.html.twig', array('historical_data'=>$historicalstatsArray));
        //return new Response(var_dump($historicalstatsArray));
    }
    
    /**
     * @Route("/app/load_ht_worker_report_filter", name="load_ht_workfer_report_filter")
     */
    public function htworkerreportfilterAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $workersArray = array();
        foreach($taskrouterclient->workspace->workers as $worker)
        {
        	$workersArray[] = $worker;
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Historical:ht_workerreport.html.twig', array('workers'=>$workersArray));
    }
    /**
     * @Route("/load_ht_workeractivitydurationbytaskqueue_report", name="load_ht_workeractivitydurationbytaskqueue_report")
     */
    public function htworkeractivitydurationbytaskqueuereportAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $filterArray = $request->request->all();
        //$startdate = \DateTime::createFromFormat('Y-m-d', $filterArray['startdate']);
        //$enddate = \DateTime::createFromFormat('Y-m-d', $filterArray['enddate']);
        $startdate_iso = date('c', strtotime($filterArray['startdate']));
        $enddate_iso = date('c', strtotime($filterArray['enddate']));
        $args = array();
        if ($filterArray['minutes'] == 0){
             $args = array("StartDate"=>$startdate_iso, "EndDate"=>$enddate_iso);
        } else {
             $args = array("Minutes"=>$filterArray['minutes']);
        }
        $statistics = $taskrouterclient->getWorkersStatistics($args);
        $historicalstatsString = json_encode($statistics->cumulative);
        $historicalstatsArray[] = json_decode($historicalstatsString, true);
        
        foreach($historicalstatsArray as $key=>$value){
            if($key == "activity_durations"){
                $activitydurations_array = $value['activity_durations'];
            }
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Historical:ht_worker_act_dur_by_tq_datatable.html.twig', array('activity_durations'=>$activitydurations_array));
        //return new Response(var_dump($activitydurations_array[0]));
    }
    
    /**
     * @Route("/app/load_ht_workeractivitydurationbytaskqueue_report_filter", name="load_ht_workeractivitydurationbytaskqueue_report_filter")
     */
    public function htworkeractivitydurationbytaskqueuereportfilterAction(Request $request)
    {
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        $workersArray = array();
        foreach($taskrouterclient->workspace->workers as $worker)
        {
        	$workersArray[] = $worker;
        }
        
        $taskqueuesArray = array();
        foreach($taskrouterclient->workspace->task_queues as $taskqueue)
        {
        	$taskqueuesArray[] = $taskqueue;
        }
        return $this->render('CommcloudVoiceBundle:Supervisor/Historical:ht_workeractivitydurationbytaskqueuereport.html.twig', array('workers'=>$workersArray,'taskqueues'=>$taskqueuesArray));
    }  
    
     /**
     * @Route("/calls/list", name="load_ht_calllist")
     */
    public function htcalllistAction(){
        
        	$twilio = $this->get('commcloud_twilio_wrapper.twilio_api');
		 //	$twilio = new Services_Twilio($accountSid, $authToken);
		 	$calls = $twilio->account->calls->getIterator(0, 50, array('Status' => 'in-progress')); 
		 	return $this->render('CommcloudVoiceBundle:Agent:call_list.html.twig', array('calls' => $calls));
    }
}
