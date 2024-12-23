<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * API methods to call "Together We Changed" service
 *
 * @package    block_unitu_notif
 * @subpackage unitu_notif
 * @copyright  2024 Yacoub Badran <yacoub@unitu.co.uk> {@link https://unitu.co.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_unitu_notif;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/filelib.php');

use lang_string;
use curl;

class api {

    /** @var API key */
    private static $key = null;

    /**
     * Initialize Endpoint
     *
     * @return null
     */
    public static function init() {
        self::$key = get_config('block_unitu_notif', 'key');
    }

    /**
     * Get the key
     *
     * @return string
     */
    public static function get_key() {
        return self::$key;
    }

    /**
     * Validate the api
     *
     * @return array
     */
    public static function unitu_api() {
        self::init();
        $plugin = \core_plugin_manager::instance()->get_plugin_info('block_unitu_notif');
        $release = str_replace("v", "", $plugin->release);
        $moodle_v = get_config('moodle', 'release');
        $version = explode(' ', $moodle_v)[0];

        $curl = new \curl();
        $url = 'https://api.unitu.co.uk/v1/public/TogetherWeChanged?vle=Moodle&vleVersion=' . $version . '&pluginVersion=' .
                $release;
        $key = self::$key;
        $header = ['API-KEY: ' . $key];
        $curl->setHeader($header);
        try {
            $result = $curl->get($url);
            $response = json_decode($result, true);
            if (isset($response['status']) && $response['status'] == 401) {
                return ['error' => get_string('apikeywarning', 'block_unitu_notif')];
            }
            return $response;
        } catch (\Exception $e) {
            return ['error' => get_string('warning', 'block_unitu_notif')];
        }
    }
}