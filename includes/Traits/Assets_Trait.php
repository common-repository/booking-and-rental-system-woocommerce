<?php

namespace RnbLite\Traits;


trait Assets_Trait
{
    /**
     * Get front scripts
     *
     * @return array
     */
    public function get_front_scripts()
    {
        return [
            'jquery.datetimepicker.full' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/jquery.datetimepicker.full.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/jquery.datetimepicker.full.js'),
                'deps'    => ['jquery']
            ],
            'sweetalert.min' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/sweetalert.min.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/sweetalert.min.js'),
                'deps'    => ['jquery']
            ],
            'chosen.jquery' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/chosen.jquery.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/chosen.jquery.js'),
                'deps'    => ['jquery']
            ],
            'date' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/date.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/date.js'),
                'deps'    => ['jquery']
            ],
            'accounting' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/accounting.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/accounting.js'),
                'deps'    => ['jquery']
            ],
            'jquery.flip' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/jquery.flip.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/jquery.flip.js'),
                'deps'    => ['jquery']
            ],
            'main-script' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/main-script.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/main-script.js'),
                'deps'    => ['jquery']
            ],
            'cost-handle' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/cost-handle.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/cost-handle.js'),
                'deps'    => ['jquery']
            ]
        ];
    }

    /**
     * Get Styles
     *
     * @return array
     */
    public function get_front_styles()
    {
        return [
            'fontawesome' => [
                'src'     => REDQ_ROOT_URL . '/assets/css/fontawesome.min.css',
                'version' => filemtime(RNB_LITE_PATH . '/assets/css/fontawesome.min.css')
            ],
            'sweetalert' => [
                'src'     => REDQ_ROOT_URL . '/assets/css/sweetalert.css',
                'version' => filemtime(RNB_LITE_PATH . '/assets/css/sweetalert.css')
            ],
            'chosen' => [
                'src'     => REDQ_ROOT_URL . '/assets/css/chosen.css',
                'version' => filemtime(RNB_LITE_PATH . '/assets/css/chosen.css')
            ],
            'rental-style-two' => [
                'src'     => REDQ_ROOT_URL . '/assets/css/rental-style-two.css',
                'version' => filemtime(RNB_LITE_PATH . '/assets/css/rental-style-two.css')
            ],
            'jquery.datetimepicker' => [
                'src'     => REDQ_ROOT_URL . '/assets/css/jquery.datetimepicker.css',
                'version' => filemtime(RNB_LITE_PATH . '/assets/css/jquery.datetimepicker.css')
            ]
        ];
    }

    /**
     * Get admin scripts
     *
     * @return array
     */
    public function get_admin_scripts()
    {
        return [
            'redq-admin' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/redq-admin.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/redq-admin.js'),
                'deps'    => ['jquery']
            ],
            'icon-picker' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/icon-picker.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/icon-picker.js'),
                'deps'    => ['jquery']
            ],
            'rnb-lite-writepanel' => [
                'src'     => REDQ_ROOT_URL . '/assets/js/writepanel.js',
                'version' => filemtime(RNB_LITE_PATH . '/assets/js/writepanel.js'),
                'deps'    => ['jquery']
            ],
        ];
    }

    /**
     * Get admin Styles
     *
     * @return array
     */
    public function get_admin_styles()
    {
        return [
            'fontawesome' => [
                'src'     => REDQ_ROOT_URL . '/assets/css/fontawesome.min.css',
                'version' => filemtime(RNB_LITE_PATH . '/assets/css/fontawesome.min.css')
            ],
            'redq-admin' => [
                'src'     => REDQ_ROOT_URL . '/assets/css/redq-admin.css',
                'version' => filemtime(RNB_LITE_PATH . '/assets/css/redq-admin.css')
            ],
        ];
    }

}
