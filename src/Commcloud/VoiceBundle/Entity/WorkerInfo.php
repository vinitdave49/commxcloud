<?php

namespace Commcloud\VoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * WorkerInfo
 *
 * @ORM\Table(name="worker_info", uniqueConstraints={@ORM\UniqueConstraint(name="wLoginId", columns={"wLoginId"})})
 * @ORM\Entity
 */
class WorkerInfo implements UserInterface, \Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="wFName", type="string", length=30, nullable=false)
     */
    private $wfname;

    /**
     * @var string
     *
     * @ORM\Column(name="wLName", type="string", length=30, nullable=false)
     */
    private $wlname;

    /**
     * @var string
     *
     * @ORM\Column(name="wPass", type="string", length=225, nullable=false)
     */
    private $wpass;

    /**
     * @var string
     *
     * @ORM\Column(name="wEmail", type="string", length=50, nullable=false)
     */
    private $wemail;

    /**
     * @var integer
     *
     * @ORM\Column(name="wStatus", type="integer", nullable=false)
     */
    private $wstatus = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="wSid", type="string", length=25, nullable=false)
     */
    private $wsid;

    /**
     * @var integer
     *
     * @ORM\Column(name="wLoginId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $wloginid;
    
     /**
     * @var string
     * @ORM\Column(name="wroles", type="string", length=50, nullable=false)
     */
    private $wroles = 'ROLE_USER';

    
	private $isActive;

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }


    /**
     * Set wfname
     *
     * @param string $wfname
     *
     * @return WorkerInfo
     */
    public function setWfname($wfname)
    {
        $this->wfname = $wfname;

        return $this;
    }

    /**
     * Get wfname
     *
     * @return string
     */
    public function getWfname()
    {
        return $this->wfname;
    }

    /**
     * Set wlname
     *
     * @param string $wlname
     *
     * @return WorkerInfo
     */
    public function setWlname($wlname)
    {
        $this->wlname = $wlname;

        return $this;
    }

    /**
     * Get wlname
     *
     * @return string
     */
    public function getWlname()
    {
        return $this->wlname;
    }

    /**
     * Set wpass
     *
     * @param string $wpass
     *
     * @return WorkerInfo
     */
    public function setWpass($wpass)
    {
        $this->wpass = $wpass;

        return $this;
    }

    /**
     * Get wpass
     *
     * @return string
     */
    public function getWpass()
    {
        return $this->wpass;
    }

    /**
     * Set wemail
     *
     * @param string $wemail
     *
     * @return WorkerInfo
     */
    public function setWemail($wemail)
    {
        $this->wemail = $wemail;

        return $this;
    }

    /**
     * Get wemail
     *
     * @return string
     */
    public function getWemail()
    {
        return $this->wemail;
    }

    /**
     * Set wstatus
     *
     * @param integer $wstatus
     *
     * @return WorkerInfo
     */
    public function setWstatus($wstatus)
    {
        $this->wstatus = $wstatus;

        return $this;
    }

    /**
     * Get wstatus
     *
     * @return integer
     */
    public function getWstatus()
    {
        return $this->wstatus;
    }

    /**
     * Set wsid
     *
     * @param string $wsid
     *
     * @return WorkerInfo
     */
    public function setWsid($wsid)
    {
        $this->wsid = $wsid;

        return $this;
    }

    /**
     * Get wsid
     *
     * @return string
     */
    public function getWsid()
    {
        return $this->wsid;
    }

    /**
     * Get wloginid
     *
     * @return integer
     */
    public function getWloginid()
    {
        return $this->wloginid;
    }
	
	 /**
     * Set wroles
     *
     * @param string $wroles
     *
     * @return WorkerInfo
     */
    public function setWroles($wroles)
    {
        $this->wroles = $wroles;

        return $this;
    }

    /**
     * Get wroles
     *
     * @return string
     */
    public function getWroles()
    {
        return $this->wroles;
    }
    
	public function getUsername()
    {
        return $this->wfname;
    }
	
	public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->wpass;
    }

    public function getRoles()
    {   
        //$roles = $this->$wroles;
       // $roles[] = 'ROLE_USER';
        return  array($this->wroles);
        //return array_unique($roles);
       // return array('ROLE_USER');
    }
    
    //public function setRoles(array $roles)
    //{
     //   $this->roles = $roles;

        // allows for chaining
      //  return $this;
    //}

    public function eraseCredentials()
    {
    }
	
	/** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->wsid,
            $this->wfname,
			$this->wloginid,
			$this->wroles
			
            
            // see section on salt below
             //$this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->wsid,
            $this->wfname,
			$this->wloginid,
            $this->wroles
			
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
   

   
}
