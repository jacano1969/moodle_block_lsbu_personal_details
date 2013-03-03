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


defined('MOODLE_INTERNAL') || die();

class block_lsbu_personal_details extends block_base {
    /**
     * block initializations
     */
    public function init() {
        $this->title   = get_string('pluginname', 'block_lsbu_personal_details');
    }

    /**
     * block contents
     *
     * @return object
     */
    public function get_content() {
        global $CFG, $USER, $COURSE, $DB, $OUTPUT, $PAGE;

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (!isloggedin() or isguestuser()) {
            return '';      
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        $course = $this->page->course;

        // username firstname surname
        $content = html_writer::start_tag('div', array('class'=>'username'));
        $content .= $USER->username . ' (' . $USER->firstname . ', ' . $USER->lastname . ')';
        $content .= html_writer::end_tag('div');
        
        // student id / staff number
        $content .= html_writer::start_tag('div', array('class'=>'idnumber'));
        $content .= $USER->idnumber;
        $content .= html_writer::end_tag('div');
        
        
        if($this->isStudent($USER->username)==true) {
            // course enrolments
    
            // custom links
            $content .= html_writer::start_tag('p');
            $content .= html_writer::link('https://my.lsbu.ac.uk/page/faculty-offices-ahs', get_string('faculty-offices-ahs', 'block_lsbu_personal_details'),array('class' => 'external_link'));
            $content .= html_writer::empty_tag('br');
            $content .= html_writer::link('https://my.lsbu.ac.uk/page/faculty-offices-bus', get_string('faculty-offices-bus', 'block_lsbu_personal_details'),array('class' => 'external_link'));
            $content .= html_writer::empty_tag('br');
            $content .= html_writer::link('https://my.lsbu.ac.uk/page/faculty-offices-esbe', get_string('faculty-offices-esbe', 'block_lsbu_personal_details'),array('class' => 'external_link'));
            $content .= html_writer::empty_tag('br');
            $content .= html_writer::link('https://my.lsbu.ac.uk/page/faculty-offices-hsc', get_string('faculty-offices-hsc', 'block_lsbu_personal_details'),array('class' => 'external_link'));
            $content .= html_writer::end_tag('p');
            
            // Messaging announcements– a Moodle link
            $content .= html_writer::start_tag('p');
            $content .= html_writer::link('/moodle/message/index.php?viewing=recentnotifications', get_string('message_announcements', 'block_lsbu_personal_details'),array('class' => 'announcements'));
            $content .= html_writer::end_tag('p');
        }
        
        $this->content->text = $content;

        return $this->content;
    }

    /**
     *
     * function to check if the logged in user is a student
     *
     */
    private function isStudent($username)
    {
        global $DB;
        
        // TODO get database name from db extended config plugins setting
        $sql="SELECT role FROM mis_lsbu.moodle_users where username='$username'";
        
        $roles = array();
        
        $roles = $DB->get_records_sql($sql ,null);
        
        foreach($roles as $role)
        {
            if(!empty($role->role))
            {
                return true;    
            }
        }
        
        return false;    
    }
    
    /**
     * allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return false;
    }

    /**
     * allow more than one instance of the block on a page
     *
     * @return boolean
     */
    public function instance_allow_multiple() {
        //allow more than one instance on a page
        return false;
    }

    /**
     * allow instances to have their own configuration
     *
     * @return boolean
     */
    function instance_allow_config() {
        //allow instances to have their own configuration
        return false;
    }

    /**
     * instance specialisations (must have instance allow config true)
     *
     */
    public function specialization() {
    }

    /**
     * displays instance configuration form
     *
     * @return boolean
     */
    function instance_config_print() {
        return false;
    }

    /**
     * locations where block can be displayed
     *
     * @return array
     */
    public function applicable_formats() {
        return array('all'=>true);
    }

    /**
     * post install configurations
     *
     */
    public function after_install() {
    }

    /**
     * post delete configurations
     *
     */
    public function before_delete() {
    }

}
