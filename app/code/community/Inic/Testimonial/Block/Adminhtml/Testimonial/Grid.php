<?php
/**
 * 
 * @package    Inic_Testimonial
 * @author     Indianic 
 */
class Inic_Testimonial_Block_Adminhtml_Testimonial_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

   
    public function __construct()
    {
        parent::__construct();
        $this->setId('testimonialGrid');
        $this->setDefaultSort('testimonial_position');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

  
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('testimonial/testimonial')->getCollection();
         $this->setCollection($collection);
         return parent::_prepareCollection();
        
    }

   
    protected function _prepareColumns()
    {
        $this->addColumn('testimonial_position', array(
            'header'    => Mage::helper('testimonial')->__('Position'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'testimonial_position',
            'type'      => 'number',
        ));

        $this->addColumn('testimonial_name', array(
            'header'    => Mage::helper('testimonial')->__('Name'),
            'align'     => 'left',
            'index'     => 'testimonial_name',
        ));

        $this->addColumn('testimonial_text', array(
            'header'    => Mage::helper('testimonial')->__('Text'),
            'align'     => 'left',
            'index'     => 'testimonial_text',
        ));

         $this->addColumn('status', array(
          'header'    => Mage::helper('testimonial')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
      
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('testimonial')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('testimonial')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
        
        $this->addExportType('*/*/exportCsv', Mage::helper('testimonial')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('testimonial')->__('XML'));
      
      return parent::_prepareColumns();
  }
     protected function _prepareMassaction()
    {
        $this->setMassactionIdField('testimonial_id');
        $this->getMassactionBlock()->setFormFieldName('testimonial');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('testimonial')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('testimonial')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('testimonial/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('testimonial')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('testimonial')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }


    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
