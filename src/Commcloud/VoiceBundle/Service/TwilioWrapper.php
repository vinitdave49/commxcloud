<?php

namespace Commcloud\VoiceBundle\Service;

class TwilioWrapper extends \Services_Twilio
{
    public function __construct($sid, $token, $version, $retryattempts){
        parent::__construct($sid, $token, $version, null, $retryattempts);
    }

    public function createInstance($sid, $token, $version = null, $retryattempts){
        return new \Services_Twilio($sid, $token, $version, null, $retryattempts);
    }
}
