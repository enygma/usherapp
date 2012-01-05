<?php

namespace Lib;

class Validate
{
    private $_rules     = array();

    private $_messages  = array();

    private $_status    = array();

    public function __construct()
    {
    }

    public function addRule($fieldName,$ruleType,$options=null)
    {
        $validateMethod = '_validate'.ucwords(strtolower($ruleType));
        if (method_exists($this, $validateMethod)) {
            $this->_rules[$fieldName][] = strtolower($ruleType);
        } else {
            throw new \Exception('Validation not found for type "'.$ruleType.'"');
        }
    }

    /**
     * Run the current rules against the data given
     *
     */
    public function validate($data)
    {
        $validationPass = true;

        foreach ($data as $fieldName => $fieldValue) {
            // see if we have rules for this index
            if (isset($this->_rules[$fieldName])) {
                foreach ($this->_rules[$fieldName] as $ruleType) {
                    $validateMethod = '_validate'.ucwords(strtolower($ruleType));
                    if ($this->$validateMethod($fieldValue) == false) {
                        $this->_status[$fieldName] = 'fail'; 
                        $this->_messages[$fieldName] = 'Invalid value for "'.$fieldName.'"';
                        $validationPass = false;
                    }
                }
            } else {
                $this->_status[$fieldName] = 'pass';
            }
        }
        return $validationPass;
    }
    
    public function getMessages()
    {
        return $this->_messages;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    //------------------------
    // Validation types

    private function _validateEmail($value)
    {
        return (filter_var($value,FILTER_VALIDATE_EMAIL) == false) ? false : true;
    }
    private function _validateUrl($value)
    {
        return (filter_var($value,FILTER_VALIDATE_URL) == false) ? false : true;
    }
}

