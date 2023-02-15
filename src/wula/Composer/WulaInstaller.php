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
        $pname     = explode('/', $package->getPrettyName());
        $pname     = array_pop($pname);

        if (isset($extraPath[ $type ])) {
            if ($type == 'extensions-dir' || $type == 'modules-dir' || $type == 'themes-dir') {
                $path = $extraPath[ $type ] . '/';
            } else {
                $path .= '/' . $extraPath[ $type ] . '/';
            }
        } else if ($type == 'modules-dir') {
            $path  = 'modules/';
            $pname = str_replace('.', '/', $pname);//支持子模块
        } else if ($type == 'themes-dir') {
            $path = 'themes/';
        } else if ($type == 'assets-dir') {
            $path .= '/assets/';
        } else if ($type == 'extensions-dir') {
            $path = 'extensions/';
        }

        if ($type == 'extensions-dir') {
            return $path . $package->getPrettyName();
        } else {
            return $path . $pname;
        }
    }
}