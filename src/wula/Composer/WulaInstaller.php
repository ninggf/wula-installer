<?php

namespace wula\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * wula安装器.
 *
 * @package wula\Composer
 * @author  Leo Ning <windywany@gmail.com>
 * @since   1.0.0
 */
class WulaInstaller extends LibraryInstaller {
	const SUPPORT_TYPES = ['wula-module', 'wula-asset', 'wula-extension', 'wula-theme'];

	public function supports($packageType) {
		return in_array($packageType, self::SUPPORT_TYPES);
	}

	public function getInstallPath(PackageInterface $package) {
		$parent    = $this->composer->getPackage();
		$type      = $package->getType();
		$extraPath = $parent->getExtra();
		$extraPath = isset($extraPath['wula']) ? $extraPath['wula'] : [];
		$path      = isset($extraPath['wwwroot']) ? $extraPath['wwwroot'] : 'wwwroot';
		$type      = substr($type, 5) . 's-dir';
		if (isset($extraPath[ $type ])) {
			if ($type == 'extension') {
				$path = $extraPath[ $type ] . '/';
			} else {
				$path .= '/' . $extraPath[ $type ] . '/';
			}
		} elseif ($type == 'modules-dir') {
			$path .= '/modules/';
		} else if ($type == 'themes-dir') {
			$path .= '/themes/';
		} elseif ($type == 'assets-dir') {
			$path .= '/assets/';
		} else if ($type == 'extensions-dir') {
			$path = 'extensions/';
		}

		if ($type == 'extensions-dir' || $type == 'assets-dir') {
			return $path . $package->getPrettyName();
		} else {
			$pname = explode('/', $package->getPrettyName());

			return $path . array_pop($pname);
		}
	}
}