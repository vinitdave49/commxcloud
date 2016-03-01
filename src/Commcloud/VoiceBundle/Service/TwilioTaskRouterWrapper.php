<?php

//include_once $this->get('kernel')->getRootDir().'/../vendor/twilio/sdk/Services/Twilio/CapabilityTaskRouter.php';
namespace Commcloud\VoiceBundle\Service;

class TwilioTaskRouterWrapper extends \TaskRouter_Services_Twilio
{
    public function __construct($sid, $authToken, $workspaceSid, $retryattempts){
        parent::__construct($sid, $authToken, $workspaceSid, null, null, $retryattempts);
    }
    public function createInstance($sid, $authToken, $workspaceSid, $retryattempts){
        return new \TaskRouter_Services_Twilio($sid, $authToken, $workspaceSid, null, null, $retryattempts);
    }
}