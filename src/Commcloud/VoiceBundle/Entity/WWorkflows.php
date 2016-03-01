<?php

namespace Commcloud\VoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WWorkflows
 *
 * @ORM\Table(name="w_workflows")
 * @ORM\Entity
 */
class WWorkflows
{
    /**
     * @var string
     *
     * @ORM\Column(name="friendly_name", type="string", length=50, nullable=false)
     */
    private $friendlyName;

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
     * @ORM\Column(name="assignment_callback_url", type="string", length=100, nullable=false)
     */
    private $assignmentCallbackUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="fallback_assignment_callback_url", type="string", length=100, nullable=false)
     */
    private $fallbackAssignmentCallbackUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="configuration", type="string", length=500, nullable=false)
     */
    private $configuration;

    /**
     * @var integer
     *
     * @ORM\Column(name="task_reservation_timeout", type="integer", nullable=false)
     */
    private $taskReservationTimeout = '120';

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

