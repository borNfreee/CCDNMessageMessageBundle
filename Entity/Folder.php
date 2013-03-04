<?php

/*
 * This file is part of the CCDNMessage MessageBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNMessage\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use CCDNMessage\MessageBundle\Model\Folder as AbstractFolder;

class Folder extends AbstractFolder
{
	/** @var integer $id */
    protected $id;

    /** @var string $name */
    protected $name;
    
    /** @var integer $specialType */
    protected $specialType; // either 1=Inbox, 2=Sent, 3=Drafts or 4=Junk, 5=Deleted.

    /** @var integer $cachedReadCount */
    protected $cachedReadCount = 0;

    /** @var integer $cachedUnreadCount */
    protected $cachedUnreadCount = 0;

    /** @var integer $cachedTotalMessageCount */
    protected $cachedTotalMessageCount = 0;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param text $name
     */
    public function setName($name)
    {
        $this->name = $name;
		
		return $this;
    }

    /**
     * Get name
     *
     * @return text
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set specialType
     *
     * @param integer $specialType
     */
    public function setSpecialType($specialType)
    {
        $this->specialType = $specialType;
		
		return $this;
    }

    /**
     * Get specialType
     *
     * @return integer
     */
    public function getSpecialType()
    {
        return $this->specialType;
    }

    /**
     * Set cachedReadCount
     *
     * @param integer $cachedReadCount
     */
    public function setCachedReadCount($cachedReadCount)
    {
        $this->cachedReadCount = $cachedReadCount;
		
		return $this;
    }

    /**
     * Get cachedReadCount
     *
     * @return integer
     */
    public function getCachedReadCount()
    {
        return $this->cachedReadCount;
    }

    /**
     * Set cachedUnreadCount
     *
     * @param integer $cachedUnreadCount
     */
    public function setCachedUnreadCount($cachedUnreadCount)
    {
        $this->cachedUnreadCount = $cachedUnreadCount;
		
		return $this;
    }

    /**
     * Get cachedUnreadCount
     *
     * @return integer
     */
    public function getCachedUnreadCount()
    {
        return $this->cachedUnreadCount;
    }

    /**
     * Set cachedTotalMessageCount
     *
     * @param integer $cachedTotalMessageCount
     */
    public function setCachedTotalMessageCount($cachedTotalMessageCount)
    {
        $this->cachedTotalMessageCount = $cachedTotalMessageCount;
		
		return $this;
    }

    /**
     * Get cachedTotalMessageCount
     *
     * @return integer
     */
    public function getCachedTotalMessageCount()
    {
        return $this->cachedTotalMessageCount;
    }
}
