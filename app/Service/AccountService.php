<?php

namespace App\Service;

use App\Model\Account;
use App\Repository\AccountRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountService
{
    protected $accountRepository;
    protected $emailService;

    public function __construct(AccountRepository $accountRepository, EmailService $emailService)
    {
        $this->accountRepository = $accountRepository;
        $this->emailService = $emailService;
    }

    public function createAccount(array $data)
    {
        if ($this->accountRepository->findByEmail($data['email'])) {
            throw new \Exception("Email đã được sử dụng."); // Ném lỗi nếu email đã tồn tại
        }
    
        $account = new Account();
        $account->id = (string) Str::uuid(); // Gán UUID cho id
        $account->fullName = $data['fullName'];
        $account->email = $data['email'];
        $account->password = Hash::make($data['password']);
        $account->enabled = false;
    
        try {
            $account->save(); // Lưu tài khoản vào cơ sở dữ liệu
        } catch (\Exception $e) {
            \Log::error('Error saving account: ' . $e->getMessage());
            throw new \Exception("Đã xảy ra lỗi khi lưu tài khoản.");
        }
    
        \Log::info('Account ID after save: ' . $account->id);
    
        return $account; // Trả về tài khoản đã lưu
    }

    public function verifyAccount($token)
{
    $account = $this->accountRepository->findByVerificationToken($token);

    if ($account) {
        $account->enabled = true; // Kích hoạt tài khoản
        $account->verificationToken = null; // Xóa token
        $this->accountRepository->save($account);
        return true;
    }

    return false;
}
public function confirmAccount($token)
{
    $account = $this->accountRepository->findByToken($token);

    if (!$account) {
        return response()->json(['message' => 'Token xác nhận không hợp lệ.'], 404);
    }

    $account->enabled = true; // Kích hoạt tài khoản
    $this->accountRepository->save($account); // Lưu thay đổi

    return response()->json(['message' => 'Tài khoản đã được xác nhận thành công.']);
}

    public function verifyAccountByTokenAndEmail($token, $email)
    {
        $account = $this->accountRepository->findByEmailAndVerificationToken($email, $token);
        
        if ($account) {
            $account->verificationToken = null;
            $account->enabled = true;
            $this->accountRepository->save($account);
            return true;
        }
        
        return false;
    }

    public function getAllAccounts()
    {
        return $this->accountRepository->findAll();
    }

    public function getAccountById($id)
    {
        return $this->accountRepository->findById($id);
    }

    public function updateAccount($id, $data)
    {
        $account = $this->accountRepository->findById($id);
        
        if (!$account) {
            throw new \Exception("Account not found");
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $account->update($data);
        return $account;
    }

    public function deleteAccount($id)
    {
        $this->accountRepository->deleteById($id);
    }

    public function login($email, $password)
    {
        $account = $this->accountRepository->findByEmail($email);
        
        if ($account) {
            if (!$account->enabled) {
                throw new \Exception("Vui lòng xác nhận tài khoản để đăng nhập.");
            }
    
            if (Hash::check($password, $account->password)) {
                return $account; // Trả về thông tin tài khoản nếu đăng nhập thành công
            }
        }
    
        throw new \Exception("Email hoặc mật khẩu không đúng.");
    }

    public function validatePassword($id, $oldPassword)
    {
        $account = $this->accountRepository->findById($id);
        
        if (!$account) {
            throw new \Exception("Không tìm thấy tài khoản");
        }

        return Hash::check($oldPassword, $account->password);
    }

    public function getTotalUsers()
    {
        return $this->accountRepository->count();
    }
}