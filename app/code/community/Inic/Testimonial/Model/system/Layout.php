<?php

class Inic_Testimonial_Model_System_Layout
{
    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options =  array(
            array('value' => 1,'label' => Mage::helper('testimonial')->__('Right')),
            array('value' => 0,'label' => Mage::helper('testimonial')->__('Left')),
        );
        }
        return $this->_options;
    }
}