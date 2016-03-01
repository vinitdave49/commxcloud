<?php

namespace Commcloud\VoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WWorkspaces
 *
 * @ORM\Table(name="w_workspaces")
 * @ORM\Entity
 */
class WWorkspaces
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
     * @ORM\Column(name="friendly_name", type="string", length=50, nullable=false)
     */
    private $friendlyName;

    /**
     * @var string
     *
     * @ORM\Column(name="event_callback_url", type="string", length=500, nullable=false)
     */
    private $eventCallbackUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="default_activity_sid", type="string", length=50, nullable=false)
     */
    private $defaultActivitySid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=false)
     */
    private $dateUpdated;

    /**
     * @var string
     *
     * @ORM\Column(name="default_activity_name", type="string", length=50, nullable=false)
     */
    private $defaultActivityName;

    /**
     * @var string
     *
     * @ORM\Column(name="timeout_activity_sid", type="string", length=50, nullable=false)
     */
    private $timeoutActivitySid;

    /**
     * @var string
     *
     * @ORM\Column(name="timeout_activity_name", type="string", length=50, nullable=false)
     */
    private $timeoutActivityName;

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

