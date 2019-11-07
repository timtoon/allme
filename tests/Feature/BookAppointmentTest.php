<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookAppointmentTest extends TestCase
{
    public function testBookAppointment()
    {
        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [
	        [
	        	'order_id'		  => 1,
				'order_type' 	  => 'add_slot',
				'stylist_id' 	  => 3333,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
	        ],
	        [
	        	'order_id'		  => 2,
				'order_type' 	  => 'book_appointment',
				'stylist_id' 	  => 3333,
				'client_id'		  => 999,
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
            ]);
    }

    public function testBookDuplicateAppointment()
    {
        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [
	        [
	        	'order_id'		  => 1,
				'order_type' 	  => 'add_slot',
				'stylist_id' 	  => 1111,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
	        ],
	        [
	        	'order_id'		  => 2,
				'order_type' 	  => 'book_appointment',
				'stylist_id' 	  => 1111,
				'client_id'		  => 999,
				'slot_begin' 	  => '2019-09-01T10:00:00Z',
				'slot_length_min' => 60,
	        ],
	        [
	        	'order_id'		  => 3,
				'order_type' 	  => 'book_appointment',
				'stylist_id' 	  => 1111,
				'client_id'		  => 333,
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

    public function testBookMissingAppointment()
    {
        $response = $this->withHeaders(['X-Header' => 'Value'])->json('POST', '/system', [
	        [
	        	'order_id'		  => 1,
				'order_type' 	  => 'book_appointment',
				'stylist_id' 	  => 5555,
				'client_id'		  => 888,
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
