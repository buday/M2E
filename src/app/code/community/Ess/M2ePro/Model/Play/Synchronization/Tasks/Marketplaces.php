<?php

/*
 * @copyright  Copyright (c) 2012 by  ESS-UA.
 */

class Ess_M2ePro_Model_Play_Synchronization_Tasks_Marketplaces extends Ess_M2ePro_Model_Play_Synchronization_Tasks
{
    const PERCENTS_START = 0;
    const PERCENTS_END = 100;
    const PERCENTS_INTERVAL = 100;

    //####################################

    public function process()
    {
        // PREPARE SYNCH
        //---------------------------
        $this->prepareSynch();
        //---------------------------

        // RUN SYNCH
        //---------------------------
        $this->execute();
        //---------------------------

        // CANCEL SYNCH
        //---------------------------
        $this->cancelSynch();
        //---------------------------
    }

    //####################################

    private function prepareSynch()
    {
        $this->_lockItem->activate();
        $this->_logs->setSynchronizationTask(Ess_M2ePro_Model_Synchronization_Log::SYNCH_TASK_MARKETPLACES);

        if (count(Mage::helper('M2ePro/Component')->getActiveComponents()) > 1) {
            $componentName = Ess_M2ePro_Helper_Component_Play::TITLE.' ';
        } else {
            $componentName = '';
        }

        $this->_profiler->addEol();
        $this->_profiler->addTitle($componentName.'Marketplaces Synchronization');
        $this->_profiler->addTitle('--------------------------');
        $this->_profiler->addTimePoint(__CLASS__,'Total time');
        $this->_profiler->increaseLeftPadding(5);

        $this->_lockItem->setTitle(Mage::helper('M2ePro')->__($componentName.'Marketplaces Synchronization'));
        $this->_lockItem->setPercents(self::PERCENTS_START);
        $this->_lockItem->setStatus(Mage::helper('M2ePro')
                                                ->__('Task "Marketplaces Synchronization" is started. Please wait...'));
    }

    private function cancelSynch()
    {
        $this->_lockItem->setPercents(self::PERCENTS_END);
        $this->_lockItem->setStatus(Mage::helper('M2ePro')
                                               ->__('Task "Marketplaces Synchronization" is finished. Please wait...'));

        $this->_profiler->decreaseLeftPadding(5);
        $this->_profiler->addEol();
        $this->_profiler->addTitle('--------------------------');
        $this->_profiler->saveTimePoint(__CLASS__);

        $this->_logs->setSynchronizationTask(Ess_M2ePro_Model_Synchronization_Log::SYNCH_TASK_UNKNOWN);
        $this->_lockItem->activate();
    }

    //####################################

    private function execute()
    {
        $marketplaceObj = Mage::helper('M2ePro/Component_Play')->getCachedObject(
            'Marketplace', Ess_M2ePro_Helper_Component_Play::MARKETPLACE_VIRTUAL_ID
        );

        $logMarketplacesString = $marketplaceObj->getTitle();

        // Parser hack -> Mage::helper('M2ePro')->__('The "Categories Synchronization" action for marketplace:
//        "%mrk%" has been successfully completed.');
        $tempString = Mage::getModel('M2ePro/Log_Abstract')->encodeDescription(
            'The "Categories Synchronization" action for marketplace: "%mrk%" has been successfully completed.',
            array('mrk'=>$logMarketplacesString)
        );
        $this->_logs->addMessage($tempString,
                                 Ess_M2ePro_Model_Log_Abstract::TYPE_SUCCESS,
                                 Ess_M2ePro_Model_Log_Abstract::PRIORITY_LOW);
    }

    //####################################
}