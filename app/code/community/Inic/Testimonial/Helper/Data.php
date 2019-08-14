<?php
/**
 * 
 * @package    Inic_Testimonial
 * @author     Indianic 
 */
class Inic_Testimonial_Helper_Data extends Mage_Core_Helper_Abstract
{
      const ROUTE_TESTIMONIAL = 'testimonials';
    
    /**
     * Get name of the extension
     *
     * @return string - name
     */
    public function getTranslatedExtensionName()
    {
        return $this->__('Testimonials');
    }
     public function getUserName()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim($customer->getName());
    }

    public function getUserEmail()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }
    
    public function getTitle()
    {
         $configTitleValue = Mage::getStoreConfig('testimonialsection/testisettings/title');
        if ($configTitleValue=='') {
            return 'Testimonials';
        }
        else{
            return $configTitleValue;
        }
    }
     public function getFeaturedTitle()
    {
         $configTitleValue = Mage::getStoreConfig('testimonialsection/testyblock/featuredtitle');
        if ($configTitleValue=='') {
            return 'Featured Testimonials';
        }
        else{
            return $configTitleValue;
        }
    }
     public function getStatusAprroval()
    {
         $configStatusAprroveValue = Mage::getStoreConfig('testimonialsection/testisettings/adminapproval');
       
         $status ="";
        if ($configStatusAprroveValue == 0) {
            $status = 1;
            return (int)$status;
        }
        else{
           $status = 2;
            return (int)$status;
        }
    }
    
    public function getTestimonialUrl()
    {
        return $this->_getUrl(self::ROUTE_TESTIMONIAL);
    }
    
     public function resizeImage($imageName, $width=NULL, $height=NULL, $imagePath=NULL)
    {
            $imagePath = str_replace("/", DS, $imagePath);
            $imagePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $imageName;
            
            if($width == NULL && $height == NULL) {
            $width = 100;
            $height = 100;
            }
            $resizePath = $width . 'x' . $height;
            $resizePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $resizePath . DS . $imageName;
            
            if (file_exists($imagePathFull) && !file_exists($resizePathFull)) {
            $imageObj = new Varien_Image($imagePathFull);
            $imageObj->constrainOnly(TRUE);
            $imageObj->keepAspectRatio(TRUE);
            $imageObj->resize($width,$height);
            $imageObj->save($resizePathFull);
            }
            
            $imagePath=str_replace(DS, "/", $imagePath);
            return Mage::getBaseUrl("media") . $imagePath . "/" . $resizePath . "/" . $imageName;
      }
       public function isEnableContactName()
        {
             $configContactNameValue = Mage::getStoreConfig('testimonialsection/showhideform/contactname');
             return $configContactNameValue;
        }
         public function isEnableCompanyname()
        {
             $configCompanyNameValue = Mage::getStoreConfig('testimonialsection/showhideform/companyname');
             return $configCompanyNameValue;
        }
        public function isEnableContactEmail()
        {
             $configContactEmailValue = Mage::getStoreConfig('testimonialsection/showhideform/contactemail');
             return $configContactEmailValue;
        } 
        
        public function isEnableSidebar()
        {
              $configFeatueredValue = Mage::getStoreConfig('testimonialsection/testyblock/featured');
             return $configFeatueredValue;
        }
        public function isEnableContactPhoto()
        { 
            $configContactPhotoValue = Mage::getStoreConfig('testimonialsection/showhideform/contactphoto');
             return $configContactPhotoValue;
        } 
        
        public function isEnableContactAddress()
        { 
              $configAddressValue = Mage::getStoreConfig('testimonialsection/showhideform/address');
             return $configAddressValue;
        }
         public function isEnableFrontend()
        { 
              $configEnableValue = Mage::getStoreConfig('testimonialsection/testisettings/activetesty');
             return $configEnableValue;
        } 
        
         public function isEnableGuest()
        { 
              $enableguest = Mage::getStoreConfig('testimonialsection/testisettings/enableguest');
             return $enableguest;
        }
       




  
}
