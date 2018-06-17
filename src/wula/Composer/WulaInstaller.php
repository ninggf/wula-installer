<?php

namespace wula\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

/**
 * wula安装器.
 *
 * @package wula\Composer
 * @author  Leo Ning <windywany@gmail.com>
 * @version 2.0.0
 * @since   1.0.0
 */
class WulaInstaller extends LibraryInstaller {
	const SUPPORT_TYPES = [
		'wula-module',
		'wula-asset',
		'wula-extension',
		'wula-theme'
	];
	private $assetsDir = '';

	public function supports($packageType) {
		return in_array($packageType, self::SUPPORT_TYPES);
	}

	public function getInstallPath(PackageInterface $package) {
		$parent          = $this->composer->getPackage();
		$type            = $package->getType();
		$myExtra         = $package->getExtra();
		$extraPath       = $parent->getExtra();
		$extraPath       = isset($extraPath['wula']) ? $extraPath['wula'] : [];
		$path            = isset($extraPath['wwwroot']) ? $extraPath['wwwroot'] : 'wwwroot';
		$type            = substr($type, 5) . 's-dir';
		$this->assetsDir = $path . '/assets/';
		if (isset($extraPath[ $type ])) {
			if ($type == 'extensions-dir' || $type == 'modules-dir' || $type == 'themes-dir') {
				$path = $extraPath[ $type ] . '/';
			} else {
				$path .= '/' . $extraPath[ $type ] . '/';
			}
		} else if ($type == 'modules-dir') {
			$path = 'modules/';
		} else if ($type == 'themes-dir') {
			$path = 'themes/';
		} else if ($type == 'assets-dir') {
			$path .= '/assets/';
		} else if ($type == 'extensions-dir') {
			$path = 'extensions/';
		}

		$pname           = explode('/', $package->getPrettyName());
		$pname           = array_pop($pname);
		$assetDir        = isset($myExtra['assetDir']) && $myExtra['assetDir'] ? $myExtra['assetDir'] : $pname;
		$this->assetsDir .= $assetDir;
		if ($type == 'extensions-dir') {
			return $path . $package->getPrettyName();
		} else {
			return $path . $pname;
		}
	}

	public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
		parent::install($repo, $package);
		$this->installAssets($package);
	}

	public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target) {
		parent::update($repo, $initial, $target);
		$this->installAssets($target);
	}

	public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package) {
		parent::uninstall($repo, $package);
		$type = $package->getType();
		if ($type != 'wula-asset') {
			$dpath = $this->assetsDir;
			if (is_dir($dpath)) {
				$this->filesystem->removeDirectoryPhp($dpath);
			}
		}
	}

	private function installAssets(PackageInterface $package) {
		$type = $package->getType();
		if ($type != 'wula-asset') {
			$path      = $this->getInstallPath($package);
			$assetsDir = $path . '/assets/';
			if (is_dir($assetsDir) && file_exists($assetsDir)) {
				if (is_dir($this->assetsDir) || @mkdir($this->assetsDir, 0755, true)) {
					$this->filesystem->copy($assetsDir, $this->assetsDir);
				}
			}
		}
	}
}