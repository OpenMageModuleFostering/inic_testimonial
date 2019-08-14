<?php
/**
 * 
 * @package    Inic_Testimonial
 * @author     Indianic 
 */
class Inic_Testimonial_IndexController extends Mage_Core_Controller_Front_Action
{
   
    const XML_PATH_EMAIL_SENDER     = 'testimonialsection/testisettings/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'testimonialsection/testisettings/email_template';
    const XML_PATH_EMAIL_RECIPIENT  = 'testimonialsection/testisettings/recipient';
    
    public function indexAction()
    {
      
        $this->loadLayout();
        $this->renderLayout();
    }
    
      public function saveAction()
    {  
      
        
        if ($this->getRequest()->getPost()) {
            
            try {
                $data = $this->getRequest()->getPost();
                $postObject = new Varien_Object();
                $postObject->setData($data);
                 $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);

                $fileName = '';
                if (isset($_FILES['testimonial_img']['name']) and (file_exists($_FILES['testimonial_img']['tmp_name']))) {
                        $baseDir = Mage::getBaseDir('media').DS.'testimonials';
                        $file = new Varien_Io_File();
                        mkdir($baseDir,0777);    
                     $fileName       = $_FILES['testimonial_img']['name'];
                        mysql_real_escape_string($fileName);
                        $tmp = explode(".",$fileName);
                       
                        $fileName = time().uniqid().'.'.end($tmp);
                        $image_to_upload = "";
                        $uploader = new Varien_File_Uploader('testimonial_img');
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);
                        $path = Mage::getBaseDir('media') . DS.'testimonials'.DS ;
                        $sitePath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'testimonials';
                        $uploader->save($path, $fileName);
                        $data['testimonial_img'] = $sitePath.'/'.$fileName;
                    
                } 
                $model = Mage::getModel('testimonial/testimonial');
                $numberOfData = $model->getCollection()->count();
              
                $model->setData($data)->setTestimonialId($this->getRequest()->getParam('id'));
                  if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                   
                    $model->setUpdateTime(now());
                } 
                
                $store= Mage::app()->getStore();
                $current_store = $store->getId();   
               
                $configContactEmailValue = Mage::getStoreConfig('testimonialsection/showhideform/contactemail');
                $configEmailToAdmin = Mage::getStoreConfig('testimonialsection/testisettings/allowemail');
                        if($configContactEmailValue == 1 && $configEmailToAdmin == 1 ){
                                $mailTemplate = Mage::getModel('core/email_template');
                                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                               
                                $mailTemplate->setDesignConfig(array('area' => 'frontend','store'=>$current_store))
                                    ->setReplyTo($data['testimonial_email'])
                                    ->sendTransactional(
                                    Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                                    Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                    Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                                    null,
                                    array('data' => $postObject)
                            );
                        }
                $model->setStores($current_store);
                $status = Mage::helper('testimonial')->getStatusAprroval();
                
                $model->setStatus($status);
                $model->setTestimonialPosition($numberOfData+1);              
                $model->save();
                Mage::getSingleton('core/session')->addSuccess(Mage::helper('testimonial')->__('Your inquiry was submitted and will be responded to as soon as possible.'));
                 $this->_redirect('*/*');
                return;
            } catch (Exception $e) {
                Mage::getModel('core/session')->setData('submited', '0');
                  $e->getMessage();exit;
                Mage::getSingleton('core/session')->addError(Mage::helper('testimonial')->__('Unable to submit your request. Please, try again later'));
                $this->_redirect('*/*');
                return;
                /* echo $e->getMessage();*/
            }
        }else{
            Mage::getSingleton('core/session')->addError(Mage::helper('testimonial')->__('There is No Data'));
            $this->_redirect('*/*');
            return;
        }       
    }
    
 
    
    
}
