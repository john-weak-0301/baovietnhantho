<?php

namespace App\Http\Controllers;

use App\Model\User;
use Exception;
use App\Model\Contact;
use App\Model\Province;
use App\Http\Requests\ContactRequest;
use App\Notifications\ContactNotification;

class ContactController extends Controller
{
    public function contact()
    {
        $province = Province::all();

        return view('contact', ['province' => $province]);
    }

    public function action(ContactRequest $request)
    {
        $contact = new Contact();

        $contact->fill(array_merge([
            'email' => '',
            'address' => '',
            'province_code' => '',
        ], $request->all()));

        /** @noinspection PhpUnhandledExceptionInspection */
        $contact->saveOrFail();

        try {
            $super = User::getAdmin();

            $super->notify(new ContactNotification($contact));

            $super->sendDashboardNotify(
                sprintf('Xin chào! Bạn có một tin nhắn mới từ %s', $contact->name ?: $contact->email),
                $contact->message,
                url(sprintf('dashboard/contacts/%s/edit', $contact->id))
            );
        } catch (Exception $e) {
            report($e);
        }

        if ($request->wantsJson()) {
            return ['status' => 'success'];
        }

        return redirect()
            ->route('thank-you')
            ->with('success', __('Cảm ơn bạn đã tin nhắn liên hệ!'));
    }
}
