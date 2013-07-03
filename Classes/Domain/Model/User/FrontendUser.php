<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



/**
 *
 * A frontend user.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_User
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Domain_Model_User_FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
	implements Tx_MmForum_Domain_Model_AccessibleInterface {



	/*
	 * ATTRIBUTES
	 */

	/**
	 * The rank repository
	 * @var Tx_MmForum_Domain_Repository_User_RankRepository
	 */
	protected $rankRepository = NULL;

	/**
	 * Forum post count
	 * @var integer
	 */
	protected $postCount;


	/**
	 * Topic count of a user
	 * @var integer
	 */
	protected $topicCount;

	/**
	 * Forum helpful count
	 * @var integer
	 */
	protected $helpfulCount;


	/**
	 * Question count of a user
	 * @var integer
	 */
	protected $questionCount;


	/**
	 * The signature. This will be displayed below this user's posts.
	 * @var string
	 */
	protected $signature;

	/**
	 * @var string
	 */
	protected $facebook;

	/**
	 * @var string
	 */
	protected $twitter;

	/**
	 * @var string
	 */
	protected $google;

	/**
	 * @var string
	 */
	protected $skype;

	/**
	 * @var string
	 */
	protected $job;

	/**
	 * @var string
	 */
	protected $workingEnvironment;

	/**
	 * Fav Subscribed topics.
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
	 * @lazy
	 */
	protected $topicFavSubscriptions;


	/**
	 * Fav Subscribed forums.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
	 * @lazy
	 */
	protected $forumFavSubscriptions;

	/**
	 * Subscribed topics.
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
	 * @lazy
	 */
	protected $topicSubscriptions;


	/**
	 * Subscribed forums.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
	 * @lazy
	 */
	protected $forumSubscriptions;


	/**
	 * The creation date of this user.
	 * @var DateTime
	 */
	protected $crdate;


	/**
	 * Userfield values.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_User_Userfield_Value>
	 */
	protected $userfieldValues;


	/**
	 * Read topics.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
	 */
	protected $readTopics;

	/**
	 * Read topics.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_Forum_Post>
	 */
	protected $supportPosts;


	/**
	 * The country.
	 * @var string
	 */
	protected $staticInfoCountry;


	/**
	 * The gender.
	 * @var integer
	 */
	protected $gender;


	/**
	 * Timestamp of last action of the user
	 * @var integer
	 */
	protected $isOnline;

	/**
	 * @var integer
	 */
	protected $disable;

	/**
	 * Defines whether to use a "gravatar" if no user image is available.
	 * @var boolean
	 */
	protected $useGravatar = FALSE;


	/**
	 * The rank of this user
	 * @var Tx_MmForum_Domain_Model_User_Rank
	 */
	protected $rank;


	/**
	 * The points of this user
	 * @var int
	 */
	protected $points;


	/**
	 * @var string
	 */
	protected $interests;


	/**
	 * The private messages of this user.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_User_PrivateMessages>
	 */
	protected $privateMessages;


	/**
	 * JSON encoded contact addresses and social network profile names. Stored
	 * unstructuredly in order to add more types of addresses without extending
	 * the database for each social network.
	 * @var string
	 */
	protected $contact = '';


	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUserGroup>
	 */
	protected $usergroup;

	/**
	 * Constructor.
	 *
	 * @param string $username The user's username.
	 * @param string $password The user's password.
	 */
	public function __construct($username = '', $password = '') {
		parent::__construct($username, $password);
		$this->readTopics = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}


	/**
	 * Inject the rank repository
	 * @param Tx_MmForum_Domain_Repository_User_RankRepository $rankRepository
	 * @return void
	 */
	public function injectRankRepository(Tx_MmForum_Domain_Repository_User_RankRepository $rankRepository) {
		$this->rankRepository = $rankRepository;
	}

	/*
	 * GETTERS
	 */

	/**
	 * Returns the usergroups. Keep in mind that the property is called "usergroup"
	 * although it can hold several usergroups.
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage An object storage containing the usergroup
	 * @api
	 */
	public function getUsergroup() {
		return $this->usergroup;
	}

	/**
	 * Gets the points of this user
	 * @return integer
	 */
	public function getPoints() {
		return $this->points;
	}

	/**
	 * Gets the points of this user
	 * @return integer
	 */
	public function getPoints() {
		return $this->points;
	}

	/**
	 * Gets the post count of this user.
	 * @return integer The post count.
	 */
	public function getPostCount() {
		return $this->postCount;
	}


	/**
	 * Gets the topic count of this user.
	 * @return integer The topic count.
	 */
	public function getTopicCount() {
		return $this->topicCount;
	}

	/**
	 * Gets the social-profile of user
	 * @return string
	 */
	public function getFacebook() {
		return $this->facebook;
	}

	/**
	 * Gets the social-profile of user
	 * @return string
	 */
	public function getTwitter() {
		return $this->twitter;
	}

	/**
	 * Gets the social-profile of user
	 * @return string
	 */
	public function getGoogle() {
		return $this->google;
	}

	/**
	 * Gets the social-profile of user
	 * @return string
	 */
	public function getSkype() {
		return $this->skype;
	}

	/**
	 * Gets the job of user
	 * @return string
	 */
	public function getJob() {
		return $this->job;
	}

	/**
	 * Gets the job of user
	 * @return string
	 */
	public function getWorkingEnvironment() {
		return $this->workingEnvironment;
	}

	/**
	 * Gets the question count of this user.
	 * @return integer The question count.
	 */
	public function getQuestionCount() {
		return $this->questionCount;
	}

	/**
	 * Gets the gender of the user
	 * @return integer (0=male, 1=female, 99=private)
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @return string
	 */
	public function getRegistrationDate() {
		return $this->crdate->format('d.m.Y');
	}

	/**
	 * Get the rank of this user
	 * @return Tx_MmForum_Domain_Model_User_Rank
	 */
	public function getRank() {
		return $this->rank;
	}


	/**
	 * Gets the private messages of this user.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_User_PrivateMessages>
	 */
	public function getPrivateMessages() {
		return $this->privateMessages;
	}


	/**
	 * Gets the subscribed topics.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
	 *                             The subscribed topics.
	 */
	public function getTopicSubscriptions() {
		return $this->topicSubscriptions;
	}

	/**
	 * Gets the helpful count of this user.
	 * @return integer The helpful count.
	 */
	public function getHelpfulCount() {
		return $this->helpfulCount;
	}

	/**
	 * Sets the helpfulCount value +1
	 *
	 * @return void
	 * @api
	 */
	public function setHelpful() {
		$this->setHelpfulCount($this->getHelpfulCount()+1);
	}

	/**
	 * Sets the city value
	 *
	 * @return void
	 * @api
	 */
	public function setHelpfulCount($count) {
		$this->helpfulCount = $count;
	}

	/**
	 * Gets the subscribed forums.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
	 *                             The subscribed forums.
	 */
	public function getForumSubscriptions() {
		return $this->forumSubscriptions;
	}

	/**
	 * Gets the subscribed forums.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_Forum_Post>
	 *                             The subscribed forums.
	 */
	public function getSupportPosts() {
		return $this->supportPosts;
	}



	/**
	 * Gets the user's registration date.
	 * @return DateTime The registration date
	 */
	public function getTimestamp() {
		return $this->crdate;
	}


	/**
	 * Performs an access check for this post.
	 *
	 * @access private
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 * @param  string                                    $accessType
	 * @return boolean
	 */
	public function checkAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL, $accessType = 'moderate') {
		switch ($accessType) {
			default:
				foreach($user->getUsergroup() as $group){
					if($group->getUserMod()) return true;
				}
				return false;
		}
	}
	/**
	 * Determines if this user is member of a specific group.
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUserGroup $checkGroup
	 * @return boolean
	 */
	public function isInGroup(Tx_MmForum_Domain_Model_User_FrontendUserGroup $checkGroup) {
		foreach ($this->getUsergroup() As $group) {
			if ($group == $checkGroup) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Get the online status of a User
	 * @return boolean.
	 */
	public function getIsOnline() {
		if(time() - $this->isOnline < 300) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Gets the user's signature.
	 * @return string The signature.
	 */
	public function getSignature() {
		return $this->signature;
	}

	/**
	 * Gets the user's signature.
	 * @return boolean
	 */
	public function getDisable() {
		return $this->disable;
	}

	/**
	 * @param bool $val
	 */
	public function setDisable($val) {
		$this->disable = intval($val);
	}


	/**
	 * Gets the userfield values for this user.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_User_Userfield_Value>
	 */
	public function getUserfieldValues() {
		return $this->userfieldValues;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_User_Userfield_Value>
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_MmForum_Domain_Model_User_Userfield_Value>
	 */
	public function getInterests() {
		return $this->interests;
	}



	/**
	 * Returns the absolute path of this user's avatar image (if existent).
	 *
	 * @global array $TCA
	 * @return string The absolute path of this user's avatar image (if existent).
	 */
	public function getImagePath() {

		// If an image is defined for this user, retrieve the upload folder from
		// the TCA (uploads/pics be default, but can be overridden, for example
		// by the sr_feuser_register extension, so it's better to check).
		if ($this->image) {
			if (version_compare(TYPO3_branch, '6.1', '<')) {
				\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('fe_users');
			}
			global $TCA;

			$imageDirectoryName = $TCA['fe_users']['columns']['image']['config']['uploadfolder'];
			$imageFilename      = rtrim($imageDirectoryName, '/') . '/' . $this->image;

			return file_exists($imageFilename) ? $imageFilename : NULL;
		}

		// If the user enabled the use of "Gravatars", then load this user's
		// gravatar using the official API (it's quite simple, actually: Just
		// use the MD5 checksum of the user's email address in the gravatar URL
		// and you're fine (http://de.gravatar.com/site/implement/images/).
		if ($this->useGravatar) {
			$emailHash         = md5(strtolower($this->email));
			$temporaryFilename = 'typo3temp/mm_forum/gravatar/' . $emailHash . '.jpg';
			if (!file_exists(PATH_site . $temporaryFilename)) {
				$image = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl('https://secure.gravatar.com/avatar/' . $emailHash . '.jpg');
				file_put_contents(PATH_site . $temporaryFilename, $image);
			}
			return $temporaryFilename;
		}

		return NULL;
	}



	/**
	 * Returns all this user's contact information. In order to keep this extensible and
	 * not to add too many columns to the already overloaded fe_users table, these data is
	 * stored in JSON serialized format in a single column.
	 *
	 * @return array All contact information for this user.
	 */
	public function getContactData() {
		$decoded = json_decode($this->contact, TRUE);
		if ($decoded === NULL) {
			return array();
		}
		return $decoded;
	}



	/**
	 * Determines whether is user is an anonymous user.
	 * @return bool TRUE when this user is an anonymous user.
	 */
	public function isAnonymous() {
		return FALSE;
	}



	/**
	 * Alias for isAnonymous().
	 * @return bool TRUE when this user is an anonymous user.
	 */
	public function getAnonymous() {
		return $this->isAnonymous();
	}



	/*
	 * SETTERS
	 */

	/**
	 * Subscribes this user to a subscribeable object, like a topic or a forum.
	 *
	 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
	 *                             The object that is to be subscribed. This may
	 *                             either be a topic or a forum.
	 * @return void
	 */
	public function addFavSubscription(Tx_MmForum_Domain_Model_SubscribeableInterface $object) {
		if ($object instanceof Tx_MmForum_Domain_Model_Forum_Topic) {
			$this->topicFavSubscriptions->attach($object);
		} elseif ($object instanceof Tx_MmForum_Domain_Model_Forum_Forum) {
			$this->forumFavSubscriptions->attach($object);
		}
	}



	/**
	 * Unsubscribes this user from a subscribeable object.
	 *
	 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
	 *                             The object that is to be unsubscribed.
	 * @return void
	 */
	public function removeFavSubscription(Tx_MmForum_Domain_Model_SubscribeableInterface $object) {
		if ($object instanceof Tx_MmForum_Domain_Model_Forum_Topic) {
			$this->topicFavSubscriptions->detach($object);
		} elseif ($object instanceof Tx_MmForum_Domain_Model_Forum_Forum) {
			$this->forumFavSubscriptions->detach($object);
		}
	}


	/**
	 * Subscribes this user to a subscribeable object, like a topic or a forum.
	 *
	 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
	 *                             The object that is to be subscribed. This may
	 *                             either be a topic or a forum.
	 * @return void
	 */
	public function addSubscription(Tx_MmForum_Domain_Model_SubscribeableInterface $object) {
		if ($object instanceof Tx_MmForum_Domain_Model_Forum_Topic) {
			$this->topicSubscriptions->attach($object);
		} elseif ($object instanceof Tx_MmForum_Domain_Model_Forum_Forum) {
			$this->forumSubscriptions->attach($object);
		}
	}



	/**
	 * Unsubscribes this user from a subscribeable object.
	 *
	 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
	 *                             The object that is to be unsubscribed.
	 * @return void
	 */
	public function removeSubscription(Tx_MmForum_Domain_Model_SubscribeableInterface $object) {
		if ($object instanceof Tx_MmForum_Domain_Model_Forum_Topic) {
			$this->topicSubscriptions->detach($object);
		} elseif ($object instanceof Tx_MmForum_Domain_Model_Forum_Forum) {
			$this->forumSubscriptions->detach($object);
		}
	}



	/**
	 * Adds a readable object to the list of objects read by this user.
	 *
	 * @param  Tx_MmForum_Domain_Model_ReadableInterface $readObject
	 *                             The object that is to be marked as read.
	 * @return void
	 */
	public function addReadObject(Tx_MmForum_Domain_Model_ReadableInterface $readObject) {
		if ($readObject instanceof Tx_MmForum_Domain_Model_Forum_Topic) {
			$this->readTopics->attach($readObject);
		}
	}



	/**
	 * Removes a readable object from the list of objects read by this user.
	 *
	 * @param  Tx_MmForum_Domain_Model_ReadableInterface $readObject
	 *                             The object that is to be marked as unread.
	 * @return void
	 */
	public function removeReadObject(Tx_MmForum_Domain_Model_ReadableInterface $readObject) {
		if ($readObject instanceof Tx_MmForum_Domain_Model_Forum_Topic) {
			$this->readTopics->detach($readObject);
		}
	}


	/**
	 * Add a private message
	 * @param $message Tx_MmForum_Domain_Model_User_PrivateMessages
	 */
	public function addPrivateMessage(Tx_MmForum_Domain_Model_User_PrivateMessages $message) {
		$this->privateMessages->attach($message);
	}

	/**
	 * Removes a private messages
	 * @param $message Tx_MmForum_Domain_Model_User_PrivateMessages
	 */
	public function removePrivateMessage(Tx_MmForum_Domain_Model_User_PrivateMessages $message) {
		$this->privateMessages->detach($message);
	}



	/**
	 * Decrease the user's post count.
	 * @return void
	 */
	public function decreasePostCount() {
		$this->postCount--;
	}



	/**
	 * Increase the user's post count.
	 * @return void
	 */
	public function increasePostCount() {
		$this->postCount++;
	}



	/**
	 * Decrease the user's topic count.
	 * @return void
	 */
	public function decreaseTopicCount() {
		$this->topicCount--;
	}



	/**
	 * Increase the user's topic count.
	 * @return void
	 */
	public function increaseTopicCount() {
		$this->topicCount++;
	}


	/**
	 * Decrease the user's question count.
	 * @return void
	 */
	public function decreaseQuestionCount() {
		$this->questionCount--;
	}



	/**
	 * Increase the user's question count.
	 * @return void
	 */
	public function increaseQuestionCount() {
		$this->questionCount++;
	}



	/**
	 * Increase the user's points.
	 * @param int $by The amount of points to be added
	 * @return void
	 */
	public function increasePoints($by) {
		$this->points = $this->points + $by;
		$currentRank = $this->getRank();
		$rank = $this->rankRepository->findRankByPoints($this->getPoints());
		if($rank !== NULL && $rank != $currentRank) {
			$this->setRank($rank[0]);
			$rank[0]->increaseUserCount();
			$currentRank->decreaseUserCount();
			$this->rankRepository->update($currentRank);
			$this->rankRepository->update($rank[0]);
		}
	}


	/**
	 * Decrease the user's points.
	 * @param int $by The amount of points to be removed
	 * @return void
	 */
	public function decreasePoints($by) {
		$this->points = $this->points - $by;
		$currentRank = $this->getRank();
		$rank = $this->rankRepository->findRankByPoints($this->getPoints());
		if($rank !== NULL && $rank != $currentRank) {
			$this->setRank($rank[0]);
			$rank[0]->increaseUserCount();
			$currentRank->decreaseUserCount();
			$this->rankRepository->update($currentRank);
			$this->rankRepository->update($rank[0]);
		}
	}



	/**
	 * Resets the whole contact data array of this user. This array will be stored in
	 * a JSON serialized format.
	 *
	 * @param  array $values All contact data of this user.
	 * @return void
	 */
	public function setContactData(array $values) {
		$this->contact = json_encode($values);
	}


	/**
	 * Sets a single contact data record. A contact data record can be unset by setting
	 * it to a empty or FALSE value.
	 *
	 * @param  $type  The contact record key (e.g. "twitter", "facebook", "icq", ...)
	 * @param  $value The new value. Set to a FALSE value to unset.
	 * @return void
	 */
	public function setContactDataItem($type, $value) {
		$contactData = $this->getContactData();
		if (!$value) {
			if (array_key_exists($type, $contactData)) {
				unset($contactData[$type]);
			}
		} else {
			$contactData[$type] = $value;
		}

		$this->contact = json_encode($contactData);
	}


	/**
	 * Set the rank of this user
	 * @param Tx_MmForum_Domain_Model_User_Rank $rank
	 */
	public function setRank(Tx_MmForum_Domain_Model_User_Rank $rank) {
		$this->rank = $rank;
	}





}
