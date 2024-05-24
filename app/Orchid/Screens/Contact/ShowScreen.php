<?php

namespace App\Orchid\Screens\Contact;

use App\Model\Contact;
use App\Orchid\Requests\StoreContactRequest;
use App\Orchid\Screens\AbstractScreen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShowScreen extends AbstractScreen
{
    /**
     * Indicator the model's exists or not.
     *
     * @var bool
     */
    protected $exist = false;

    protected $reserved = false;

    /**
     * @param Request $request
     * @param Contact $contact
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function query(Request $request, Contact $contact): array
    {
        $this->authorize(Contact::PERMISSION_VIEW);

        $this->exist = $contact->exists;

        return [
            'contact' => $contact,
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        $user = $this->request->user();

        return [
            $this->addLink(__('Xóa'))
                ->icon('icon-trash')
                ->method('destroy')
                ->confirm(__('Bạn có muốn xóa?'))
                ->canSee($this->exist && $user->can(Contact::PERMISSION_DELETE)),

            $this->addLink(__('Lưu'))
                ->icon('icon-check')
                ->method('save')
                ->canSee($this->exist),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            Layouts\EditLayout::class,
        ];
    }

    public function save(StoreContactRequest $request, Contact $contact): RedirectResponse
    {
        $validated = $request->validated()['contact'] ?? [];

        $contact->fill([
            'name' => clean($validated['name'] ?? ''),
            'phone_number' => clean($validated['phone_number'] ?? ''),
            'email' => clean($validated['email'] ?? ''),
            'address' => clean($validated['address'] ?? ''),
            'message' => clean($validated['message'] ?? ''),
            'province_code' => (int) $validated['province_code'],
        ]);

        $contact->saveOrFail();
        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.contacts.show', $contact);
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $this->checkPermission(Contact::PERMISSION_DELETE);

        $contact->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.contacts');
    }
}
