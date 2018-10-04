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

require_once("../../config.php");
require_login();
require_once($CFG->dirroot . '/mod/certificate/lib.php');
require_once($CFG->dirroot.'/mod/certificate/locallib.php');

$id = required_param('certnumber', PARAM_ALPHANUM);   // Certificate code to verify.

$PAGE->set_pagelayout('standard');
$strverify = get_string('verifycertificate', 'block_verify_certificate');
$PAGE->set_url('/blocks/verify_certificate/index.php', array('certnumber' => $id));
$context = context_system::instance();
$PAGE->set_context($context);

// Print the header.
$PAGE->navbar->add($strverify);
$PAGE->set_title($strverify);
$PAGE->set_heading($strverify);
$PAGE->requires->css('/blocks/verify_certificate/printstyle.css');
$PAGE->requires->css('/blocks/verify_certificate/styles.css');
echo $OUTPUT->header();

$ufields = user_picture::fields('u');

$sql = "SELECT ci.code, ci.timecreated AS citimecreated,
               ci.certificateid, ci.userid,
               c.*, $ufields, u.id AS id, u.*
          FROM {certificate_issues} ci
    INNER JOIN {user} u
            ON u.id = ci.userid
    INNER JOIN {certificate} c
            ON c.id = ci.certificateid
         WHERE ci.code = ?";
$certificates = $DB->get_records_sql($sql, array($id));

if (! $certificates) {
    echo $OUTPUT->box_start('generalbox boxaligncenter');
    echo '<div id="block_verify_certificate"><br>';
    echo '<p class="notVerified">' . get_string('certificate', 'block_verify_certificate')
         . ' "' . $id . '" ' . '</p>';
    echo '<div class="wrapper-box"><br>';
    echo '<div class="left"><br>' .get_string('notfound', 'block_verify_certificate').'</div>';
    echo '<div class="right"><img src="pix/certnotverified.png" border="0" align="center"></div>';
    echo '</div></div>';
    echo $OUTPUT->box_end();
} else {
    echo $OUTPUT->box_start('generalbox boxaligncenter');
    echo "<a title=\""; print_string('printerfriendly', 'certificate');
    echo "\" href=\"#\" onclick=\"window.print ()\"><div class=\"printicon\">";
    echo "<img src=\"pix/printicon.png\" height=\"40\" width=\"40\" border=\"0\" align=\"right\"></img></a></div>";

    // Print Section.
    foreach ($certificates as $certdata) {
        echo '<p class = "verified">' . get_string('certificate', 'block_verify_certificate')
        . ' "' . $certdata->code . '" ' . '</font></b></p>';
        echo '<table width ="100%" cellpadding="5px"><tr><td>';
        echo '<div class="userprofilebox clearfix"><div class="profilepicture">';
        echo $OUTPUT->user_picture($certdata, array('size' => 150));
        echo '</div>';
        echo '</td><td>';
        echo '<p><b>' . get_string('to', 'block_verify_certificate') . ': </b>' . fullname($certdata) . '<br />';

        $course = $DB->get_record('course', array('id' => $certdata->course));
        if ($course) {
            echo '<p><b>' . get_string('course', 'block_verify_certificate') . ': </b>' . $course->fullname . '<br />';
        }

        // Date format.
        $dateformat = get_string('strftimedate', 'langconfig');

        // Modify printdate so that date is always printed.
        $certdata->printdate = 1;
        $certrecord = new stdClass();
        $certrecord->timecreated = $certdata->citimecreated;
        $certrecord->code = $certdata->code;
        $certrecord->userid = $certdata->userid;
        $userid = $certrecord->id = $certdata->id;

        // Retrieving grade and date for each certificate.
        $grade = certificate_get_grade($certdata, $course, $userid, $valueonly = true);
        $date = $certrecord->timecreated = $certdata->citimecreated;

        if ($date) {
            echo '<p><b>' . get_string('date', 'block_verify_certificate') . ': </b>' . userdate($date, $dateformat) . '<br /></p>';
        }
        if ($course && $certdata->printgrade > 0) {
            echo '<p><b>' . get_string('grade', 'block_verify_certificate') . ': </b>' . $grade . '<br /></p>';
        }
        echo '</td><td>';
        echo "<img src=\"pix/certverified.png\" border=\"0\" align=\"center\"></img>";
        echo '</td></tr></table></br>';
        echo '<p><b>' . get_string('check', 'block_verify_certificate') . '</b></p>';
    }
    echo $OUTPUT->box_end();
}
echo $OUTPUT->footer();
