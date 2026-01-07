<?php namespace PAMI\Message\Action;

class PJSIPNotifyAction extends ActionMessage
{
    public function __construct($endpoing)
    {
        parent::__construct('PJSIPnotify');
        $this->setKey('Endpoint', $endpoing);
    }
}
