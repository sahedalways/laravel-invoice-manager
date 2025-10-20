<?php

namespace App\Livewire\Customers;

use App\Livewire\Components\BaseComponent;
use App\Models\Customer;
use App\Services\UserService;
use Illuminate\Validation\Rule;


class Customers extends BaseComponent
{
    public $users, $user,  $user_id, $name, $email, $phone, $address, $search;

    public $perPage = 10;
    public $loaded;
    public $lastId = null;
    public $hasMore = true;
    public $editMode = false;


    protected $userService;

    protected $listeners = ['deleteUser'];


    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }


    protected $rules = [
        'name' => 'required|string|max:100',
        'address' => 'required|string|max:100',
        'email'      => 'required|email|unique:customers,email',
        'phone' => 'required|string|max:20|unique:customers,phone',
    ];



    public function mount()
    {
        $this->loaded = collect();
        $this->loadMore();
    }


    public function render()
    {
        return view('livewire.customers.customers', [
            'infos' => $this->loaded
        ]);
    }





    /* reset input file */
    public function resetInputFields()
    {
        $this->user = '';
        $this->email = '';
        $this->phone = '';
        $this->name = '';
        $this->address = '';

        $this->resetErrorBag();
    }
    /* store User data */
    public function store()
    {

        $this->validate();

        $this->userService->register([
            'name' => $this->name,
            'email'      => $this->email,
            'phone'   => $this->phone,
            'address'   => $this->address,
        ]);

        $this->users =  $this->userService->getAllUsers();

        $this->resetInputFields();
        $this->dispatch('closemodal');

        $this->toast('Customer registered Successfully!', 'success');
        $this->resetLoaded();
    }







    /* view User details to update */
    public function edit($id)
    {
        $this->editMode = true;
        $this->user = $this->userService->getUser($id);

        if (!$this->user) {
            $this->toast('Customer not found!', 'error');
            return;
        }

        $this->email = $this->user->email;
        $this->name = $this->user->name;
        $this->phone = $this->user->phone;
        $this->address = $this->user->address;
    }


    /* update user details */
    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:100',
            'email'      => [
                'required',
                'email',
                Rule::unique('customers', 'email')->ignore($this->user->id),
            ],
            'phone'   => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers', 'phone')->ignore($this->user->id),
            ],

        ]);

        if (!$this->user) {
            $this->toast('Customer not found!', 'error');
            return;
        }
        $this->userService->updateUser($this->user, [
            'name' => $this->name,
            'email'      => $this->email,
            'phone'   => $this->phone,
            'address'   => $this->address,
        ]);


        $this->resetInputFields();
        $this->editMode = false;

        $this->dispatch('closemodal');
        $this->toast('Customer has been updated!', 'success');
        $this->resetLoaded();
    }


    /* process while update */
    public function searchCustomers()
    {
        $this->resetLoaded();
    }



    // Load more function
    public function loadMore()
    {
        if (!$this->hasMore) return;

        $query = Customer::query();

        if ($this->search && $this->search != '') {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('phone', 'like', $searchTerm);
            });
        }

        if ($this->lastId) {
            $query->where('id', '<', $this->lastId);
        }

        $items = $query->orderBy('id', 'desc')
            ->limit($this->perPage)
            ->get();

        if ($items->count() == 0) {
            $this->hasMore = false;
            return;
        }

        if ($items->count() < $this->perPage) {
            $this->hasMore = false;
        }

        $this->lastId = $items->last()->id;
        $this->loaded = $this->loaded->merge($items);
    }


    // Reset loaded collection
    private function resetLoaded()
    {
        $this->loaded = collect();
        $this->lastId = null;
        $this->hasMore = true;
        $this->loadMore();
    }



    public function deleteUser($id)
    {
        $this->userService->deleteUser($id);


        $this->toast('Customer has been deleted!', 'success');
        $this->resetLoaded();
    }
}
