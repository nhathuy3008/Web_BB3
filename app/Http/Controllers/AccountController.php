<?php

namespace App\Http\Controllers;

use App\Model\Account;
use App\Service\AccountService;
use App\Repository\AccountRepository; // Thêm dòng này
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;
    protected $accountRepository; // Thêm thuộc tính này

    public function __construct(AccountService $accountService, AccountRepository $accountRepository) // Tiêm AccountRepository
    {
        $this->accountService = $accountService;
        $this->accountRepository = $accountRepository; // Gán repository vào thuộc tính
    }
    
    public function create(Request $request)
    {
        $data = $request->validate([
            'fullName' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
    
        try {
            $account = $this->accountService->createAccount($data);
            return response()->json([
                'message' => 'Tài khoản đã được tạo thành công',
                'account' => $account,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400); // Trả về thông điệp lỗi
        }
    }

    public function confirm($token)
    {
        if ($this->accountService->verifyAccount($token)) {
            return response()->json(['message' => 'Tài khoản đã được xác nhận thành công.']);
        }

        return response()->json(['message' => 'Token xác nhận không hợp lệ.'], 404);
    }

    public function getAllAccounts()
    {
        return $this->accountService->getAllAccounts();
    }

    public function getAccountById($id)
    {
        return $this->accountService->getAccountById($id);
    }

    public function updateAccount(Request $request, $id)
    {
        $data = $request->all();
        return $this->accountService->updateAccount($id, $data);
    }

    public function deleteAccount($id)
    {
        $this->accountService->deleteAccount($id);
        return response()->json(['message' => 'Xóa tài khoản thành công']);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        try {
            $account = $this->accountService->login($data['email'], $data['password']);
            return response()->json([
                'message' => 'Đăng nhập thành công',
                'account' => [
                    'id' => $account->id, // Đảm bảo id là chính xác
                    'fullName' => $account->fullName,
                    'email' => $account->email,
                    'image' => $account->image, // Thêm vào đây
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function validatePassword(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|uuid',
            'oldPassword' => 'required|string',
        ]);

        $isValid = $this->accountService->validatePassword($data['id'], $data['oldPassword']);

        return response()->json(['message' => $isValid ? 'Mật khẩu hợp lệ' : 'Mật khẩu cũ không hợp lệ']);
    }

    public function getTotalUsers()
    {
        return response()->json($this->accountService->getTotalUsers());
    }

    // Cập nhật phương thức xác nhận tài khoản
    public function confirmAccount($token)
    {
        // Tìm tài khoản dựa trên token xác thực
        $account = $this->accountRepository->findByVerificationToken($token);

        if (!$account) {
            return response()->json(['message' => 'Token xác nhận không hợp lệ.'], 404);
        }

        // Kích hoạt tài khoản
        $account->enabled = true; 
        $this->accountRepository->save($account); // Lưu thay đổi

        return response()->json(['message' => 'Tài khoản đã được xác nhận thành công.']);
    }
}