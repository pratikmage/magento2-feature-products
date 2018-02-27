<?php

namespace Brainvire\ProductGrid\Controller\Adminhtml\Featureproduct;

class Index extends \Brainvire\ProductGrid\Controller\Adminhtml\Featureproduct {

    public function execute() {
        if ($this->getRequest()->getQuery('ajax')) {
            $resultForward = $this->_resultForwardFactory->create();
            $resultForward->forward('grid');

            return $resultForward;
        }

        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }

}
