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

/*
 * @package    course
 * @subpackage publish
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 * On this page the user selects where he wants to publish the course
*/

require('../../config.php');

require_once($CFG->dirroot.'/lib/hublib.php');
require_once($CFG->dirroot.'/course/publish/forms.php');

$id = optional_param('id', 0, PARAM_INT);
$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);
require_login($course);

$PAGE->set_url('/course/publish/hubselector.php', array('id' => $course->id));
$PAGE->set_pagelayout('course');
$PAGE->set_title(get_string('course') . ': ' . $course->fullname);
$PAGE->set_heading($course->fullname);


$share = optional_param('share', false, PARAM_BOOL);
$advertise = optional_param('advertise', false, PARAM_BOOL);
$hubselectorform = new hub_publish_selector_form('',
        array('id' => $id, 'share' => $share, 'advertise' => $advertise));
$fromform = $hubselectorform->get_data();

//// Redirect to the registration form if an URL has been choosen ////
$huburl = optional_param('huburl', false, PARAM_URL);

//redirect
if (!empty($huburl) and confirm_sesskey()) {
    $hubname = optional_param(clean_param($huburl, PARAM_ALPHANUMEXT), '', PARAM_TEXT);
    $params = array('sesskey' => sesskey(), 'id' => $id,
            'huburl' => $huburl, 'hubname' => $hubname, 'share' => $share, 'advertise' => $advertise);
    redirect(new moodle_url($CFG->wwwroot."/course/publish/metadata.php",
            $params));
}


//// OUTPUT ////


echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('publishon', 'hub'), 3, 'main');
$hubselectorform->display();
echo $OUTPUT->footer();