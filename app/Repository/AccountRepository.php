<?php

namespace App\Repository;

use App\Model\Account;

class AccountRepository
{
    public function findByEmail($email)
    {
        $account = Account::where('email', $email)->first(); // Trả về tài khoản nếu tồn tại
        \Log::info('Checking email: ' . $email . ' - Exists: ' . ($account ? 'Yes' : 'No'));
        return $account;
    }

    public function findByEmailAndVerificationToken($email, $token)
    {
        return Account::where('email', $email)->where('verificationToken', $token)->first();
    }

    public function save(Account $account)
{
    return $account->save(); // Phải sử dụng phương thức save của mô hình
}
    
    public function findAll()
    {
        return Account::all();
    }

    public function findById($id)
    {
        return Account::find($id);
    }

    public function deleteById($id)
    {
        return Account::destroy($id);
    }

    public function count()
    {
        return Account::count();
    }
    public function findByVerificationToken($token)
{
    return Account::where('verificationToken', $token)->first();
}
}