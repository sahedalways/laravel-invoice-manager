<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
  /**
   * Get currently authenticated user
   */
  public function getAdminAuthUser()
  {
    return Auth::user();
  }

  /**
   * Check if given password matches user password
   */
  public function checkPassword($user, string $password): bool
  {
    return Hash::check($password, $user->password);
  }

  /**
   * Update user's password
   */
  public function updatePassword($user, string $newPassword): void
  {
    $user->password = Hash::make($newPassword);
    $user->save();
  }



  public function create(array $data): Customer
  {
    $user = new Customer();
    $user->name    = $data['name'];
    $user->email     = $data['email'];
    $user->phone  = $data['phone'];
    $user->address  = $data['address'];

    $user->save();

    return $user;
  }

  public function update(Customer $user, array $data): Customer
  {
    $user->name    = $data['name'];
    $user->email     = $data['email'];
    $user->phone  = $data['phone'];
    $user->address  = $data['address'];


    $user->save();

    return $user;
  }

  public function find($id): ?Customer
  {
    return Customer::where('id', $id)->first();
  }


  public function delete(Customer $user): bool
  {
    return $user->delete();
  }


  public function getAllUsers()
  {
    return Customer::latest()->get();
  }
}
