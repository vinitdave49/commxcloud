<?php

namespace Commcloud\VoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WActivity
 *
 * @ORM\Table(name="w_activity")
 * @ORM\Entity
 */
class WActivity
{
    /**
     * @var string
     *
     * @ORM\Column(name="account_sid", type="string", length=50, nullable=false)
     */
    private $accountSid;

    /**
     * @var string
     *
     * @ORM\Column(name="workspace_sid", type="string", length=50, nullable=false)
     */
    private $workspaceSid;

    /**
     * @var string
     *
     * @ORM\Column(name="friendly_name", type="string", length=50, nullable=false)
     */
    private $friendlyName;

    /**
     * @var integer
     *
     * @ORM\Column(name="available", type="integer", nullable=true)
     */
    private $available = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="sid", type="string", length=50)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sid;


}

