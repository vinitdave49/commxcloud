<?php

namespace Commcloud\VoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WTaskqueues
 *
 * @ORM\Table(name="w_taskqueues")
 * @ORM\Entity
 */
class WTaskqueues
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
     * @var string
     *
     * @ORM\Column(name="target_workers", type="string", length=10, nullable=false)
     */
    private $targetWorkers;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_reserved_workers", type="integer", nullable=false)
     */
    private $maxReservedWorkers = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="reservation_activity_sid", type="string", length=50, nullable=false)
     */
    private $reservationActivitySid;

    /**
     * @var string
     *
     * @ORM\Column(name="reservation_activity_name", type="string", length=50, nullable=false)
     */
    private $reservationActivityName;

    /**
     * @var string
     *
     * @ORM\Column(name="assignment_activity_sid", type="string", length=50, nullable=false)
     */
    private $assignmentActivitySid;

    /**
     * @var string
     *
     * @ORM\Column(name="assignment_activity_name", type="string", length=50, nullable=false)
     */
    private $assignmentActivityName;

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

