<?php

namespace Faxity\Test;

use PHPUnit\Framework\TestCase;

/**
 * Test the fetch class for mocking data
 */
class MockFetchTest extends TestCase
{
    /**
     * Test get method
     */
    public function testGet() : void
    {
        $fetch = new MockFetch();
        $fetch->addResponse((object) [
            "foo" => "bar",
        ]);

        $res = $fetch->get("");
        $this->assertIsObject($res);
        $this->assertEquals($res->foo, "bar");
    }


    /**
     * Test exception in get method
     */
    public function testGetFail() : void
    {
        $fetch = new MockFetch();
        $this->expectException(\Exception::class);

        $fetch->get("");
    }


    /**
     * Test getMulti method
     */
    public function testGetMulti() : void
    {
        $fetch = new MockFetch();
        $fetch->addResponse([
            (object) [
                "id" => 1,
            ],
            (object) [
                "id" => 2,
            ],
            (object) [
                "id" => 3,
            ],
        ]);

        // Get only the first response
        $res1 = $fetch->getMulti([
            [ "url" => "" ],
        ]);
        $this->assertCount(1, $res1);
        $this->assertIsObject($res1[0]);
        $this->assertEquals($res1[0]->id, 1);

        // Get the next 2 responses
        $res2 = $fetch->getMulti([
            [ "url" => "" ],
            [ "url" => "" ],
        ]);
        $this->assertCount(2, $res2);
        $this->assertIsObject($res2[0]);
        $this->assertIsObject($res2[1]);
        $this->assertEquals($res2[0]->id, 2);
        $this->assertEquals($res2[1]->id, 3);
    }


    /**
     * Test exception in getMulti method
     */
    public function testGetMultiFail() : void
    {
        $fetch = new MockFetch();
        $this->expectException(\Exception::class);

        $fetch->getMulti([
            "url" => "",
        ]);
    }
}
