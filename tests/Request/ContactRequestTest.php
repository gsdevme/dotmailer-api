<?php

namespace Dotmailer;

use Dotmailer\Entity\Contact;
use Dotmailer\Entity\DataItem;
use Dotmailer\Collection\DataItemCollection;
use Dotmailer\Request\ContactRequest;

require('tests/bootstrap.php');

class ContactRequestTest extends \PHPUnit_Framework_TestCase
{
    private $config;
    private $request;

    public function __construct()
    {
        $this->config = new Config('config/config.yml');
    }

    public function setUp()
    {
        $this->request = new ContactRequest($this->config);
    }

    public function tearDown()
    {
        unset($this->request);
    }

    public function testCreate()
    {
        $dataitem = array('key' => 'FIRSTNAME', 'value' => 'Lee');
        $firstname = new DataItem($dataitem);
        $arr = array(
            'email' => 'lee@example.com',
            'dataFields' => new DataItemCollection(array($firstname)),
        );
        $new_contact = new Contact($arr);
        try {
            $response = $this->request->create($new_contact);
        } catch (\Exception $e) {
            $this->fail('Request exception received: '.$e->api_response);
        }
        $this->assertInstanceOf('Dotmailer\Entity\Contact', $response);
        return $response;
    }

    /**
     * @depends testCreate
     *
     * This depends on testCreate so that we can assert that at least one record
     * is returned. The parameter is unused however.
     */
    public function testGetAll($ignored)
    {
        try {
            $response = $this->request->getAll();
        } catch (\Exception $e) {
            $this->fail('Request exception received');
        }
        $this->assertInstanceOf('Dotmailer\Collection\ContactCollection', $response);
        $this->assertGreaterThan(0, count($response));

        try {
            $response = $this->request->getAll(array('select' => 1));
        } catch (\Exception $e) {
            $this->fail('Request exception received');
        }
        $this->assertInstanceOf('Dotmailer\Collection\ContactCollection', $response);
        $this->assertCount(1, $response);
        return $response[0];
    }
}