<?php

namespace wula\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class WulaComposerPlugin implements PluginInterface {
	public function activate(Composer $composer, IOInterface $io) {
		$installer = new WulaInstaller($io, $composer);
		$im        = $composer->getInstallationManager();
		$im->addInstaller($installer);
	}
}