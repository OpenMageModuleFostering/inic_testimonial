<?php
/**
 * 
 * @package    Inic_Testimonial
 * @author     Indianic 
 */
class Inic_Testimonial_Adminhtml_TestimonialController extends Mage_Adminhtml_Controller_Action
{

  
	protected function _initAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('inic/testimonial');
		$this->_addBreadcrumb(Mage::helper('testimonial')->__('Testimonials'), Mage::helper('testimonial')->__('Testimonials'));
	}

    /**
     * View grid action
     */
	public function indexAction()
	{
		$this->_initAction();
		$this->renderLayout();
	}

    /**
     * View edit form action
     */
	public function editAction()
	{
      
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('testimonial/adminhtml_testimonial_edit'));
		$this->renderLayout();
	}

    /**
     * View new form action
     */
	public function newAction()
	{
		$this->editAction();
	}
  

    /**
     * Save form action
     */
	public function saveAction()
	{
        
		if ($this->getRequest()->getPost()) {
            
			try {
				$data = $this->getRequest()->getPost();
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
                } else {
                     
                    if(isset($data['testimonial_img']['delete']) && $data['testimonial_img']['delete'] == 1) {
                        $data['testimonial_img'] = '';
                        $path = Mage::getBaseDir('media') . DS . 'testimonials';
                        $model = Mage::getModel('testimonial/testimonial');
                          $resizeEndarray = explode("/",$model->load($this->getRequest()->getParam('id'))->getTestimonialImg());
                          unlink($path.'/'.end($resizeEndarray)); //delete it
                    } else {
                        unset($data['testimonial_img']);
                       
                    }
                }

				$model = Mage::getModel('testimonial/testimonial');
              
              
				$model->setData($data)->setTestimonialId($this->getRequest()->getParam('id'));
                  if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                   
                    $model->setUpdateTime(now());
                }    
                 $model->setStores(implode(',',$data['stores']));
              
                $model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('testimonial')->__('Testimonial was successfully saved'));
                    if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}

		$this->_redirect('*/*/');
	}

    /**
     * Delete action
     */
	public function deleteAction()
	{
		if ($this->getRequest()->getParam('id') > 0) {
			try {
				$model = Mage::getModel('testimonial/testimonial');
                $imageId= $this->getRequest()->getParam('id');
                $path = Mage::getBaseDir('media') . DS . 'testimonials';
                $resizeEndarray = explode("/",$model->load($imageId)->getTestimonialImg());
                unlink($path.'/'.end($resizeEndarray)); //delete it
                $model->setTestimonialId($imageId)->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('testimonial')->__('Testimonial was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}

		$this->_redirect('*/*/');
	}

    /**
     * Check allow or not access to ths page
     *
     * @return bool - is allowed to access this menu
     */
	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('inic/testimonial');
	}
    
    
    
   

    public function massDeleteAction() {
        $testimonialsIds = $this->getRequest()->getParam('testimonial');
     
        if(!is_array($testimonialsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                
                foreach ($testimonialsIds as $testimonialsId) {
                    $testimonials = Mage::getModel('testimonial/testimonial')->load($testimonialsId);
                    $path = Mage::getBaseDir('media') . DS . 'testimonials';
                   /* $filename = basename($testimonials->getTestimonialImg()); //get the filename
                    unlink($path.'/'.$filename); //delete it */
                    $resizeEndarray = explode("/",$testimonials->getTestimonialImg());
                    unlink($path.'/'.end($resizeEndarray)); //delete it
                    $testimonials->delete();
                }
               
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($testimonialsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    public function massStatusAction()
    {
        $testimonialsIds = $this->getRequest()->getParam('testimonial');
        if(!is_array($testimonialsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($testimonialsIds as $testimonialsId) {
                    $testimonials = Mage::getSingleton('testimonial/testimonial')
                        ->load($testimonialsId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($testimonialsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'testimonial.csv';
        $content    = $this->getLayout()->createBlock('testimonial/adminhtml_testimonial_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'testimonial.xml';
        $content    = $this->getLayout()->createBlock('testimonial/adminhtml_testimonial_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
    
    
    

}
