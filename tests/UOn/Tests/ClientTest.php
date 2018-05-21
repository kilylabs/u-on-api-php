<?php

namespace Kily\API\UOn\Tests;

use Kily\API\UOn\Client;
use Kily\API\UOn\Exception;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-12-26 at 17:34:33.
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        if(!isset($_SERVER['API_KEY'])) {
            $this->markTestSkipped(
                'You should define API_KEY in phpunit.xml to pass this test'
            );
        }

        $this->object = new Client($_SERVER['API_KEY'],array_filter([
            'timeout'  => 300,
            'debug'=>isset($_SERVER['DEBUG']) ? $_SERVER['DEBUG'] : false,
        ]));
    }

    /**
     * @covers Kily\API\UOn\Client::__construct
     */
    public function test__construct()
    {
        $client = new Client('ololo',[
            'timeout'  => 300,
        ]);

        $this->assertTrue(is_object($client));
    }

    /**
     * @covers Kily\API\UOn\Client::create
     */
    public function testCreate()
    {
        if(!isset($_SERVER['ALLOW_UNSAFE_OPERATIONS']) || !$_SERVER['ALLOW_UNSAFE_OPERATIONS'])
            $this->markTestSkipped(
                'You should explictly allow tests with create,update and delete operations'
            );

        $data = [
            'r_id_internal'=>'TEST_'.rand(1,1000000),
            'u_name'=>'Alexander',
            'u_surname'=>'Bogdanov',
            'u_email'=>'spam@sux.net',
        ];

        $data = $this->object->lead()->create($data);
        $this->assertTrue($this->object->isOk());
    }


    /**
     * @covers Kily\API\UOn\Client::_request
     */
    public function test_request()
    {
        if(!isset($_SERVER['ALLOW_UNSAFE_OPERATIONS']) || !$_SERVER['ALLOW_UNSAFE_OPERATIONS'])
            $this->markTestSkipped(
                'You should explictly allow tests with create,update and delete operations'
            );

        $data = [
            'r_id_internal'=>'TEST_'.rand(1,1000000),
            'u_name'=>'Alexander',
            'u_surname'=>'Bogdanov',
            'u_email'=>'spam@sux.net',
        ];

        $data = $this->object->lead()->create($data); //FIXME: should test only _request() call
        $this->assertTrue($this->object->isOk());
    }

    /**
     * @covers Kily\API\UOn\Client::getErrorMessage
     */
    public function testGetErrorMessage()
    {
        $this->markTestSkipped(
            'Not implemented yet'
        );
    }

    /**
     * @covers Kily\API\UOn\Client::getErrorCode
     */
    public function testGetErrorCode()
    {
        $this->markTestSkipped(
            'Not implemented yet'
        );
    }

    /**
     * @covers Kily\API\UOn\Client::getHttpErrorMessage
     */
    public function testGetHttpErrorMessage()
    {
        $data = $this->object->ololoshenki()->get();
        $this->assertEquals('not found',strtolower($this->object->getHttpErrorMessage()));
    }

    /**
     * @covers Kily\API\UOn\Client::getHttpErrorCode
     */
    public function testGetHttpErrorCode()
    {
        $data = $this->object->ololoshenki()->get();
        $this->assertEquals('404',$this->object->getHttpErrorCode());
    }

    /**
     * @covers Kily\API\UOn\Client::isOk
     */
    public function testIsOk()
    {
        $data = $this->object->source()->get();
        $this->assertTrue($this->object->isOk());
    }

}