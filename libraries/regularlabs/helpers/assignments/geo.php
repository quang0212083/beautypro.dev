<?php
/**
 * @package         Regular Labs Library
 * @version         16.7.11864
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2016 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class RLAssignmentsGeo extends RLAssignment
{
	var $geo = null;

	/**
	 * passContinents
	 */
	public function passContinents()
	{
		if (!$this->getGeo() || empty($this->geo->continentCode))
		{
			return $this->pass(false);
		}

		return $this->passSimple($this->geo->continentCode);
	}

	/**
	 * passCountries
	 */
	public function passCountries()
	{
		if (!$this->getGeo() || empty($this->geo->countryCode))
		{
			return $this->pass(false);
		}

		return $this->passSimple($this->geo->countryCode);
	}

	/**
	 * passRegions
	 */
	public function passRegions()
	{
		if (!$this->getGeo() || empty($this->geo->countryCode) || empty($this->geo->regionCodes))
		{
			return $this->pass(false);
		}

		$regions = $this->geo->regionCodes;
		array_walk($regions, function (&$value)
		{
			$value = $this->geo->countryCode . '-' . $value;
		});

		return $this->passSimple($regions);
	}

	/**
	 * passPostalcodes
	 */
	public function passPostalcodes()
	{
		if (!$this->getGeo() || empty($this->geo->postalCode))
		{
			return $this->pass(false);
		}

		// replace dashes with dots: 730-0011 => 730.0011
		$postalcode = str_replace('-', '.', $this->geo->postalCode);

		return $this->passInRange($postalcode);
	}

	public function getGeo($ip = '')
	{
		if ($this->geo !== null)
		{
			return $this->geo;
		}

		if (!file_exists(JPATH_LIBRARIES . '/geoip/geoip.php'))
		{
			return false;
		}

		require_once JPATH_LIBRARIES . '/geoip/geoip.php';

		$geo = new GeoIp($ip);

		$this->geo = $geo->get();

		return $this->geo;
	}
}
