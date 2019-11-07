<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RemoveSlotTest extends TestCase
{
    public function testRemoveSlot()
    {
        $add = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [[
        	'order_id'		  => 1,
			'order_type' 	  => 'add_slot',
			'stylist_id' 	  => 2222,
			'slot_begin' 	  => '2019-09-01T10:00:00Z',
			'slot_length_min' => 60,
        ]]);

        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [[
        	'order_id'		  => 1,
			'order_type' 	  => 'remove_slot',
			'stylist_id' 	  => 2222,
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

    public function testRemoveBookedSlot()
    {
        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [
			[
	        	'order_id'		  => 1,
				'order_type' 	  => 'add_slot',
				'stylist_id' 	  => 7777,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
			],
	        [
	        	'order_id'		  => 2,
				'order_type' 	  => 'book_appointment',
				'stylist_id' 	  => 7777,
				'client_id'		  => 333,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
	        ],
	        [
	        	'order_id'		  => 3,
				'order_type' 	  => 'remove_slot',
				'stylist_id' 	  => 7777,
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
                'succeed' => 1,
            ],
            [
                'order_id' => 3,
                'succeed' => 0,
            ],
            ]);
    }

    public function testRemoveMissingSlot()
    {
        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [[
        	'order_id'		  => 1,
			'order_type' 	  => 'remove_slot',
			'stylist_id' 	  => 7777,
			'slot_begin' 	  => '2019-09-01T10:00:00Z',
			'slot_length_min' => 60,
        ]]);

        $response
            ->assertStatus(200)
            ->assertJson([[
                'order_id' => 1,
                'succeed' => 0,
            ]]);
    }
}
