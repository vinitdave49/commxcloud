<?php

namespace Commcloud\VoiceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use VoiceBundle\Entity\WorkerInfo;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $taskrouterclient = $this->get('commcloud_twilio_wrapper.twilio_taskrouter');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
    
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
    
        return $this->render(
            'CommcloudVoiceBundle:Login:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        
    }
    
    /**
     * @Route("/insert", name="")
     */
	public function insertAction(){
		
		$worker = new WorkerInfo();
		$worker->setWfname('Ashwin');
		$worker->setWlname('Deshpande');
		$worker->setWpass('ashwin');
		$worker->setWemail('ashwin@gmail.com');
		$worker->setWstatus('1');
		$worker->setWsid('WK410a6f0f30ea9f7bd30f6eccd5ee28ed');

		$em = $this->getDoctrine()->getManager();

		$em->persist($worker);
		$em->flush();
		return new Response('Created worker id '.$worker->getWpass());
	}
	
}
