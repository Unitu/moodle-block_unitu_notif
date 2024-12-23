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
 * Contains the class for the "Unitu Notification" block.
 *
 * @package    block_unitu_notif
 * @copyright  2024 Yacoub Badran <yacoub@unitu.co.uk> {@link https://unitu.co.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_unitu_notif extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_unitu_notif');
    }

    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    public function instance_allow_config() {
        return true;
    }

    public function applicable_formats() {
        return array(
                'admin' => false,
                'site-index' => true,
                'course-view' => true,
                'mod' => false,
                'my' => true
        );
    }

    public function specialization() {
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_unitu_notif');
        } else {
            $this->title = $this->config->title;
        }
    }

    public function get_content() {
        global $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->config)) {
            $this->config = new stdClass();
        }

        $this->content = new stdClass();
        $posts = [];

        $contentdata = \block_unitu_notif\api::unitu_api();
        if (isset($contentdata['error'])) {
            return $this->content;
        }
        if (empty($contentdata)) {
            return null;
        }

        $universitydomain = $contentdata['UniversityDomain'];
        foreach ($contentdata['Posts'] as $item) {
            list($truncatedDescription, $isTruncated) = $this->truncate($item['Description'], 50);
            $departments = implode(' | ', $item['Departments']);
            if (mb_strlen($departments) > 80) {
                $departments = mb_substr($departments, 0, 80) . '..';
            } else {
                $departments = $departments;
            }
            $posts[] = [
                    'userimage' => $item['Avatar'] ?: 'https://via.placeholder.com/40',
                    'username' => $item['FullName'],
                    'userrole' => $item['UniversityTitle'],
                    'date' => $item['DateSince'],
                    'title' => $item['Title'],
                    'content' => $truncatedDescription,
                    'fullcontent' => $item['Description'],
                    'readmorelink' => $isTruncated,
                    'likes' => $item['Likes'],
                    'url' => $item['Url'],
                    'departments' => $departments
            ];
        }

        $template_data = [
                'posts' => $posts
        ];
        $this->content->text = $OUTPUT->render_from_template('block_unitu_notif/notifications', $template_data);
        $image_url = new moodle_url('/blocks/unitu_notif/pix/unitu-logo.png');
        $this->content->footer = 'Powered by <img src="' . $image_url . '" alt="Unitu Logo">
        <a href="' . $universitydomain . '" target="_blank"> Unitu</a>';

        return $this->content;
    }

    private function truncate($text, $max_words) {
        $words = explode(' ', $text);
        if (count($words) > $max_words) {
            $text = implode(' ', array_slice($words, 0, $max_words));
            return [$text, true];
        }
        return [$text, false];
    }
}
