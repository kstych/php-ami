<?php namespace PAMI\Message\Action;

class MessageSendAction extends ActionMessage
{
  public function __construct($to, $from, $body)
  {
    parent::__construct('MessageSend');
    $this->setKey('To', $to);
    $this->setKey('From', $from);
    //$this->setKey('Body', $body);
    $this->setKey('Base64Body', base64_encode($body));
  }
}
