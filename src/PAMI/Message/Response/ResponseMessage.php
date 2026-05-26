<?php
/**
 * A generic response message from ami.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Response
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/PAMI/
 *
 * Copyright 2011 Marcelo Gornstein <marcelog@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
namespace PAMI\Message\Response;

use PAMI\Message\Message;
use PAMI\Message\IncomingMessage;
use PAMI\Message\Event\EventMessage;

/**
 * A generic response message from ami.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Response
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @link       http://marcelog.github.com/PAMI/
 */
class ResponseMessage extends IncomingMessage
{
    /**
     * Child events.
     * @var EventMessage[]
     */
    private $events;

    /**
     * Is this response completed? (with all its events).
     * @var boolean
     */
    private $completed;

    /**
     * Serialize function.
     *
     * @return string[]
     */
    public function __sleep()
    {
        $ret = parent::__sleep();
        $ret[] = 'completed';
        $ret[] = 'events';
        return $ret;
    }

    /**
     * True if this response is complete. A response is considered complete
     * if it's not a list OR it's a list with its last child event containing
     * an EventList = Complete.
     *
     * @return boolean
     */
    public function isComplete()
    {
        return $this->completed;
    }

    /**
     * Adds an event to this response.
     *
     * @param EventMessage $event Child event to add.
     *
     * @return void
     */
    public function addEvent(EventMessage $event)
    {
        $this->events[] = $event;
        if (stristr($event->getEventList(), 'complete') !== false
            || stristr($event->getName(), 'complete') !== false
            || stristr($event->getName(), 'DBGetResponse') !== false
        ) {
            $this->completed = true;
        }
    }

    /**
     * Returns all associated events for this response.
     *
     * @return EventMessage[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Checks if the Response field has the word Error in it.
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return stristr($this->getKey('Response'), 'Error') === false;
    }

    /**
     * Returns true if this response contains the key EventList with the
     * word 'start' in it. Another way is to have a Message key, like:
     * Message: Result will follow
     *
     * @return boolean
     */
    public function isList()
    {
        return
            stristr($this->getKey('EventList'), 'start') !== false
            || stristr($this->getMessage(), 'follow') !== false
        ;
    }

    /**
     * Returns key: 'Privilege'.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getKey('Message');
    }

    /**
     * Sets an action id. This should not be necessary, but asterisk sometimes
     * decides to not send the Response: or Event: headers.
     *
     * @param string $actionId New ActionId.
     *
     * @return void
     */
    public function setActionId($actionId)
    {
        $this->setKey('ActionId', $actionId);
    }

    /**
     * Constructor.
     *
     * @param string $rawContent Literal message as received from ami.
     *
     * @return void
     */
    public function __construct($rawContent)
    {
        parent::__construct($rawContent);
        $this->events = array();
        $this->eventsCount = 0;
        $this->completed = !$this->isList();
        // In Asterisk 13+, Command responses are self-contained with inline
        // Output: lines. They use "Command output follows" as the Message but
        // do NOT send follow-up events, so mark them as complete immediately.
        if (!$this->completed && $this->isCommandResponse()) {
            $this->completed = true;
        }
    }

    /**
     * Returns true if this is a Command action response with inline output
     * (Asterisk 13+ format). These responses contain "Command output follows"
     * in the Message header but are self-contained (no follow-up events).
     *
     * @return boolean
     */
    private function isCommandResponse()
    {
        return stristr($this->getMessage(), 'Command output follows') !== false;
    }

    /**
     * Returns the output lines from a Command action response as an array.
     *
     * @return string[]
     */
    public function getCommandOutput()
    {
        $output = $this->getKey('output');
        if ($output === null) {
            return array();
        }
        return explode("\n", $output);
    }
}
