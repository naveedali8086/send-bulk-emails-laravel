<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailCreateRequest;
use App\Jobs\SendEmailJob;
use App\Models\Email;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{

    public function send(EmailCreateRequest $request): JsonResponse
    {
        $emails = $request->input('emails');

        foreach ($emails as $index => $email_obj) {

            $email_attachments = $request->hasFile("emails.$index.attachments") ? $request->file("emails.$index.attachments") : [];

            $uploaded_file_paths = [];

            foreach ($email_attachments as $attachment) {

                // saving uploaded files in 'attachments' folder
                $uploaded_file_paths[] = [
                    'file_name' => $attachment->getClientOriginalName(),
                    'file_path' => $attachment->store('attachments')
                ];

            }

            $email = Email::query()->create(array_merge($email_obj, ['attachments' => json_encode($uploaded_file_paths)]));

            SendEmailJob::dispatch($email);

        }

        // as per the convention, when a resource is created 201 is returned
        return response()->json(['message' => 'Emails saved'], 201);

    }

    public function list()
    {
        // as per the requirements, only following four attributes were requested to be sent in response
        return Email::query()->select(['recipient', 'subject', 'body', 'attachments'])->simplePaginate();
    }

}
