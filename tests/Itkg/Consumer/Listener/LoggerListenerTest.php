<?php

namespace Itkg\Consumer\Listener;

use Itkg\Consumer\Service\LoggableService;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Itkg\Consumer\Response;

class LoggerListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testServiceSuccessAndFail()
    {
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber(new LoggerListener());

        $clientMock = $this->getMockBuilder('Itkg\Consumer\Client\RestClient')->getMock();
        $loggerMock = $this->getMockBuilder('Psr\Log\AbstractLogger')->disableOriginalConstructor()->getMock();
        $loggerMock->expects($this->exactly(3))->method('info');
        $loggerMock->expects($this->once())->method('error');
        $loggableService = new LoggableService(
            $eventDispatcher,
            $clientMock,
            $loggerMock,
            array('identifier' => 'loggable service')
        );

        $loggableService->sendRequest(Request::create('/'));

        $clientMock->expects($this->once())->method('sendRequest')->will($this->throwException(new \Exception('KO')));

        $loggableService->setClient($clientMock);
        $loggableService->sendRequest(Request::create('/'));

    }
} 