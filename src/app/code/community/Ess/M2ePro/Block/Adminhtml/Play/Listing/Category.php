<?php

/*
* @copyright  Copyright (c) 2012 by  ESS-UA.
*/

class Ess_M2ePro_Block_Adminhtml_Play_Listing_Category extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();

        // Initialization block
        //------------------------------
        $this->setId('playListingCategory');
        //------------------------------

        $this->setTemplate('M2ePro/play/listing/category.phtml');
    }
}