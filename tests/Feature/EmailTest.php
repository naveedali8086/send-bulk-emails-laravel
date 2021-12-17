<?php

namespace Tests\Feature;

use App\Jobs\SendEmailJob;
use App\Models\Email;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class EmailTest extends TestCase
{

    public function testEmailsSubjectIsRequired()
    {
        // creating and saving new user in DB
        $user = User::factory()->create();

        // making 2 instance of 'Email' models to be sent as form data and explicitly setting 'subject' attribute of every email object as null
        // to raise validation errors
        $emails = Email::factory()->count(2)->make(['subject' => null]);

        $this->json('POST', '/api/send?api_token=' . $user->api_token, ['emails' => $emails])
            ->assertStatus(422); // laravel returns 422 code if there are validation errors
    }

    public function testEmailsAreCreatedSuccessfully()
    {
        Bus::fake();

        // creating and saving new user in DB
        $user = User::factory()->create();

        // making 10 instances of 'Email' models to be sent as form data
        $emails = Email::factory()->count(10)->make();

        $this->json('POST', '/api/send?api_token=' . $user->api_token, ['emails' => $emails])
            ->assertStatus(201)
            ->assertJson(['message' => 'Emails saved']);

        Bus::assertDispatched(SendEmailJob::class);
    }

    /**
     * In this test, the 'Email' model's instances that created in first test
     * would be returned because they were not delete after running first test
     */
    public function testEmailsListReturnedSuccessfully()
    {
        // creating and saving new user in DB
        $user = User::factory()->create();

        $this->json('GET', '/api/list?api_token=' . $user->api_token)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'recipient', 'subject', 'body', 'attachments'
                    ]
                ]
            ]);

    }


}
