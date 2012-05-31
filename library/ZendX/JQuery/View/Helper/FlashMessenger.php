<?php
/**
 * A view helper shotrcut to the flashMessenger
 * @author scur
 *
 */
class ZendX_JQuery_View_Helper_FlashMessenger extends Zend_View_Helper_Abstract
{

    /**
     * Display Flash Messages.
     *
     * @param  string $key Level of message that will be displaied
     * @return string Flash messages formatted for output
     */
    public function flashMessenger()
    {
        return new ZendX_JQuery_FlashMessenger();
    }

    
}

?>