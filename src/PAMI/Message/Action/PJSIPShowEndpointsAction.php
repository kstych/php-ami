<?php

namespace PAMI\Message\Action;


class PJSIPShowEndpointsAction extends ActionMessage
{
    public function __construct()
    {
        parent::__construct('PJSIPShowEndpoints');
    }
}
