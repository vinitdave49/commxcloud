<?php

namespace Commcloud\VoiceBundle\Service;

class TwilioCapabilityWrapper extends \Services_Twilio_Capability {
    public function __construct($sid, $token){
        parent::__construct($sid, $token);
    }

    public function createInstance($sid, $token){
        return new \Services_Twilio_Capability($sid, $token);
    }
}