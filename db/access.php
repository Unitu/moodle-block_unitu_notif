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
 * Unitu Notification block capabilities.
 *
 * @package    block_unitu_notif
 * @copyright  2024 Mahendra Soni <ms@taketwotechnologies.com> {@link https://taketwotechnologies.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$capabilities = array(
        'block/unitu_notif:addinstance' => array(
                'captype' => 'write',
		        'contextlevel' => CONTEXT_SYSTEM,
		        'archetypes' => array(
		            'user' => CAP_ALLOW
		        ),
                'clonepermissionsfrom' => 'moodle/site:manageblocks'
        ),
        'block/unitu_notif:myaddinstance' => array(
                'captype'      => 'read',
                'contextlevel' => CONTEXT_SYSTEM,
                'archetypes'   => array(
                        'user' => CAP_ALLOW,
                ),
                'clonepermissionsfrom' => 'moodle/my:manageblocks'
        ),
);