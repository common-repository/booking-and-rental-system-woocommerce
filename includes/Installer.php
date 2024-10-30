<?php

namespace RnbLite;

/**
 * Installer Class
 */
class Installer
{
    /**
     * Initialize class functions
     *
     * @return void
     */
    public function run()
    {
        $this->add_version();
    }

    /**
     * Store plugin information
     *
     * @return void
     */
    public function add_version()
    {
        $installed = get_option('rnb_lite_installed');
        if (!$installed) {
            update_option('rnb_lite_installed', time());
        }
        update_option('rnb_lite_version', RNB_LITE_VERSION);
    }

}