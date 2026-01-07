<?php namespace PAMI\Message\Action;

class MuteAudioAction extends ActionMessage
{
  public function __construct($channel, $direction, $state)
  {
    parent::__construct('MuteAudio');
    $this->setKey('Channel', $channel);
    $this->setKey('Direction', $direction);
    $this->setKey('State', $state);
  }
}
