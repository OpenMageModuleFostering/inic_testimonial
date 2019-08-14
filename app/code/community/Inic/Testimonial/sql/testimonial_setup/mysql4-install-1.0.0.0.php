<?php
/**
 * @package    Inic_Testimonial
 * @author     Indianic 
 */
$installer = $this;

$installer->startSetup();

$table = $installer->getTable('inic_testimonials');

$installer->run("
    create table IF NOT EXISTS {$table} (
        `testimonial_id` int(11) unsigned not null auto_increment,
        `testimonial_position` int(11) default 0,
        `testimonial_name` varchar(50) not null default '',
        `testimonial_email` varchar(255) NOT NULL default '',
        `testimonial_text` text not null default '',
        `testimonial_companyname` text not null default '',
        `testimonial_address` text not null default '',
        `testimonial_img` varchar(128) default NULL,
        `testimonial_sidebar` tinyint(4) NOT NULL,
        `status` smallint(6) NOT NULL default '0',
        `stores` varchar(255) NOT NULL default '',
        `created_time` datetime NULL,
        `update_time` datetime NULL,
        PRIMARY KEY(testimonial_id)
    ) engine=InnoDB default charset=utf8;
");

$installer->endSetup();
