<?php

namespace Commcloud\VoiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Commcloud\VoiceBundle\Entity\WorkerInfo;


class AdminController extends Controller
{
   /**
     * @Route("index", name="index")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
       // $this->get('logger')->debug("Called Index Page");
       // return $this->render('CommcloudVoiceBundle:Admin:home.html.twig');
    }
    // /**
    //  * @Route("logout", name="logout")
    //  */
    // public function logoutAction(Request $request)
    // {
    //     // replace this example code with whatever you need
    //     $this->get('logger')->debug("Called Logout");
    //     return $this->render('cloudadmin/index.html.twig');
    // }
    /**
     * @Route("home", name="login")
     */
    // public function loginAction(Request $request)
    // {
    //     $this->get('logger')->debug("Called Login");
    //     $loginname = $request->request->get("wLoginId");
    //     $password = $request->request->get("wPass");

    //     $encPass = sha1($password);
    //     //console.log("Encoded Password: ".$encPass);

    //     $user = $this->getDoctrine()
    //             ->getRepository('AppBundle:User')
    //             ->findOneBy(array('loginname'=>$loginname, 'password'=>$encPass));

    //     if (isset($user)){
    //         return $this->render('cloudadmin/home.html.twig', array('loginname' => $user->getFirstname()));
    //     } else {
    //         return new Response('<h1>Invalid User Credentials!!!</h1>');
    //     }
    //     //return $this->render('cloudadmin/home.html.twig');
    // }
    /**
     * @Route("createactivityTemplate", name="createactivityTemplate")
     */
    public function createActivityTemplateAction(Request $request)
    {
        return $this->render('CommcloudVoiceBundle:Admin:createactivitytemplate.html.twig');
    }
    /**
     * @Route("createworkerTemplate", name="createworkerTemplate")
     */
    public function createWorkerTemplateAction(Request $request)
    {
        return $this->render('CommcloudVoiceBundle:Admin:createworkertemplate.html.twig');
    }
    /**
     * @Route("createtaskqueueTemplate", name="createtaskqueueTemplate")
     */
    public function createTaskQueueTemplateAction(Request $request)
    {
        return $this->render('CommcloudVoiceBundle:Admin:createtaskqueuetemplate.html.twig');
    }
    /**
     * @Route("createuser", name="createuser")
     */
    public function createuserAction(Request $request)
    {
        //return $this->render('cloudadmin/admin/createuser.html.twig');
    }
    /**
     * @Route("createproject", name="createproject")
     */
    public function createprojecttemplateAction(Request $request)
    {
       // $projects = $this->getDoctrine()
        //        ->getRepository('AppBundle:Project')
        //        ->findAll();

       // return $this->render('cloudadmin/admin/createproject.html.twig', array('projects'=>$projects));
    }
    /**
     * @Route("configureproject", name="configureproject")
     */
    public function configureprojectAction(Request $request)
    {
       // $workspaceid = $request->request->get("workspaceid");
       // $projectname = $request->request->get("projectname");
       // $session = $this->get('session');
       // $session->set('workspaceid', $workspaceid);
        //$this->getRequest()->getSession()->set('workspaceid', $workspaceid);
       // return $this->render('CommcloudVoiceBundle:Admin:configureproject.html.twig', array('projectname'=>$projectname, 'workspaceid'=>$workspaceid));
    }
    /**
     * @Route("createprojectaction", name="createprojectaction")
     */
    public function createprojectAction(Request $request)
    {
        $projectname = json_decode(file_get_contents("php://input"));
        //include_once $this->get('kernel')->getRootDir().'/../vendor/twilio/sdk/Services/Twilio.php';
        $taskrouterclient = $this->get('comcloud_twilio_wrapper.twilio_taskrouter');
        //$path = $this->get('kernel')->getRootDir();
        $accountSid = 'AC0f18494502dee91457252ff7b2bada6a';
        $authToken = '2f430f7691acb543889ef3a721c1c096';
        $params = array();
        $params['EventCallbackUrl'] = 'http://requestb.in/vh9reovh';
        $params['Template'] = 'FIFO';

        //$taskrouterclient = new \TaskRouter_Services_Twilio($accountSid, $authToken, null);;

        $workspace = $taskrouterclient->workspaces->create($projectname, $params);
        $workspaceId = $workspace->sid;

        $new_project = new Project();
        $new_project->setProjectname($projectname);
        $new_project->setWorkspaceid($workspaceId);

        $em = $this->getDoctrine()->getManager();
        $em->persist($new_project);
        $em->flush();

        //$query = $em->createQuery('create database db_'.$projectname);

        //$project = $query->getResult();

        //var_dump($project);

        return new Response('<h1>Project Created!!!'.$projectname.' Id: '.$workspaceId.'</h1>');
    }
    /**
     * @Route("/app/createworker", name="createworker")
     */
    public function createworker(Request $request)
    {
        /* This code is to display fields on create worker form from the database(created in workertemplate)
        $textboxes = $this->getDoctrine()
            ->getRepository('AppBundle:TemplateDetails')
            ->findBy(array('attributeType'=>1));

        $checkboxes = $this->getDoctrine()
            ->getRepository('AppBundle:TemplateDetails')
            ->findBy(array('attributeType'=>2));
             return $this->render('cloudadmin/admin/createworker.html.twig', array('textboxes'=>$textboxes, 'checkboxes'=>$checkboxes));
        */
        return $this->render('CommcloudVoiceBundle:Admin:createworker.html.twig');
    }
    /**
    * @Route("createworkeraction", name="createworkeraction")
    */
    public function createworkerAction(Request $request)
    {

        $wFName=$request->request->get('wFName');
        $wLName=$request->request->get("wLName");
        $wName=$request->request->get('wName');
        $wemail=$request->request->get("wemail");
        $wSkills=$request->request->get("wSkills");
        $wroles=$request->request->get("wroles");
        $wSkill_Array = array("Attributes"=>$wSkills);
        $taskrouterclient = $this->get('comcloud_twilio_wrapper.twilio_taskrouter');
        $worker = $taskrouterclient->workspace->workers->create($wName, $wSkill_Array);
        echo $worker->friendly_name;
        
        $new_worker = new WorkerInfo();
        $new_worker->setWfname($wFName);
        $new_worker->setWlname($wLName);
        $new_worker->setWemail($wemail);
        //$new_worker->setWname($wName);
        //$new_worker->setWlname($wSkills);
        $new_worker->setWsid($worker->sid);
        
        $wPass  = "pass123";
        $encoder = $this->container->get('security.encoder_factory')
                                    ->getEncoder($new_worker);

        $enPass = $encoder->encodePassword($wPass, $new_worker->getSalt());
        //$enPass = bcrypt($wPass);
        $new_worker->setWpass($enPass);
        $new_worker->setWroles($wroles);

        $em = $this->getDoctrine()->getManager();
        $em->persist($new_worker);
        $em->flush();
        //echo $worker->sid;
        return new Response('Worker Created!!!'.$wFName.' Id: '.$worker->sid);
    }
    /**
     * @Route("demopassword", name="")
     */
    public function demopassword(){
        $new_worker = new WorkerInfo();
        $wPass  = "pass123";
        $encoder = $this->container->get('security.encoder_factory')
                                   ->getEncoder($new_worker);

        $enPass = $encoder->encodePassword($wPass, $new_worker->getSalt());
        return new Response($enPass);
    }
    /**
     * @Route("/app/createactivity", name="createactivity")
     */
    public function createactivity(Request $request)
    {
        // $activity = $this->getDoctrine()
        //         ->getRepository('AppBundle:Activity')
        //         ->findAll();
        return $this->render('CommcloudVoiceBundle:Admin:createactivity.html.twig');
    }
    /**
     * @Route("createactivityaction", name="createactivityaction")
     */
    public function createactivityAction(Request $request)
    {
        $actName=$request->request->get("actName");
        $availability=$request->request->get("availability");
        $taskrouterclient = $this->get('comcloud_twilio_wrapper.twilio_taskrouter');
        $activity = $taskrouterclient->workspace->activities->create($actName, $availability);
        //echo $activity->friendly_name;
        return new Response('Activity Created!!!'.$actName.' Id: '.$activity->sid);
        //return $this->render('cloudadmin/admin/createactivity.html.twig');
    }
    /**
     * @Route("/app/createworkflow", name="createworkflow")
     */
    public function createworkflowAction(Request $request)
    {
        return $this->render('CommcloudVoiceBundle:Admin:createworkflow.html.twig');
    }
    /**
     * @Route("/app/createtaskqueue", name="createtaskqueue")
     */
    public function createtaskqueue(Request $request)
    {
        return $this->render('CommcloudVoiceBundle:Admin:createtaskqueue.html.twig');
    }
        /**
     * @Route("createtaskqueueaction", name="createtaskqueueaction")
     */
    public function createtaskqueueAction(Request $request)
    {
        //var_dump($request);
        $tqName=$request->request->get('tqName');
        $reservationActivity=$request->request->get("reservationActivity");
        $assignmentActivity=$request->request->get("assignmentActivity");
        $maxreservWorkers=$request->request->get("maxreservWorkers");
        $targetWorkers=array("TargetWorkers"=>$request->request->get("targetWorkers"));

        $taskrouterclient = $this->get('comcloud_twilio_wrapper.twilio_taskrouter');
        $taskQueue = $taskrouterclient->workspace->task_queues->create($tqName,$reservationActivity,$assignmentActivity, $targetWorkers);
        //echo $taskQueue->friendly_name;
        return new Response('TaskQueue Created!!!'.$tqName.' Id: '.$taskQueue->sid);
        //return $this->render('cloudadmin/admin/createtaskqueue.html.twig');
    }
}
