<?php

namespace wula\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class WulaComposerPlugin implements PluginInterface {
    private $installer;

    public function activate(Composer $composer, IOInterface $io) {
        $installer = new WulaInstaller($io, $composer);
        $im        = $composer->getInstallationManager();
        $im->addInstaller($installer);
        $this->installer = $installer;
    }

    public function deactivate(Composer $composer, IOInterface $io) {
        #理论上不能停用
    }

    public function uninstall(Composer $composer, IOInterface $io) {
        #$composer->getInstallationManager()->removeInstaller($this->installer);
    }
}