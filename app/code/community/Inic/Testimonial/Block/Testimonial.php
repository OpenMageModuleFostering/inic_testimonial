<?php

class Inic_Testimonial_Block_Testimonial extends Mage_Core_Block_Template
{

    /**
     * Before rendering html, but after trying to load cache
     *

     */
     public function __construct() {  
        parent::__construct();   
        $collection = Mage::getModel('testimonial/testimonial')->getCollection() // for collection 
                         ->addFieldToFilter('status','1');
        $this->setTestimonials($collection);
        //var_dump((string)$collection->getSelect()); 
            
        $this->setFormAction(Mage::getUrl('*/*/save'));  
      }  
  
    protected function _beforeToHtml()
    {
          
        $this->_prepareCollection();
        return parent::_beforeToHtml();
    }

    /**
     * Prepare testimonial collection object
     *
     
     */
    protected function _prepareCollection()
    {
        $current_store = Mage::app()->getStore()->getId();
        $configTestyBlockValue = Mage::getStoreConfig('testimonialsection/testisettings/testynumbers');
        $configFeaturedTestyBlockValue = Mage::getStoreConfig('testimonialsection/testyblock/featuredtestynumbers');
        $pagerEnable = Mage::getStoreConfig('testimonialsection/testisettings/allowpagination');
        $collection = Mage::getModel("testimonial/testimonial")->getCollection()
                            ->addFieldToFilter('status','1');
         
                            
               /*  echo '<pre>';
                 print_r(get_class_methods($collection));exit;      */   
           if(!$this->getSidebar())  {
              if($pagerEnable != 1){    
                    if($configTestyBlockValue =='')
                     {
                         $collection->clear()->setPageSize(10);
                     }
                    else
                    {
                       $collection->clear()->setPageSize($configTestyBlockValue);
                    }
                }
                else{
                    $limit = $this->getRequest()->getParam('limit');
                    $pager = $this->getLayout()->createBlock('page/html_pager', 'testimonials.pager');
                     //$pager->setAvailableLimit(array(6=>6,12=>12,20=>20));
                      if(!empty($limit))
                         {
                             $collection->clear()->setPageSize($limit);
                             $pager->setCollection($collection); 
                         }
                      else
                         {
                             $collection->clear()->setPageSize(10);
                             $pager->setCollection($collection); 
                    }
                     $this->setChild('pager', $pager);
                } 
         }   
        if ($this->getSidebar()){
            $collection->addFieldToFilter('testimonial_sidebar', '1');
                  if($configFeaturedTestyBlockValue ==''){
                     $collection->clear()->setPageSize(5);
                 }
                 else{
                       $collection->clear()->setPageSize($configFeaturedTestyBlockValue);
                 }
        }
         $collection->setOrder('testimonial_position', 'DESC')
                   ->load();
        
          $testys = array();
        foreach ($collection as $testy) {
            $stores = explode(',',$testy->getStores());
            if (in_array(0,$stores) || in_array($current_store,$stores))
            //if ($banner->getStatus())
                $testys[] = $testy;
             
        }
        $this->setTestimonials($testys);
      
        return $this;
    }
    
     public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
  

    /**
     * Setter for limit items per page
     *
     * @param int $limit
     * @return Mage_Page_Block_Html_Pager
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }
     
     


}