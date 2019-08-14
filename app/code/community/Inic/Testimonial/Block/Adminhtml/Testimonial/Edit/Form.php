<?php
/**
 * 
 * @package    Inic_Testimonial
 * @author     Indianic 
 */
class Inic_Testimonial_Block_Adminhtml_Testimonial_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

   
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        }
    }

   
    protected function _prepareForm()
    {
     $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array(
            'add_widgets' => false,
            'add_variables' => false,
            'add_images' => false,
            'files_browser_window_url'=> $this->getBaseUrl().'admin/cms_wysiwyg_images/index/',
            ));
        $helper = Mage::helper('testimonial');
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('testimonial_form', array(
            'legend'      => Mage::helper('testimonial')->__('Testimonial'),
            'class'        => 'fieldset-wide',
            )
        );
       
            if($helper->isEnableContactName()){
                $fieldset->addField('testimonial_name', 'text', array(
                    'name'      => 'testimonial_name',
                    'label'     => Mage::helper('testimonial')->__('Contact Name'),
                    'class'     => 'required-entry',
                    'required'  => true,
                ));
        }
       
           if($helper->isEnableContactEmail()){
                 $fieldset->addField('testimonial_email', 'text', array(
                    'name'      => 'testimonial_email',
                    'label'     => Mage::helper('testimonial')->__('Contact Email'),
                    'class'     => 'required-entry validate-email',
                    'required'  => true,
                ));
           }
         
           if($helper->isEnableCompanyname()){ 
             $fieldset->addField('testimonial_companyname', 'text', array(
                'name'      => 'testimonial_companyname',
                'label'     => Mage::helper('testimonial')->__('Company Name'),
                'required'  => false,
            ));
           }
        
           if($helper->isEnableContactAddress()){ 
            $fieldset->addField('testimonial_address', 'text', array(
                'name'      => 'testimonial_address',
                'label'     => Mage::helper('testimonial')->__('Location'),
                'required'  => false,
            ));
         }

        $fieldset->addField('testimonial_text', 'editor', array(
            'name'      => 'testimonial_text',
            'label'     => Mage::helper('testimonial')->__('Content'),
            'title'     => Mage::helper('testimonial')->__('Content'),
            'style'     => 'width:100%;height:300px;',
            'required'  => true,
            'wysiwyg' => true,
            'required' => true,
            'config' => $config,
          
        ));
           
           if($helper->isEnableContactPhoto()){ 
              $fieldset->addField('testimonial_img', 'image', array(
                'name'      => 'testimonial_img',
                'label'     => Mage::helper('testimonial')->__('Contact Photo'),
            ));
           }

        
         $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('testimonial')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('testimonial')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('testimonial')->__('Disabled'),
              ),
          ),
      ));
        if (!Mage::app()->isSingleStoreMode()) {
                $fieldset->addField('store_id', 'multiselect', array(
                            'name'      => 'stores[]',
                            'label'     => Mage::helper('testimonial')->__('Store View'),
                            'title'     => Mage::helper('testimonial')->__('Store View'),
                            'required'  => true,
                            'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                    ));
            }
            else {
                    $fieldset->addField('store_id', 'hidden', array(
                            'name'      => 'stores[]',
                            'value'     => Mage::app()->getStore(true)->getId()
                    ));
                      $model = Mage::getModel('testimonial/testimonial');
                    $model->setStoreId(Mage::app()->getStore(true)->getId());
            }

             if($helper->isEnableSidebar()){ 
                $fieldset->addField('testimonial_sidebar', 'select', array(
                    'label'     => Mage::helper('testimonial')->__('Show in Sidebar'),
                    'name'      => 'testimonial_sidebar',
                    'values'    => array(
                        array(
                            'value'     => 1,
                            'label'     => Mage::helper('core')->__('Yes'),
                        ),
                        array(
                            'value'     => 0,
                            'label'     => Mage::helper('core')->__('No'),
                        ),
                    ),
                ));
           }
          $fieldset->addField('testimonial_position', 'text', array(
            'name'      => 'testimonial_position',
            'label'     => Mage::helper('testimonial')->__('Position'),
            'style'     => 'width:100px !important',
        ));

       

        $form->setUseContainer(true);
        $this->setForm($form);
        
        
         if ( Mage::getSingleton('adminhtml/session')->getTestimonial() )
      {
          $data = Mage::getSingleton('adminhtml/session')->getTestimonial();
          Mage::getSingleton('adminhtml/session')->getTestimonial(null);
      } elseif ( Mage::registry('testimonial') ) {
          $data = Mage::registry('testimonial')->getData();
      }
      $data['store_id'] = explode(',',$data['stores']);
      $form->setValues($data);
        
        
        
        
        return parent::_prepareForm();
    }

}
