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
 * Version details
 *
 * Verify certificate block
 * --------------------------
 * Verify certificate based on the unique codes displayed on issued certificates.
 * Full details of the issued certificate is displayed including profile picture.
 * Mostly cosmetic changes to the original codes from Jean-Michel Védrine.
 * Original Autor & Copyright - Jean-Michel Védrine | 2014
 *
 * @copyright           2015 onwards Manieer Chhettri | Marie Curie, UK | <manieer@gmail.com>
 * @author              Manieer Chhettri | Marie Curie, UK | <manieer@gmail.com> | 2015
 * @package             block_verify_certificate
 * @license             http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
$settings->add(new admin_setting_configcheckbox('block_verify_certificate_requirelogin',
						get_string('requirelogin', 'block_verify_certificate'),
						get_string('descrequirelogin', 'block_verify_certificate'),
						1));
