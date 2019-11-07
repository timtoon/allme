<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CancelAppointmentTest extends TestCase
{
    public function testCancelAppointment()
    {
        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [
	        [
	        	'order_id'		  => 1,
				'order_type' 	  => 'add_slot',
				'stylist_id' 	  => 4444,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
	        ],
	        [
	        	'order_id'		  => 2,
				'order_type' 	  => 'book_appointment',
				'stylist_id' 	  => 4444,
				'client_id' 	  => 111,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
	        ],
	        [
	        	'order_id'		  => 3,
				'order_type' 	  => 'cancel_appointment',
				'stylist_id' 	  => 4444,
				'client_id' 	  => 111,
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
	                'succeed' => 1,
	            ],
            ]);
    }

    public function testCancelMissingAppointment()
    {
        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [
	        [
	        	'order_id'		  => 1,
				'order_type' 	  => 'cancel_appointment',
				'stylist_id' 	  => 6666,
				'client_id' 	  => 222,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
			],
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
	            [
	                'order_id' => 1,
	                'succeed' => 0,
	            ],
            ]);
    }

}
