<?php

namespace App\Http\Controllers\Api;

use App\Model\Consultant;
use App\Model\District;
use App\Model\Province;
use App\Model\User;
use App\Notifications\ContactNotification;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ConsultantsController
{
    use ValidatesRequests;

    public function __invoke(Request $request)
    {
        $args = $request->validate([
            'counselor_id' => 'nullable|exists:counselors,id',
            'customer_name' => 'required|string',
            'customer_email' => 'nullable|email',
            'customer_address' => 'nullable|string',
            'customer_phone' => 'required|phone:VN',
            'private_note' => 'required|string',
            'data' => 'required|array',
            'g-recaptcha-response' => config('captcha.sitekey') ? 'required|captcha' : 'nullable',
        ], [], [
            'counselor_id' => 'tư vấn viên',
            'customer_name' => 'họ tên',
            'customer_email' => 'email',
            'customer_address' => 'địa chỉ',
            'customer_phone' => 'SĐT',
            'private_note' => 'nội dung',
            'data' => 'ngày tư vấn',
        ]);

        $args['customer_phone'] = format_phone_number($args['customer_phone']);

        if (empty($args['customer_phone'])) {
            ValidationException::withMessages(['customer_phone' => 'Vui lòng nhập SĐT hợp lệ']);
        }

        if (!empty($args['customer_address'])) {
            $address = explode('|', $args['customer_address'], 2);

            $address[0] = Province::getByCode($address[0])->getName();

            $address[1] = rescue(function () use ($address) {
                return isset($address[1]) ? District::getByCode($address[1])->getNameWithType() : null;
            });

            $args['customer_address'] = implode(', ', array_filter(array_map('trim', $address)));
        }

        $counselor = Consultant::query()
            ->whereNull('reserved_at')
            ->firstOrNew(Arr::only($args, 'customer_phone'));

        $counselor->fill($args);
        $counselor->counselor_id = $counselor->counselor_id ?: 0;

        if (!$counselor->customer_email) {
            $counselor->customer_email = '';
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $counselor->saveOrFail();

        try {
            $super = User::getAdmin();

            $super->sendDashboardNotify(
                sprintf('Một tư vấn mới từ %s', $counselor->customer_name ?: $counselor->customer_email),
                $counselor->private_note ?: '',
                url(sprintf('dashboard/consultants/%s/edit', $counselor->id))
            );
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json(['status' => 'success']);
    }
}
