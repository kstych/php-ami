# Introduction

PAMI means PHP Asterisk Manager Interface. As its name suggests its just a
set of php classes that will let you issue commands to an ami and/or receive
events, using an observer-listener pattern.

The idea behind this, is to easily implement operator consoles, monitors, etc.
either via SOA or ajax.

# Installing
Add this library to your [Composer](https://packagist.org/) configuration. In
composer.json:
```json
  "require": {
    "kstych/php-ami": "*"
  }
```

# QuickStart

```php
// Make sure you include the composer autoload.
require __DIR__ . '/vendor/autoload.php';

$options = array(
    'host' => '2.3.4.5',
    'scheme' => 'tcp://',
    'port' => 9999,
    'username' => 'asd',
    'secret' => 'asd',
    'connect_timeout' => 10,
    'read_timeout' => 10
);
$client = new \PAMI\Client\Impl\ClientImpl($options);

// Registering a closure
$client->registerEventListener(function ($event) {
});

// Register a specific method of an object for event listening
$client->registerEventListener(array($listener, 'handle'));

// Register an IEventListener:
$client->registerEventListener($listener);
```

# Using Predicates
A second (optional) argument can be used when registering the event listener: a
closure that will be evaluated before calling the callback. The callback will
be called only if this predicate returns true:

```php
use PAMI\Message\Event\DialEvent;

$client->registerEventListener(
    array($listener, 'handleDialStart'),
    function ($event) {
        return $event instanceof DialEvent && $event->getSubEvent() == 'Begin';
    })
);
```

# Example

Please see docs/examples/quickstart/example.php for a very basic example.

For an example of using asynchronous AGI with PAMI, see docs/examples/asyncagi

# Currently Supported Events

More events will be added with time. I can only add the ones I can test for and
use, so your contributions may make the difference! ;)

Unknown (not yet implemented) events will be reported as UnknownEvent, so you
can still catch them. If you catch one of these, please report it!

* AgentsComplete
* AgentConnect
* Agentlogin
* Agentlogoff
* AGIExec
* AsyncAGI
* Bridge
* BridgeInfoChannel
* BridgeInfoComplete
* CEL
* ChannelUpdate
* ConfbridgeEnd
* ConfbridgeJoin
* ConfbridgeLeave
* ConfbridgeList
* ConfbridgeListComplete
* ConfbridgeMute
* ConfbridgeStart
* ConfbridgeTalking
* ConfbridgeUnmute
* CoreShowChannel
* CoreShowChannelComplete
* DAHDIShowChannel
* DAHDIShowChannelsComplete
* FullyBooted
* DongleSMSStatus
* DongleUSSDStatus
* DongleNewUSSD
* DongleNewUSSDBase64
* DongleNewCUSD
* DongleStatus
* DongleDeviceEntry
* DongleShowDevicesComplete
* DBGetResponse
* Dial
* DTMF
* Extension
* Hangup
* Hold
* JabberEvent
* Join
* Leave
* Link
* ListDialplan
* Masquerade
* MessageWaiting
* MusicOnHold
* NewAccountCode
* NewCallerid
* Newchannel
* Newexten
* Newstate
* OriginateResponse
* ParkedCall
* ParkedCallsComplete
* PeerEntry
* PeerlistComplete
* PeerStatus
* QueueMember
* QueueMemberAdded
* QueueMemberRemoved
* QueueMemberPause
* QueueMemberStatus
* QueueParams
* QueueStatusComplete
* QueueSummaryComplete
* RegistrationsComplete
* Registry
* Rename
* RTCPReceived
* RTCPReceiver
* RTCPSent
* RTPReceiverStat
* RTPSenderStat
* ShowDialPlanComplete
* Status
* StatusComplete
* Transfer
* Unlink
* UnParkedCall
* UserEvent
* VarSet
* vgsm_me_state
* vgsm_net_state
* vgsm_sms_rx
* VoicemailUserEntry
* VoicemailUserEntryComplete

# Currently Supported Actions

* AbsoluteTimeout
* AGI
* Agents
* AgentLogoff
* Atxfer (asterisk 1.8?)
* Bridge
* BridgeInfo
* ChangeMonitor
* Command
* ConfbridgeList
* ConfbridgeMute
* ConfbridgeUnmute
* CoreSettings
* CoreShowChannels
* CoreStatus
* DAHDIDialOffHookAction
* DAHDIHangup
* DAHDIRestart
* DAHDIShowChannels
* DAHDIDNDOn
* DAHDIDNDOff
* DBGet
* DBPut
* DBDel
* DBDelTree
* DongleSendSMS
* DongleSendUSSD
* DongleSendPDU
* DongleReload
* DongleStop
* DongleStart
* DongleRestart
* DongleReset
* DongleShowDevices
* ExtensionState
* CreateConfig
* GetConfig
* GetConfigJSON
* GetVar
* Hangup
* JabberSend
* LocalOptimizeAway
* Login
* Logoff
* ListCategories
* ListCommands
* MailboxCount
* MailboxStatus
* MeetmeList
* MeetmeMute
* MeetmeUnmute
* MixMonitor
* ModuleCheck
* ModuleLoad (split in ModuleLoad, ModuleUnload, and ModuleReload)
* Monitor
* Originate
* ParkedCalls
* PauseMonitor
* Ping
* PlayDTMF
* Queues
* QueueAdd
* Queue
* QueueLog
* QueuePause
* QueuePenalty
* QueueReload
* QueueRemove
* QueueReset
* QueueRule
* QueueSummary
* QueueStatus
* QueueUnpause
* Redirect
* Reload
* SendText
* SetVar
* ShowDialPlan
* Sipnotify
* Sippeers
* Sipqualifypeer
* Sipshowpeer
* Sipshowregistry
* Status
* StopMixMonitor
* StopMonitor
* UnpauseMonitor
* VGSM_SMS_TX
* VoicemailUsersList



## Debugging, logging

You can optionally set a [PSR-3](http://www.php-fig.org/psr/psr-3/) compatible logger:
```php
$pami->setLogger($logger);
```

By default, the client will use the [NullLogger](http://www.php-fig.org/psr/psr-3/#1-4-helper-classes-and-interfaces).

# Developers
This project uses [phing](https://www.phing.info/). Current tasks include:
 * test: Runs [PHPUnit](https://phpunit.de/).
 * cs: Runs [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).
 * doc: Runs [PhpDocumentor](http://www.phpdoc.org/).
 * md: runs [PHPMD](http://phpmd.org/).
 * build: This is the default task, and will run all the other tasks.

## Running a phing task
To run a task, just do:

```sh
vendor/bin/phing build
```

LICENSE
=======
Copyright 2026 Siddharth Upmanyu <siddharth@kstych.com>
Copyright 2016 Marcelo Gornstein <marcelog@gmail.com>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
