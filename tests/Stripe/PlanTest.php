<?php

namespace Stripe;

class PlanTest extends TestCase
{
    const TEST_RESOURCE_ID = 'plan';

    public function testIsListable()
    {
        $this->expectsRequest(
            'get',
            '/v1/plans'
        );
        $resources = Plan::all();
        $this->assertTrue(is_array($resources->data));
        $this->assertInstanceOf(\Stripe\Plan::class, $resources->data[0]);
    }

    public function testIsRetrievable()
    {
        $this->expectsRequest(
            'get',
            '/v1/plans/' . self::TEST_RESOURCE_ID
        );
        $resource = Plan::retrieve(self::TEST_RESOURCE_ID);
        $this->assertInstanceOf(\Stripe\Plan::class, $resource);
    }

    public function testIsCreatable()
    {
        $this->expectsRequest(
            'post',
            '/v1/plans'
        );
        $resource = Plan::create([
            'amount' => 100,
            'interval' => 'month',
            'currency' => 'usd',
            'nickname' => self::TEST_RESOURCE_ID,
            'id' => self::TEST_RESOURCE_ID
        ]);
        $this->assertInstanceOf(\Stripe\Plan::class, $resource);
    }

    public function testIsSaveable()
    {
        $resource = Plan::retrieve(self::TEST_RESOURCE_ID);
        $resource->metadata["key"] = "value";
        $this->expectsRequest(
            'post',
            '/v1/plans/' . $resource->id
        );
        $resource->save();
        $this->assertInstanceOf(\Stripe\Plan::class, $resource);
    }

    public function testIsUpdatable()
    {
        $this->expectsRequest(
            'post',
            '/v1/plans/' . self::TEST_RESOURCE_ID
        );
        $resource = Plan::update(self::TEST_RESOURCE_ID, [
            "metadata" => ["key" => "value"],
        ]);
        $this->assertInstanceOf(\Stripe\Plan::class, $resource);
    }

    public function testIsDeletable()
    {
        $resource = Plan::retrieve(self::TEST_RESOURCE_ID);
        $this->expectsRequest(
            'delete',
            '/v1/plans/' . $resource->id
        );
        $resource->delete();
        $this->assertInstanceOf(\Stripe\Plan::class, $resource);
    }

    public function testDeserializeDecimalString()
    {
        $plan = Plan::constructFrom(json_decode("{\"amount_precise\": \"0.000000123\"}", true));
        $this->assertNotNull($plan);
        $this->assertNotNull($plan->amount_precise);
        $this->assertTrue(is_string($plan->amount_precise));
        $this->assertEquals("0.000000123", $plan->amount_precise);
    }
}
