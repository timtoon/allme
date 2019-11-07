<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddSlotTest extends TestCase
{
    public function testAddSlot()
    {
        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [[
        	'order_id'		  => 1,
			'order_type' 	  => 'add_slot',
			'stylist_id' 	  => 1234,
			'slot_begin' 	  => '2019-09-01T10:00:00Z',
			'slot_length_min' => 60,
        ]]);

        $response
            ->assertStatus(200)
            ->assertJson([[
                'order_id' => 1,
                'succeed' => 1,
            ]]);
    }

    public function testAddDuplicateSlot()
    {
        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [
	        [
	        	'order_id'		  => 1,
				'order_type' 	  => 'add_slot',
				'stylist_id' 	  => 8888,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
	        ],
	        [
	        	'order_id'		  => 2,
				'order_type' 	  => 'add_slot',
				'stylist_id' 	  => 8888,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
	        ],
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
	            [
	                'order_id' => 1,
	                'succeed' => 1,
	            ],
	            [
	                'order_id' => 2,
	                'succeed' => 0,
	            ]
            ]);
    }
}
