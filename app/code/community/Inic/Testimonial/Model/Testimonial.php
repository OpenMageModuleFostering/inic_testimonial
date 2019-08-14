<?php
/**
 * 
 * @package    Inic_Testimonial
 * @author     Indianic 
 */

class Inic_Testimonial_Model_Testimonial extends Mage_Core_Model_Abstract
{
       
      
  
    public function _construct()
    {
        parent::_construct();
        $this->_init('testimonial/testimonial');
    }
    
   

}
