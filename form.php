<?php
require_once "$CFG->dirroot/lib/formslib.php";

class local_vmchat_form extends moodleform {

    function definition()
    {
        $mform =& $this->_form;    
        $mform->addElement('checkbox', 'enablevmchat', get_string('enablevmchat', 'local_vmchat'));    
        
                    
        //$mform->setType('firstname', PARAM_TEXT);
        $mform->setDefault('enablevmchat',$this->_customdata['enablevmchat']);
        $this->add_action_buttons($cancel = false);
    }    
}
