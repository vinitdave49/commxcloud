<?php

namespace Commcloud\VoiceBundle\Service;

//require_once('/home/ubuntu/workspace/vendor/twilio/sdk/Services/Twilio/CapabilityTaskRouter.php');

class TwilioTaskRouterWorkerCapabilityWrapper extends \Services_Twilio_TaskRouter_Worker_Capability
{
    public function __construct($sid, $authToken, $workspaceSid, $workerSid)
    {
        //echo "In WorkerWrapper".$this->get('sid');
        parent::__construct($sid, $authToken, $workspaceSid, $workerSid, null, null);
    }

    public function createInstance($sid, $authToken, $workspaceSid, $workerSid)
    {
        return new \Services_Twilio_TaskRouter_Worker_Capability($sid, $authToken, $workspaceSid, $workerSid, null, null);
    }
}