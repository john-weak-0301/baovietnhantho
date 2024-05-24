<p> Họ tên: {{ $contact->name ?? '' }}</p> <br>
<p> Số điện thoại tên: {{ $contact->phone_number ?? '' }}</p> <br>
<p> Email: {{ $contact->email ?? '' }}</p> <br>
<p> Tỉnh/Thành phố: {{ $province ?? '' }}</p> <br>
<p> Địa chỉ chính xác: {{ $contact->address ?? '' }}</p> <br>
<p> Lời nhắn: {{ $contact->message ?? '' }}</p> <br>
